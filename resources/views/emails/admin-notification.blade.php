@extends('layouts.email')

@section('title', 'Admin Notification - DCash Wallet')

@push('styles')
    <style>
        .notification-container {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #5e4932;
        }

        .notification-type {
            color: #bca328;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .user-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .details-container {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            color: #495057;
        }

        .detail-value {
            color: #6c757d;
        }

        .action-button {
            display: inline-block;
            background-color: #a57630;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }

        .priority-high {
            border-left-color: #dc3545;
        }

        .priority-high .notification-type {
            color: #dc3545;
        }

        .timestamp {
            color: #6c757d;
            font-size: 14px;
            font-style: italic;
        }
    </style>
@endpush

@section('content')
    @php
        $typeConfig = [
            'kyc_submission' => [
                'title' => 'KYC Submission',
                'message' => 'A new KYC submission requires your review.',
                'priority' => 'high'
            ],
            'limit_upgrade' => [
                'title' => 'Limit Upgrade Request',
                'message' => 'A user has requested an account limit upgrade.',
                'priority' => 'normal'
            ],
            'bonus_redemption' => [
                'title' => 'Bonus Redemption',
                'message' => 'A bonus redemption request requires approval.',
                'priority' => 'normal'
            ],
            'wallet_transaction' => [
                'title' => 'Wallet Transaction Alert',
                'message' => 'A wallet transaction requires your attention.',
                'priority' => 'high'
            ],
            'exchange_transaction' => [
                'title' => 'Exchange Transaction Alert',
                'message' => 'An exchange transaction requires your attention.',
                'priority' => 'high'
            ]
        ];

        $config = $typeConfig[$notificationType] ?? [
            'title' => 'Admin Notification',
            'message' => 'A new notification requires your attention.',
            'priority' => 'normal'
        ];
    @endphp

    <h2 class="brand-color">{{ $config['title'] }} - Admin Review Required</h2>

    <div class="notification-container {{ $config['priority'] === 'high' ? 'priority-high' : '' }}">
        <h3 class="notification-type">{{ $config['title'] }}</h3>
        <p>{{ $config['message'] }}</p>
        <div class="timestamp">Received: {{ now()->format('M d, Y \a\t g:i A') }}</div>
    </div>

    <div class="user-info">
        <h4 style="margin: 0 0 10px 0; color: #495057;">User Information</h4>
        <div class="detail-row">
            <span class="detail-label">Name:</span>
            <span class="detail-value">{{ $userName }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value">{{ $userEmail }}</span>
        </div>
    </div>

    @if(!empty($details))
        <div class="details-container">
            <h4 style="margin: 0 0 15px 0; color: #495057;">Additional Details</h4>
            @foreach($details as $key => $value)
                <div class="detail-row">
                    <span class="detail-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                    <span class="detail-value">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @if($actionUrl)
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $actionUrl }}" class="action-button">Review & Take Action</a>
        </div>
    @endif

    <div
        style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <strong>Note:</strong> Please review this notification promptly to ensure smooth operations and user experience.
    </div>

@endsection
