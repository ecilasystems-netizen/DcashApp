@extends('layouts.email')

@section('title', 'OTP Verification - DCash Wallet')

@push('styles')
    <style>
        .otp-container {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
            border: 2px dashed #856404;
        }

        .otp-code {
            color: #856404;
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 3px;
        }

        .warning-text {
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
    <h2 class="brand-color">OTP Verification Required</h2>

    <p>Hello <strong>{{ $name }}</strong>,</p>

    <p>Please use the following OTP code to complete your transaction:</p>

    <div class="otp-container">
        <h1 class="otp-code">{{ $otp }}</h1>
    </div>

    <div class="warning-text">
        <strong>Important:</strong> This code will expire in <strong>10 minutes</strong> for security reasons.
    </div>

    <p>If you didn't request this verification, please ignore this email and contact our support team immediately.</p>
@endsection
