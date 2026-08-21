<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(PurchaseOrder $purchaseOrder)
    {
        // $stats = [
        //     'total'  => PurchaseOrder::count(),
        //     'unpaid' => PurchaseOrder::where('is_paid', false)->count(),
        //     // Contoh PO tanpa Surat Jalan (asumsi ada relasi 'attachments')
        //     // 'no_sj'  => PurchaseOrder::whereDoesntHave('attachments', function($q) {
        //     //                 $q->where('status', 'draft');
        //     //             })->count(),
        //     'no_sj'  => PurchaseOrder::where('status', 'draft')->count(),
        //     'delivered' => PurchaseOrder::where('status', 'delivered')->count(),
        // ];

        $recentOrders = PurchaseOrder::with(['customer', 'logs'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('recentOrders'));
    }
}
