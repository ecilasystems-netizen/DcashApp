<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function index()
    {

    }

    public function enterBankAccount(Request $request)
    {
        return view('app.exchange.enter-bank-account');
    }

    public function makePayment(Request $request)
    {
        return view('app.exchange.make-payment');
    }

    //transaction completed
    public function transactionCompleted(Request $request)
    {
        return view('app.exchange.transaction-completed');
    }

    //transaction receipt
    public function transactionReceipt(Request $request)
    {
        return view('app.exchange.transaction-receipt');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
