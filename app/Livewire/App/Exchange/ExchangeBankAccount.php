<?php

namespace App\Livewire\App\Exchange;

use App\Models\SafehavenBank;
use App\Services\SafeHavenApi\TransfersService;
use Livewire\Component;


class ExchangeBankAccount extends Component
{
    public $bank = '';
    public $bankName;
    public $accountNumber = '';
    public $accountName = '';
    public $quoteCurrencyType;
    public $baseCurrencyId;
    public $quoteCurrencyId;
    public $exchangeRate;
    public $baseAmount;
    public $quoteAmount;
    public $baseCurrencyCode;
    public $quoteCurrencyCode;
    public $processingFee = 0.00;
    public $baseCurrencyFlag = '';
    public $quoteCurrencyFlag = '';
    public $isVerifying = false;
    public $networks = [];
    public $phpBanks = [];

    public $useWalletBankDetails = false;

    // Add these crypto-related properties
    public $walletAddress = '';
    public $network = '';

    public function boot(TransfersService $transfersService)
    {
        $this->transfersService = $transfersService;
    }

    public function mount()
    {
        $exchangeData = session('exchangeData');

        // Set default to manual entry if user doesn't have wallet balance
        if (!auth()->user()->wallet || auth()->user()->wallet->balance <= 0) {
            $this->useWalletBankDetails = 'false';
        } else {
            // If user has wallet balance, default to false but show both options
            $this->useWalletBankDetails = 'false';
        }

        if (!$exchangeData) {
            return redirect()->route('dashboard');
        }
        $this->baseCurrencyId = $exchangeData['baseCurrencyId'];
        $this->quoteCurrencyId = $exchangeData['quoteCurrencyId'];
        $this->exchangeRate = $exchangeData['exchangeRate'];
        $this->baseCurrencyCode = $exchangeData['baseCurrencyCode'];
        $this->quoteCurrencyCode = $exchangeData['quoteCurrencyCode'];
        $this->baseAmount = $exchangeData['baseAmount'];
        $this->quoteAmount = $exchangeData['quoteAmount'];
        $this->baseCurrencyFlag = $exchangeData['baseCurrencyFlag'];
        $this->quoteCurrencyFlag = $exchangeData['quoteCurrencyFlag'];
        $this->setCurrencyType();
        $this->setNetworksForCurrency();
        $this->phpBanks = $this->phillipineBanks();

    }

    protected TransfersService $transfersService;


    //supported crypyo currencies
    // This will determine if the quote currency is crypto or fiat
    private function setCurrencyType()
    {
        $cryptoCurrencies = ['BTC', 'ETH', 'USDT', 'USDC', 'BNB', 'MATIC', 'TRX'];

        $this->quoteCurrencyType = in_array($this->quoteCurrencyCode, $cryptoCurrencies) ? 'crypto' : 'fiat';
    }

    // Set networks based on the quote currency code
    // This will help in selecting the correct network for crypto transactions
    // For fiat currencies, this will be an empty array
    private function setNetworksForCurrency()
    {
        if ($this->quoteCurrencyType === 'crypto') {
            $cryptoNetworks = [
                'ETH' => ['ERC20', 'Polygon', 'BSC'],
                'USDT' => ['TRC20'],
                'USDC' => ['ERC20', 'Polygon', 'BSC'],
                'BNB' => ['BSC'],
                'MATIC' => ['Polygon', 'ERC20'],
                'TRX' => ['TRC20'],
                'BTC' => [] // BTC doesn't need network selection
            ];

            $this->networks = $cryptoNetworks[$this->quoteCurrencyCode] ?? [];
        }
    }

