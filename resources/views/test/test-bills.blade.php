<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Bills</title>
    <style>
        form {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        div {
            margin-bottom: 10px;
        }

        label {
            display: inline-block;
            width: 120px;
        }

        button {
            margin-top: 10px;
            padding: 5px 15px;
        }
    </style>
</head>
<body>
<h1>Test Bills Page</h1>
<p>Test Flutterwave Bills API endpoints</p>

<h2>Get Billers by Category</h2>
<form method="get" action="{{route('bills.billers')}}">
    @csrf
    <div>
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="" disabled selected>Select category</option>
            <option value="AIRTIME">AIRTIME</option>
            <option value="MOBILEDATA">MOBILEDATA</option>
            <option value="CABLEBILLS">CABLEBILLS</option>
            <option value="UTILITYBILLS">UTILITYBILLS</option>
            <option value="OTHERS">OTHERS</option>
            <option value="DEALPAY">DEALPAY</option>
            <option value="INTSERVICE">INTSERVICE</option>
        </select>
    </div>
    <div>
        <label for="country">Country Code:</label>
        <input type="text" id="country" name="country" placeholder="e.g. NG" value="NG">
    </div>
    <button type="submit">Get Billers</button>
</form>

<h2>Get Biller Items</h2>
<form method="get" action="{{route('bills.billerItems')}}">
    @csrf
    <div>
        <label for="biller_id">Biller ID:</label>
        <input type="text" id="biller_id" name="biller_id" placeholder="e.g. BIL119" value="BIL119">
    </div>
    <button type="submit">Get Items</button>
</form>
</body>
</html>
