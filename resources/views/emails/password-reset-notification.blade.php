@extends('layouts.email')

@section('title', 'Password Updated - DCash Wallet')

@push('styles')
    <style>
        .success-container {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
            border: 2px solid #856404;
        }

        .success-icon {
            color: #856404;
            font-size: 48px;
            margin-bottom: 15px;
        }

        .info-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
@endpush

@section('content')
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h2 class="brand-color">Password Successfully Updated</h2>
    </div>

    <p>Hello <strong>{{ $name }}</strong>,</p>

    <p>Your password has been successfully changed. This email confirms that your DCash Wallet account password was
        updated on {{ now()->format('F j, Y \a\t g:i A') }}.</p>

    <div class="info-box">
        <strong>Device Information:</strong><br>
        Browser: {{ $browser ?? 'Unknown' }}<br>
        Location: {{ $location ?? 'Unknown' }}
    </div>

    <p>If you did not make this change, please contact our support team immediately or secure your account by:</p>

    <center>
        <a href="{{ route('reset-password') }}"
           style="display: inline-block; background-color: #856404; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; margin: 20px 0;">
            Reset Your Password
        </a>
    </center>

    <p>For security reasons, you will need to re-login on all your devices.</p>
@endsection
