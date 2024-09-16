<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    
    function DashboardPage():View{
        return view('pages.dashboard.dashboard-page');
    }
    function AdminDashboardPage():View{
        return view('pages.dashboard.admin-dashboard-page');
    }
}
