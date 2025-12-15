<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Bonus;
use App\Models\ExchangeTransaction;
use App\Models\ReferralBonus;
use App\Models\User;
use App\Services\SafeHavenApi\TransfersService;
use App\Services\SafeHavenService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $perPage = 50;

    public $selectedBank = '';
    public $banks = [
        'Polaris Bank',
        'Providus Bank',
        'Guaranty Trust Bank (GTBank)',
        'United Bank for Africa (UBA)',
        'Zenith Bank',
        'Opay',
        'Kuda Bank',
        'Rubies Bank',
        'Fidelity Bank',
        'Access Bank',
        'SafeHaven ',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => '']
    ];
    protected $paginationTheme = 'tailwind';
    protected TransfersService $transfersService;

    public $safeHavenAccount = null;
    public $safeHavenAccountBalance = null;


    public function mount()
    {
        // Get the last used status filter from session or use current status
        $this->statusFilter = Session::get('transaction_status_filter', $this->statusFilter);
        $this->fetchSafeHavenAccounts();

    }

    public function boot(TransfersService $transfersService)
    {
        $this->transfersService = $transfersService;
    }

    public function approveAndPay($transactionId)
    {
        try {
            $transaction = ExchangeTransaction::with(['toCurrency', 'fromCurrency', 'user'])
                ->findOrFail($transactionId);

            if ($transaction->status === 'completed') {
                session()->flash('error', 'Transaction is already completed.');
                return;
            }

            // Store selected bank if NGN transaction
            if ($transaction->toCurrency->code === 'NGN') {
                $note = $transaction->note ?? [];
                $note['transfer_method'] = 'SafeHavenPay';
                $transaction->note = $note;
                $transaction->save();
            }

            // Step 1: Verify account details
            $accountEnquiry = $this->transfersService->accountNameEnquiry(
                $transaction->recipient_account_number,
                $transaction->recipient_bank_code
            );

            if (!in_array($accountEnquiry['status'], [200, 201])) {
                session()->flash('error',
                    'Account verification failed: '.($accountEnquiry['json']['message'] ?? 'Unknown error'));
                return;
            }

            $accountName = $accountEnquiry['json']['data']['accountName'] ?? null;
            $nameEnquiryReference = $accountEnquiry['json']['data']['sessionId'] ?? null;

            if (!$nameEnquiryReference) {
                session()->flash('error', 'Name enquiry reference not found');
                return;
            }

            // Verify account name matches
            if ($accountName !== $transaction->recipient_account_name) {
                Log::warning('Account name mismatch', [
                    'expected' => $transaction->recipient_account_name,
                    'received' => $accountName
                ]);
            }

            // Step 2: Initiate transfer
            $transferData = [
                'nameEnquiryReference' => $nameEnquiryReference,
                'beneficiaryBankCode' => $transaction->recipient_bank_code,
                'debitAccountNumber' => config('safehaven.debit_account_number'),
                'beneficiaryAccountNumber' => $transaction->recipient_account_number,
                'amount' => (int) $transaction->amount_to,
                'narration' => 'transfer-'.$transaction->reference ?? '',
                'paymentReference' => now()->format('YmdHis'),

            ];

            $transferResponse = $this->transfersService->initiateTransfer($transferData);

            if (!in_array($transferResponse['status'], [200, 201])) {
                session()->flash('error',
                    'Transfer failed: '.($transferResponse['json']['message'] ?? 'Unknown error'));
                return;
            }

            // Update transaction status
            $transaction->update([
                'status' => 'completed'
            ]);

            // Store transaction metadata
            $note = $transaction->note ?? [];
            $note['transfer_session_id'] = $transferResponse['json']['data']['sessionId'] ?? null;
            $note['transfer_status'] = $transferResponse['json']['data']['status'] ?? null;
            $note['verified_account_name'] = $accountName;
            $note['transfer_date'] = now()->toDateTimeString();
            $transaction->note = $note;
            $transaction->save();

            session()->flash('message', 'Transaction approved and bank transfer initiated successfully.');
            return redirect()->to(request()->header('Referer'));


        } catch (\Exception $e) {
            Log::error('Approve and Pay failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to process: '.$e->getMessage());
        }
    }

    public function fetchSafeHavenAccounts()
    {
        try {
            $safeHaven = new SafeHavenService();

            // Get specific account details
            $this->safeHavenAccount = $safeHaven->getAccountByNumber('0119358126');

            // Set balance if account exists
            if ($this->safeHavenAccount) {
                $this->safeHavenAccountBalance = $this->safeHavenAccount['accountBalance'];
            }

        } catch (\Exception $e) {
            logger()->error('SafeHaven account fetch failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getCurrencyStatsProperty()
    {
        $completed = ExchangeTransaction::with(['fromCurrency', 'toCurrency'])
            ->where('status', 'completed')
            ->get();

        $currencyStats = [];

        foreach ($completed as $transaction) {
            $fromCode = $transaction->fromCurrency->code;
            $toCode = $transaction->toCurrency->code;

            // Track sent amounts (from_currency)
            if (!isset($currencyStats[$fromCode])) {
                $currencyStats[$fromCode] = [
                    'currency' => $transaction->fromCurrency,
                    'total_sent' => 0,
                    'total_received' => 0,
                ];
            }
            $currencyStats[$fromCode]['total_sent'] += $transaction->amount_from;

            // Track received amounts (to_currency)
            if (!isset($currencyStats[$toCode])) {
                $currencyStats[$toCode] = [
                    'currency' => $transaction->toCurrency,
                    'total_sent' => 0,
                    'total_received' => 0,
                ];
            }
            $currencyStats[$toCode]['total_received'] += $transaction->amount_to;
        }

        return collect($currencyStats);
    }

    public function updatedStatusFilter($value)
    {
        // Store the new status filter in session
        Session::put('transaction_status_filter', $value);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateFilter']);
        $this->statusFilter = '';
        Session::forget('transaction_status_filter');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function approveTransaction($transactionId, $selectedBank = null)
    {
        $transaction = ExchangeTransaction::findOrFail($transactionId);

        // If NGN transaction and bank is selected, store it in the note
        if ($transaction->toCurrency->code === 'NGN' && $selectedBank) {
            $note = $transaction->note ?? [];
            $note['transfer_bank'] = $selectedBank;
            $transaction->note = $note;
        }

        $transaction->update([
            'status' => 'completed'
        ]);

        //check for the referral of the user and credit bonus if applicable
        if ($transaction->user->referrer && $transaction->status === 'completed') {
            // Check if referral bonus already exists for this user
            $existingBonus = ReferralBonus::where('referred_user_id', $transaction->user_id)
                ->where('trigger_event', 'first_exchange')
                ->first();

            if (!$existingBonus) {
                // Create referral bonus (adjust amount as needed)
                $bonusAmount = 2.00; // Set your bonus amount

                $referralBonus = ReferralBonus::create([
                    'referrer_id' => $transaction->user->referrer->id,
                    'referred_user_id' => $transaction->user_id,
                    'bonus_amount' => $bonusAmount,
                    'status' => 'credited',
                    'trigger_event' => 'first_exchange',
                    'credited_at' => now(),
                    'notes' => 'Referral Bonus ['.$transaction->user->fname.' '.$transaction->user->lname.']',
                ]);
                // Optionally, you can notify the referrer about the bonus

                //create bonus record if not exists
                $checkBonus = Bonus::where('referral_bonus_id', $referralBonus->id)
                    ->where('type', 'referral')
                    ->first();
                if (!$checkBonus) {
                    $bonus = Bonus::create([
                        'user_id' => $transaction->user->referrer->id,
                        'type' => 'referral',
                        'referral_bonus_id' => $referralBonus->id ?? null,
                        'status' => 'credited',
                        'bonus_amount' => $bonusAmount,
                        'trigger_event' => 'referral_first_exchange',
                        'notes' => 'Referral Bonus ['.$transaction->user->fname.' '.$transaction->user->lname.']',
                    ]);
                }
            }


        }


        // Check if note exists and has receiving_bank key
        if ($transaction->note && is_array($transaction->note) && isset($transaction->note['receiving_bank'])) {


            $user = $transaction->user;
            $wallet = $user->wallet;

            if (!$wallet) {
                Log::error('No wallet found for user', [
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id
                ]);
                return;
            }


            $balanceBefore = $wallet->balance;
            $wallet->balance += $transaction->amount_to;
            $wallet->save();
            $balanceAfter = $wallet->balance;


            // Record transaction
            $walletTransaction = $wallet->transactions()->create([
                'reference' => 'EXCH-'.strtoupper(uniqid()),
                'wallet_id' => $wallet->id,
                'type' => 'exchange_in',
                'direction' => 'credit',
                'user_id' => $user->id,
                'amount' => $transaction->amount_to,
                'charge' => 0,
                'description' => 'Exchange credited for transaction '.$transaction->reference,
                'status' => 'completed',
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'metadata' => [
                    'transaction_id' => $transaction->id,
                    'receiving_bank' => $transaction->note['receiving_bank'],
                ],
            ]);


        }

        session()->flash('message', 'Transaction approved successfully.');
    }

    public function rejectTransaction($transactionId, $reason)
    {
        $transaction = ExchangeTransaction::findOrFail($transactionId);
        $transaction->update([
            'status' => 'rejected',
            'note' => [
                'rejected' => true,
                'rejection_reason' => $reason
            ]
        ]);

        session()->flash('message', 'Transaction rejected successfully.');
    }

    public function deleteTransaction($transactionId)
    {
        ExchangeTransaction::findOrFail($transactionId)->delete();

        session()->flash('message', 'Transaction deleted successfully.');
    }


    private function getTransactionsQuery(): Builder
    {
        return ExchangeTransaction::with(['user', 'fromCurrency', 'toCurrency', 'agent'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('fname', 'like', '%'.$this->search.'%')
                                ->orWhere('lname', 'like', '%'.$this->search.'%')
                                ->orWhere('email', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('agent', function ($agentQuery) {
                            $agentQuery->where('fname', 'like', '%'.$this->search.'%')
                                ->orWhere('lname', 'like', '%'.$this->search.'%')
                                ->orWhere('email', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter, function ($query) {
                $query->whereDate('created_at', $this->dateFilter);
            })
            ->latest();
    }

    public function exportExcel()
    {
        try {
            Log::info('Export Excel method called');

            $transactions = $this->getTransactionsQuery()->get();
            Log::info('Transactions count: '.$transactions->count());

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                'A1' => 'Transaction ID',
                'B1' => 'User Name',
                'C1' => 'Email',
                'D1' => 'Phone',
                'E1' => 'From Currency',
                'F1' => 'To Currency',
                'G1' => 'Amount From',
                'H1' => 'Amount To',
                'I1' => 'Rate',
                'J1' => 'Recipient Bank',
                'K1' => 'Account Number',
                'L1' => 'Account Name',
                'M1' => 'Status',
                'N1' => 'Assigned Agent',
                'O1' => 'Date Created'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Style headers
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '366092']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ];
            $sheet->getStyle('A1:O1')->applyFromArray($headerStyle);

            // Add data rows
            $row = 2;
            foreach ($transactions as $transaction) {
                $sheet->setCellValue('A'.$row, $transaction->reference);
                $sheet->setCellValue('B'.$row, $transaction->user->fname.' '.$transaction->user->lname);
                $sheet->setCellValue('C'.$row, $transaction->user->email);
                $sheet->setCellValue('D'.$row, $transaction->user->phone ?? 'N/A');
                $sheet->setCellValue('E'.$row, $transaction->fromCurrency->code ?? 'N/A');
                $sheet->setCellValue('F'.$row, $transaction->toCurrency->code ?? 'N/A');
                $sheet->setCellValue('G'.$row, $transaction->amount_from);
                $sheet->setCellValue('H'.$row, $transaction->amount_to);
                $sheet->setCellValue('I'.$row, $transaction->rate);
                $sheet->setCellValue('J'.$row, $transaction->recipient_bank_name ?? 'N/A');
                $sheet->setCellValue('K'.$row, $transaction->recipient_account_number ?? 'N/A');
                $sheet->setCellValue('L'.$row, $transaction->recipient_account_name ?? 'N/A');
                $sheet->setCellValue('M'.$row, $transaction->status);
                $sheet->setCellValue('N'.$row,
                    $transaction->agent ? $transaction->agent->fname.' '.$transaction->agent->lname : 'Unassigned');
                $sheet->setCellValue('O'.$row, $transaction->created_at->format('Y-m-d H:i:s'));
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'O') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Create writer and return response
            $writer = new Xlsx($spreadsheet);

            $response = new StreamedResponse(function () use ($writer) {
                $writer->save('php://output');
            });

            $response->headers->set('Content-Type',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition',
                'attachment; filename="transactions_'.date('Y-m-d').'.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        } catch (\Exception $e) {
            Log::error('Excel export error: '.$e->getMessage());
            session()->flash('message', 'Export failed: '.$e->getMessage());
            return;
        }
    }

    public function getTransactionsProperty()
    {
        return $this->getTransactionsQuery()->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        return [
            'total' => ExchangeTransaction::count(),
            'pending' => ExchangeTransaction::where('status', 'pending_confirmation')->count(),
            'completed' => ExchangeTransaction::where('status', 'completed')->count(),
            'failed' => ExchangeTransaction::whereIn('status', ['failed', 'rejected'])->count(),
        ];
    }

    public function assignToAgent($transactionId, $agentId)
    {
        $transaction = ExchangeTransaction::findOrFail($transactionId);
        $transaction->update([
            'agent_id' => $agentId
        ]);

        session()->flash('message', 'Transaction assigned to agent successfully.');
    }

    public function getAgentsProperty()
    {
        return User::where('is_agent', 1)->get(['id', 'fname', 'lname', 'email']);
    }


    public function render()
    {
        return view('livewire.admin.transactions.transaction-list')->layout('layouts.admin.app', [
            'title' => 'Transactions',
            'description' => 'List of all transactions',
        ]);
    }
}
