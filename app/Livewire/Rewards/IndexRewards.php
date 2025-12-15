<?php

namespace App\Livewire\Rewards;

use App\Models\Bonus;
use App\Models\RedemptionRequest;
use App\Models\User;
use Livewire\Component;

class IndexRewards extends Component
{
    public $referralCode;
    public $referralLink;
    public $totalRewards;
    public $referrals;
    public $referralCount;
    public $redemptionHistory;

    public $totalEarned;
    public $totalRedeemed;

    // added
    public $bonuses;

    public function mount()
    {
        $user = auth()->user();

        $this->referralCode = $user->referral_code;
        $this->referralLink = url('/register?ref='.$user->referral_code);

        // Get users referred by this user
        $this->referrals = User::where('referred_by', $user->referral_code)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->referralCount = $this->referrals->count();

        // Get redemption history for this user
        $this->redemptionHistory = RedemptionRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // load bonuses for display
        $this->bonuses = Bonus::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->totalEarned = Bonus::where('user_id', $user->id)
            ->sum('bonus_amount');

        $this->totalRedeemed = RedemptionRequest::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'processing', 'pending'])
            ->sum('amount');

        $this->totalRewards = $this->totalEarned - $this->totalRedeemed;
    }

    public function render()
    {
        return view('livewire.rewards.index-rewards')->layout('layouts.app.app')->title('Rewards');
    }
}