    private function phillipineBanks()
    {
        return [
            ["name" => "AGRIBUSINESS RURAL BANK, INC."],
            ["name" => "AL-AMANAH ISLAMIC BANK"],
            ["name" => "Alipay Philippines Inc / Lazada Wallet"],
            ["name" => "ALLBANK , INC. (A THRIFT BANK)"],
            ["name" => "AllBank Inc"],
            ["name" => "ASIA UNITED BANK"],
            ["name" => "AUSTRALIA & NEW ZEALAND BANK"],
            ["name" => "Banana Fintech/ BananaPay"],
            ["name" => "Banco de Oro Unibank Inc (BDO)"],
            ["name" => "BANGKO KABAYAN INC."],
            ["name" => "Bangko Mabuhay"],
            ["name" => "BANGKO NUESTRA SENORA DEL PILAR"],
            ["name" => "BANGKOK BANK PUBLIC CO., LTD."],
            ["name" => "BANK OF AMERICA, NAT'L. ASS'N."],
            ["name" => "Bank of China"],
            ["name" => "Bank Of Commerce"],
            ["name" => "BANK OF FLORIDA"],
            ["name" => "BANK OF MAKATI"],
            ["name" => "Bank of the Philippine Islands (BPI)"],
            ["name" => "BDO Network Bank Inc"],
            ["name" => "BINAN RURAL BANK, INC."],
            ["name" => "Binangonan Rural Bank / BRBDigital"],
            ["name" => "BPI Direct BanKo A Savings Bank"],
            ["name" => "CAMALIG BANK INC"],
            ["name" => "CANTILAN BANK, INC. (A Rural Bank)"],
            ["name" => "CARD Bank Inc"],
            ["name" => "CARD SME BANK INC"],
            ["name" => "CATHAY UNITED BANK CO LTD"],
            ["name" => "Cebuana Lhuillier Rural Bank"],
            ["name" => "China Bank Savings Inc"],
            ["name" => "China Banking Corporation"],
            ["name" => "CIMB BANK PHILIPPINES INC"],
            ["name" => "CIS Bayad Center / Bayad"],
            ["name" => "CITIBANK, N. A."],
            ["name" => "CITY SAVINGS BANK"],
            ["name" => "COOPERATIVE BANK OF QUEZON PROVINCE"],
            ["name" => "COUNTRY BUILDERS BANK,INC."],
            ["name" => "CTBC Bank Phils Corp"],
            ["name" => "DCPay Philippines Inc. / COINS.PH"],
            ["name" => "DEUTSCHE BANK"],
            ["name" => "Development Bank of the Philippines"],
            ["name" => "DM BANK"],
            ["name" => "Dumaugete City Development Bank Inc"],
            ["name" => "DUNGGANON BANK INCORPORATED"],
            ["name" => "East West Banking Corporation"],
            ["name" => "East West Rural Bank / Komo"],
            ["name" => "EASYPAY GLOBAL EMI CORP."],
            ["name" => "Equicom Savings Bank"],
            ["name" => "FIRST CONSOLIDATED BANK"],
            ["name" => "G-Xchange / GCash"],
            ["name" => "GM BANK OF LUZON, INC."],
            ["name" => "GoTyme Bank"],
            ["name" => "GrabPay Philippines"],
            ["name" => "GUAGUA RURAL BANK, INC"],
            ["name" => "HK AND SHANGHAI BANKING CORP."],
            ["name" => "HSBC SAVINGS BANK PHILS.,INC."],
            ["name" => "INDUSTRIAL AND COMMERCIAL BANK OF CHINA"],
            ["name" => "INDUSTRIAL BANK OF KOREA - MANILA"],
            ["name" => "Infoserve / Nationlink"],
            ["name" => "ING BANK N.V."],
            ["name" => "INNOVATIVE BANK"],
            ["name" => "INNOVATIVE BANK, INC. (A Rural Bank)"],
            ["name" => "ISLA Bank"],
            ["name" => "JPMORGAN CHASE BANK"],
            ["name" => "KEB HANA BANK"],
            ["name" => "Laguna Prestige Banking Corporation (A Rural Bank)"],
            ["name" => "LAND BANK OF THE PHILIPPINES"],
            ["name" => "LEGAZPI SAVINGS BANK INC"],
            ["name" => "LOLC BANK PHILIPPINES, INC."],
            ["name" => "LULU FINANCIAL SERVICES (PHILS), INC."],
            ["name" => "Luzon Development Bank"],
            ["name" => "MALARAYAT RURAL BANK,INC"],
            ["name" => "Malayan Bank Savings"],
            ["name" => "Maya Bank, Inc"],
            ["name" => "Maybank Philippines Inc"],
            ["name" => "MEGA INTL COMML BANK CO. LTD"],
            ["name" => "Metrobank"],
            ["name" => "MINDANAO CONSOLIDATED COOPERATIVE"],
            ["name" => "MIZUHO BANK,LTD."],
            ["name" => "MONEY MALL RURAL BANK, INC."],
            ["name" => "MUFG BANK, LTD"],
            ["name" => "MVSM BANK (A RURAL BANK), INC."],
            ["name" => "Netbank(A Rural Bank), Inc."],
            ["name" => "NEW RURAL BANK OF SAN LEONARDO (NUEVA ECIJA), INC"],
            ["name" => "OmniPay Inc"],
            ["name" => "OWN BANK, THE RURAL BANK OF CAVITE CITY, INC."],
            ["name" => "PACIFIC ACE SAVINGS BANK, INC."],
            ["name" => "PalawanPay (PPS-PEPP FINANCIAL SERVICES CORP.)"],
            ["name" => "PARTNER RURAL BANK"],
            ["name" => "PayMaya Philippines Inc"],
            ["name" => "PAYMONGO PAYMENTS, INC."],
            ["name" => "Philippine Bank of Communications"],
            ["name" => "PHILIPPINE BUSINESS BANK"],
            ["name" => "PHILIPPINE DIGITAL ASSET EXCHANGE (PDAX), INC."],
            ["name" => "Philippine National Bank"],
            ["name" => "Philippine Savings Bank"],
            ["name" => "Philippine Veterans Bank"],
            ["name" => "PHILTRUST BANK"],
            ["name" => "Producers Savings Bank Corporation"],
            ["name" => "QUEEN CITY DEVELOPMENT BANK INC"],
            ["name" => "Quezon Capital Rural Bank Inc"],
            ["name" => "RANG-AY BANK,INC. (A RURAL BANK)"],
            ["name" => "RBT BANK, INC., A Rural Bank"],
            ["name" => "RCBC"],
            ["name" => "ROBINSONS BANK CORPORATION"],
            ["name" => "RURAL BANK OF BACOLOD CITY, INC"],
            ["name" => "RURAL BANK OF BAUANG, INC"],
            ["name" => "RURAL BANK OF DIGOS, INC"],
            ["name" => "Rural Bank of Guinobatan / Asenso"],
            ["name" => "RURAL BANK OF LA PAZ, INC."],
            ["name" => "RURAL BANK OF LEBAK (SULTAN KUDARAT), INC."],
            ["name" => "RURAL BANK OF MANGALDAN, INC"],
            ["name" => "RURAL BANK OF MONTALBAN, INC"],
            ["name" => "RURAL BANK OF PORAC (PAMP), INC"],
            ["name" => "RURAL BANK OF ROSARIO (LA UNION), INC."],
            ["name" => "RURAL BANK OF SAGAY, INC."],
            ["name" => "RURAL BANK OF SAN MEDJUGORJE"],
            ["name" => "RURAL BANK OF SAN NARCISO, INC."],
            ["name" => "RURAL BANK OF SILAY CITY, INC."],
            ["name" => "RURAL BANK OF STA. IGNACIA, INC."],
            ["name" => "Seabank Philippines, Inc."],
            ["name" => "Security Bank Corporation"],
            ["name" => "SHINHAN BANK"],
            ["name" => "ShopeePay Philippines Inc"],
            ["name" => "STANDARD CHARTERED BANK"],
            ["name" => "Starpay Corporation"],
            ["name" => "Sterling Bank of Asia Inc"],
            ["name" => "SUMITOMO MITSUI BANKING CORP"],
            ["name" => "SUMMIT BANK (RURAL BANK OF TUBLAY, INC.)"],
            ["name" => "Sun Savings Bank"],
            ["name" => "TAGCASH LTD. INC."],
            ["name" => "TAYOCASH INC"],
            ["name" => "Tonik Bank"],
            ["name" => "TONIK DIGITAL BANK, INC"],
            ["name" => "Topjuan Tech Corporation"],
            ["name" => "UCPB SAVINGS BANK"],
            ["name" => "Unionbank of the Philippines"],
            ["name" => "UnionDigital Bank"],
            ["name" => "United Coconut Planters Bank"],
            ["name" => "UNITED OVERSEAS BANK PHILS"],
            ["name" => "UNObank"],
            ["name" => "USSC Money Services Inc"],
            ["name" => "VIGAN BANCO RURAL, INCORPORADA"],
            ["name" => "WEALTH DEVELOPMENT BANK CORPORATION"],
            ["name" => "YUANTA SAVINGS BANK,INC"],
            ["name" => "ZAMBALES RURAL BANK, INC"],
            ["name" => "Zybi Tech Inc. / JuanCash"]
        ];
    }

