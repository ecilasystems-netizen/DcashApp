<!-- resources/views/layouts/email.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DCash Wallet')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            border-bottom: 2px solid #856404;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .brand-color {
            color: #856404;
        }

        .email-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <img src="{{ asset('logo-with-text-black.png') }}" alt="Dcash Wallet Logo" style="max-width: 150px;">
    </div>

    <div class="email-content">
        @yield('content')
    </div>

    <div class="email-footer">
        <p>Best regards,<br><strong>DCash Wallet</strong></p>
        <p style="font-size: 12px; color: #999;">
            This email was sent from DCash Wallet. If you have any questions, please contact our support team.
        </p>
    </div>
</div>
</body>
</html>
