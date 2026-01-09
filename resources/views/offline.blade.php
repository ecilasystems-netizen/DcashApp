<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1a1a1a">
    <title>Offline - DCash</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 1rem;
        }

        .container {
            max-width: 400px;
            width: 100%;
        }

        .icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        p {
            color: #9ca3af;
            margin-bottom: 2.5rem;
            font-size: 1rem;
            line-height: 1.6;
        }

        button {
            background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            width: 100%;
            max-width: 200px;
        }

        button:active {
            transform: scale(0.98);
        }

        .status {
            margin-top: 2rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .online {
            color: #10b981;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">📡</div>
        <h1>You're Offline</h1>
        <p>Please check your internet connection and try again. Your app will work once you're back online.</p>
        <button onclick="checkConnection()">Retry Connection</button>
        <div class="status" id="status">Checking connection...</div>
    </div>

</body>
</html>
