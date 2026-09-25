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
            ->take(5)
            ->get();

        $stats = PurchaseOrder::getStat();

        return view('dashboard', compact('recentOrders', 'stats'));
    }
}
