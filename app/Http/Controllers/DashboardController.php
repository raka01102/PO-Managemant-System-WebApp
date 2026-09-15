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
                SUM(CASE WHEN payment_status = 'unpaid' THEN 1 ELSE 0 END) as unpaid,
                SUM(CASE WHEN delivery_status = 'draft' THEN 1 ELSE 0 END) as no_sj,
                SUM(CASE WHEN delivery_status = 'completed' THEN 1 ELSE 0 END) as delivered
            ")
            ->first()
            ->toArray();

        return view('dashboard', compact('recentOrders', 'stats'));
    }
}
