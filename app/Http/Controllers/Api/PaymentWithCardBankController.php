<?php

namespace App\Http\Controllers\Api;

use App\Models\PaymentWithCardBank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;


class PaymentWithCardBankController extends Controller
{
    public function index()
    {
        try {
            $PaymentWithCardBank = PaymentWithCardBank::first();
            $PaymentWithCardBank->card_payment = strip_tags($PaymentWithCardBank->card_payment);
            $PaymentWithCardBank->bank_payment = strip_tags($PaymentWithCardBank->bank_payment);
            return apiresponse(true, 'data found',$PaymentWithCardBank);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }
}
