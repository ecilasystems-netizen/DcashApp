@extends('layouts.email')

@section('title', 'Transaction Refund - DCash Wallet')

@push('styles')
    <style>
        .refund-container {
            background-color: #e8f5e8;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
            border: 2px solid #28a745;
        }

        .refund-amount {
            color: #28a745;
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }

        .transaction-details {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: left;
        }

        .success-text {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: bold;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <h2 class="brand-color">Transaction Refund Processed</h2>

    <p>Hello <strong>{{ $userName }}</strong>,</p>

    <p>We have successfully processed a refund for your failed transaction. The amount has been credited back to your
        wallet.</p>

    <div class="refund-container">
        <h3 style="margin: 0; color: #28a745;">Refund Amount</h3>
        <div
            class="refund-amount">{{ $originalTransaction->wallet->currency->code ?? 'NGN' }} {{ number_format($refundTransaction->amount, 2) }}</div>
    </div>

    <div class="transaction-details">
        <h4 style="margin-top: 0; color: #495057;">Transaction Details</h4>

        <div class="detail-row">
            <span class="detail-label">Original Transaction:</span>
            <span>#{{ $originalTransaction->reference }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Refund Reference:</span>
            <span>#{{ $refundTransaction->reference }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Transaction Type:</span>
            <span>{{ ucfirst($originalTransaction->type) }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Original Date:</span>
            <span>{{ $originalTransaction->created_at->format('M j, Y \a\t g:i A') }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Refund Date:</span>
            <span>{{ $refundTransaction->created_at->format('M j, Y \a\t g:i A') }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">New Wallet Balance:</span>
            <span>{{ $originalTransaction->wallet->currency->code ?? 'NGN' }} {{ number_format($refundTransaction->balance_after, 2) }}</span>
        </div>
    </div>

    <div class="success-text">
        <strong>Good news!</strong> The refunded amount is now available in your wallet and can be used for future
        transactions.
    </div>

    <p>If you have any questions about this refund or need assistance, please don't hesitate to contact our customer
        support team.</p>

    <p>Thank you for using DCash Wallet!</p>
@endsection
