<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public static function createUniqueTransactionCode(){
        $UniqueTransactionCode = mt_rand(10000000, 99999999);
        while(Transaction::where('transaction_id', $UniqueTransactionCode)->first()){
            $UniqueTransactionCode = "WLC".now()->format('y')."-".mt_rand(100000, 999999);
        }

        return $UniqueTransactionCode;
    }
}
