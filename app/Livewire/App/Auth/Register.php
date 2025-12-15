<?php

namespace App\Livewire\App\Auth;

use App\Mail\RegistrationOtpMail;
use App\Models\Bonus;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Register extends Component
{
    public string $fname = '';
    public string $lname = '';
    public string $email = '';
    public string $password = '';
    public string $phone = '';
    public string $password_confirmation = '';
    public string $pin = '';
    public ?string $referral = null;
    public $referrerName;
    public $terms = false;
    public $country_code = '+234';

    protected array $rules = [
        'referral' => ['nullable', 'exists:users,referral_code'],
        'fname' => ['required', 'string', 'max:255'],
        'lname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
        'pin' => ['required', 'digits:4'],
        'terms' => 'accepted',
        'country_code' => 'required',
    ];

    public function updatedReferral($value)
    {
        if (!empty($value)) {
            $referrer = User::where('referral_code', $value)->first();
            if ($referrer) {
                $this->referrerName = $referrer->fname.' '.$referrer->lname;
                $this->resetValidation('referral');
            } else {
                $this->referrerName = null;
                $this->addError('referral', 'Invalid referral code.');
            }
        } else {
            $this->referrerName = null;
            $this->resetValidation('referral');
        }
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'fname' => $this->fname,
            'lname' => $this->lname,
            'email' => $this->email,
            'phone' => $this->country_code.'-'.$this->phone,
            'pin' => Hash::make($this->pin),
            'is_admin' => 0,
            'password' => Hash::make($this->password),
            'username' => strtolower($this->fname.rand(1000, 9999)),
            'kyc_status' => 'unverified',
            'referral_code' => strtolower(mb_substr(trim($this->fname), 0, 5).rand(1000, 9999)),
            'referred_by' => $this->referral,
        ]);


        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_for_'.$this->email, $otp, now()->addMinutes(5));

        //give registration bonus, first check if user is already given referral bonus
        $existingBonus = Bonus::where('user_id', $user->id)
            ->where('type', 'registration')
            ->first();
        if (!$existingBonus) {
            $bonus = Bonus::create([
                'user_id' => $user->id,
                'type' => 'registration',
                'status' => 'credited',
                'bonus_amount' => 10,
                'trigger_event' => 'registration',
                'notes' => 'New User Welcome Bonus.',
            ]);
        }

        Mail::to($user->email)->send(new RegistrationOtpMail($otp, $user->fname));

        return $this->redirect(route('register.otp', ['email' => $user->email]));

    }

    public function render()
    {
        return view('livewire.app.auth.register')->layout('layouts.auth.app', [
            'title' => 'Register',
        ]);
    }
}