    public function goBack()
    {
        return redirect()->route('dashboard');
    }

    public function updatedBaseCurrencyCode()
    {
        $this->setNetworksForCurrency();
    }

    public function updatedAccountNumber($value)
    {
        $this->accountNumber = $value;

        if ($this->quoteCurrencyCode === 'NGN') {

            if (strlen($this->accountNumber) === 10 && $this->bank !== '') {
                $this->simulateAccountNameFetch();
            } else {
                $this->accountName = '';
            }
        }

    }

    public function simulateAccountNameFetch()
    {
        $this->isVerifying = true;
        $this->accountName = 'Account name will appear here';

        try {
            // Get the bank code from SafehavenBank model
            $bank = SafehavenBank::find($this->bank);


            if (!$bank) {
                //log the error if bank not found

                $this->accountName = 'Invalid Bank';
                $this->isVerifying = false;
                $this->dispatch('account-verified');
                return;
            }

            $response = $this->transfersService->accountNameEnquiry(
                $this->accountNumber,
                $bank->code,
            );

            if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
                $this->accountName = $response['json']['data']['accountName'] ?? 'N/A';
            } else {
                $this->accountName = $response['json']['message'] ?? 'Invalid Account Number';
            }
        } catch (\Exception $e) {
            $this->accountName = 'Error fetching account name';
            \Illuminate\Support\Facades\Log::error('Account verification failed', [
                'error' => $e->getMessage()
            ]);
        }

