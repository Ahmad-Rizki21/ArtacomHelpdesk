<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class SLAController extends Controller
{
    public function validateRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'response_time' => 'required|integer|min:0',
            'resolution_time' => 'required|integer|min:0',
        ]);
    }
}
