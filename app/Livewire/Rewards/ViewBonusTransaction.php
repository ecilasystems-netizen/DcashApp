<?php

namespace App\Livewire\Rewards;

use App\Models\Bonus;
use Livewire\Component;

class ViewBonusTransaction extends Component
{
    public $reference;
    public $bonusData = [];
    public $backUrl;
    public $randomAd;

    public function mount($ref, $backUrl = null)
    {
        // Random ads
        $this->randomAd = \App\Models\Advertisement::where('is_active', true)
            ->inRandomOrder()
            ->first();

        if ($this->randomAd) {
            $this->randomAd->incrementImpressions();
        }

        $this->reference = $ref;

        // Get backUrl from query string or use a default
        $this->backUrl = request()->query('backUrl', route('exchange.transactions'));

        // Check if the reference is valid
        $bonus = Bonus::where('id', $this->reference)
            ->where('user_id', auth()->id())
            ->first();

        if (!$bonus) {
            return redirect()->route('dashboard')->with('error',
                'Bonus not found or you do not have permission to view it.');
        }

        $this->bonusData = [
            'id' => $bonus->id,
            'bonus_amount' => $bonus->bonus_amount,
            'type' => $bonus->type ?? 'general',
            'trigger_event' => $bonus->trigger_event ?? null,
            'status' => $bonus->status ?? 'pending',
            'is_claimable' => $bonus->status !== 'claimed',
            'notes' => $bonus->notes ?? null,
            'is_referral_bonus' => $bonus->is_referral_bonus,
            'referral_bonus_id' => $bonus->referral_bonus_id ?? null,
            'created_at' => $bonus->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $bonus->updated_at->format('Y-m-d H:i:s'),
            'sender_name' => $bonus->user->fname.' '.$bonus->user->lname,
            'user_email' => $bonus->user->email,
        ];

        // If it's a referral bonus, add referral details
        if ($bonus->is_referral_bonus && $bonus->referralBonus) {
            $this->bonusData['referral_details'] = [
                'referrer_name' => $bonus->referralBonus->user->fname.' '.$bonus->referralBonus->user->lname,
                'referral_code' => $bonus->referralBonus->code,
                'bonus_percentage' => $bonus->referralBonus->bonus_percentage,
                'min_transaction_amount' => $bonus->referralBonus->min_transaction_amount,
            ];
        }
    }

    public function claimBonus()
    {
        try {
            $bonus = Bonus::find($this->bonusData['id']);

            if ($bonus->status === 'claimed') {
                $this->dispatch('notify', [
                    'type' => 'warning',
                    'message' => 'This bonus has already been claimed.'
                ]);
                return;
            }

            // Add bonus amount to user's wallet
            $wallet = $bonus->user->getWallet();
            $wallet->balance += $bonus->bonus_amount;
            $wallet->save();

            // Update bonus status
            $bonus->update([
                'status' => 'claimed',
            ]);

            // Update component data
            $this->bonusData['status'] = 'claimed';
            $this->bonusData['is_claimable'] = false;
            $this->bonusData['updated_at'] = now()->format('Y-m-d H:i:s');

            // Log the claim action
            \Log::info('Bonus claimed', [
                'user_id' => $bonus->user_id,
                'bonus_id' => $bonus->id,
                'amount' => $bonus->bonus_amount,
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '₦'.number_format($bonus->bonus_amount,
                        2).' bonus has been successfully added to your wallet!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error claiming bonus: '.$e->getMessage());
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to claim bonus. Please try again.'
            ]);
        }
    }

    public function downloadReceipt()
    {
        $this->dispatch('captureAndDownload');
    }

    public function shareReceipt()
    {
        $this->dispatch('captureAndShare');
    }

    public function render()
    {
        return view('livewire.rewards.view-bonus-transaction')->layout('layouts.app.app')->title('Bonus Receipt');
    }
}