        $this->isVerifying = false;
        $this->dispatch('account-verified');
    }

    public function submit()
    {
        $validated = $this->validate();


        $exchangeData = [
            'baseCurrencyId' => $this->baseCurrencyId,
            'quoteCurrencyId' => $this->quoteCurrencyId,
            'exchangeRate' => $this->exchangeRate,
            'baseAmount' => $this->baseAmount,
            'quoteAmount' => $this->quoteAmount,
            'baseCurrencyCode' => $this->baseCurrencyCode,
            'quoteCurrencyCode' => $this->quoteCurrencyCode,
            'processingFee' => $this->processingFee,
            'baseCurrencyFlag' => $this->baseCurrencyFlag,
            'quoteCurrencyFlag' => $this->quoteCurrencyFlag,
        ];


        if ($this->quoteCurrencyType === 'fiat') {

            if ($this->quoteCurrencyCode === 'NGN') {
                if ($this->useWalletBankDetails === 'true') {
                    $wallet = auth()->user()->virtualBankAccount;
                    $exchangeData['bank'] = $wallet->bank_name ?? 'Wallet Bank';
                    $exchangeData['accountNumber'] = $wallet->account_number ?? '';
                    $exchangeData['accountName'] = $wallet->account_name ?? auth()->user()->fname.' '.auth()->user()->lname;
                    $exchangeData['note'] = ['receiving_bank' => 'DCASH Wallet'];
                } else {
                    $exchangeData['bank'] = $this->getBankName($this->bank);
                    $exchangeData['accountNumber'] = $this->accountNumber;
                    $exchangeData['accountName'] = $this->accountName;
                }
            } else {
                $exchangeData['bank'] = $this->bankName;
                $exchangeData['accountNumber'] = $this->accountNumber;
                $exchangeData['accountName'] = $this->accountName;
            }

        } else {
            $exchangeData['walletAddress'] = $this->walletAddress;
            $exchangeData['network'] = $this->network;
        }

        session(['exchangeData' => $exchangeData]);

        return redirect()->route('exchange.payment');
    }

    private function getBankName($bankId)
    {
        $bank = SafehavenBank::find($bankId);
        return $bank->name ?? 'Unknown Bank';
    }

    public function render()
    {
        //get all banks
        $banks = SafehavenBank::orderBy('name', 'asc')->get(); // Fetch all currencies
        return view('livewire.app.exchange.exchange-bank-account', compact('banks'))->layout('layouts.app.app',
            [
                'title' => 'Enter Bank Account - Dcash Wallet',
            ]);
    }

    protected function rules()
    {
        if ($this->quoteCurrencyType === 'fiat') {
            if ($this->quoteCurrencyCode === 'NGN') {
                // Skip validation if using wallet bank details
                if ($this->useWalletBankDetails === 'true') {
                    return [
                        'useWalletBankDetails' => 'required'
                    ];
                }

                return [
                    'bank' => 'required|string',
                    'accountNumber' => 'required|digits:10',
                    'useWalletBankDetails' => 'required'
                ];
            } else {
                return [
                    'bankName' => 'required|string',
                    'accountNumber' => 'required|string',
                    'accountName' => 'required|string',
                ];
            }
        } else {
            $rules = [
                'walletAddress' => 'required|string|min:26',
            ];

            if ($this->quoteCurrencyCode !== 'BTC') {
                $rules['network'] = 'required|string';
            }

            return $rules;
        }
    }
}
