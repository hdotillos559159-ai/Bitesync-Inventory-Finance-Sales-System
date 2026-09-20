<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * CEO/Admin dashboard.
     */
    public function admin(Request $request): View
    {
        return view('dashboard.admin', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Finance dashboard.
     */
    public function finance(Request $request): View
    {
        return view('dashboard.finance', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Procurement dashboard.
     */
    public function procurement(Request $request): View
    {
        return view('dashboard.procurement', [
            'user' => $request->user(),
        ]);
    }
}