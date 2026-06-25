<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = \App\Models\User::where('is_admin', false)
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.customers.index', compact('customers'));
    }}
