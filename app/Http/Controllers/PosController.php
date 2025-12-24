<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function printReceipt(Order $order)
    {
        if ($order->status !== 'completed') {
            abort(404);
        }

        return view('pos.receipt', compact('order'));
    }
}