@extends('layouts.email')

@section('title', 'Welcome to DCash Wallet - A Message from our CEO')

@push('styles')
    <style>
        .ceo-banner {
            background-color: #f4f4f4;
            padding: 25px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
            border: 2px solid #856404;
        }

        .ceo-signature {
            font-family: 'Brush Script MT', cursive;
            font-size: 24px;
            color: #856404;
            margin: 15px 0;
        }

        .benefit-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .highlight {
            color: #856404;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="ceo-banner">
        {{--        <img src="{{ asset('images/ceo-avatar.png') }}" alt="CEO" style="width: 100px; height: 100px; border-radius: 50%; margin-bottom: 15px;">--}}
        <h2 class="brand-color">A Personal Welcome from Our CEO</h2>
    </div>

    <p>Dear <strong>{{ $name }}</strong>,</p>

    <p>I'm thrilled to personally welcome you to DCash Wallet. Thank you for choosing us as your trusted currency
        exchange partner.</p>

    <div class="benefit-box">
        <h3>Why You'll Love DCash Wallet:</h3>
        <ul>
            <li>🔒 <span class="highlight">Bank-Grade Security</span> - Your funds are protected with military-grade
                encryption
            </li>
            <li>💱 <span class="highlight">Competitive Rates</span> - We guarantee the best exchange rates in the market
            </li>
            <li>⚡ <span class="highlight">Lightning-Fast Transfers</span> - Most transactions complete within minutes
            </li>
            <li>📱 <span class="highlight">24/7 Support</span> - Our dedicated team is always here to help</li>
        </ul>
    </div>

    <p>As a new member, you'll receive premium rates on your transactions. I invite you to experience our
        platform's seamless exchange process today.</p>

    <center>
        <a href="{{ route('dashboard') }}"
           style="display: inline-block; background-color: #856404; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; margin: 20px 0;">
            Start Trading Now
        </a>
    </center>

    <p>If you ever need assistance or have suggestions for improvement, please don't hesitate to reach out to our
        support team or me personally.</p>

    <p>Welcome to the DCash Wallet family!</p>

    <div class="ceo-signature">
        Regards,<br>
        <span style="font-size: 14px; font-family: Arial, sans-serif;">CEO, DCash Wallet</span>
    </div>
@endsection
