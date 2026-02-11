<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ApiController extends Controller
{
    public function api(): View
    {
        $plan = myPlan();
        $features = $plan['features'];
        $apiStatus = $features['api'] ? true : false;

        return view('user.api.index', [
            'functions' => 'user.api.function',
            'sidebar' => 'api',
            'pageName' => pageName(['API']),
            'apiStatus' => $apiStatus,
        ]);
    }

}
