<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $recentOrders = PurchaseOrder::with(['customer', 'logs'])
            ->latest()
            ->take(6)
            ->get();

        $stats = PurchaseOrder::query()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN payment_status = 'paid' THEN 0 ELSE 1 END) as belum_lunas,
                SUM(CASE WHEN delivery_status = 'delivered' THEN 0 ELSE 1 END) as proses_pengiriman,
                SUM(CASE WHEN delivery_status = 'delivered' && payment_status = 'paid' THEN 1 ELSE 0 END) as completed
            ")
            ->first()
            ->toArray();

        return view('dashboard', compact('recentOrders', 'stats'));
    }
}
