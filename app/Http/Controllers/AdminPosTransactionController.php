<?php

namespace App\Http\Controllers;

use App\Models\PosTransaction;
use Illuminate\Http\Request;

class AdminPosTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = PosTransaction::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('receipt_number', 'like', "%{$search}%");
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.pos_transactions.index', compact('orders'));
    }
}
