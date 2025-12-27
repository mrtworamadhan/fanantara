<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function printInvoice(Order $order)
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
            'settings' => [
                'name' => 'KOPERASI FANANTARA',
                'address' => 'Jl. Fanantara No. 1, Jakarta',
                'phone' => '021-12345678',
                'email' => 'admin@fanantara.id',
            ]
        ]);

        return $pdf->stream('Invoice-' . $order->order_number . '.pdf');
    }

    public function printPurchaseOrder(Purchase $purchase)
    {
        $pdf = Pdf::loadView('pdf.purchase-order', [
            'purchase' => $purchase,
            'settings' => [
                'name' => 'KOPERASI FANANTARA',
                'address' => 'Jl. Fanantara No. 1, Jakarta',
                'phone' => '021-12345678',
                'email' => 'admin@fanantara.id',
            ]
        ]);

        return $pdf->stream('PO-' . $purchase->purchase_number . '.pdf');
    }

    public function printIdCard(Member $member)
    {
        return view('pdf.id-card', compact('member'));
    }
}