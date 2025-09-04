<x-mail::message>
    # Hi {{ $name }},

    Thank you for registering with DCash Wallet. To complete your registration, please use the following verification code:

    <x-mail::panel>
        <div style="font-size: 24px; text-align: center; letter-spacing: 8px; font-weight: bold;">
            {{ $otp }}
        </div>
    </x-mail::panel>

    This code will expire in 5 minutes.

    If you didn't create an account with us, please ignore this email.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
