<?php

namespace App\Http\Controllers\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Configurations\AccountOption;
use Illuminate\Http\Request;

class AccountOptionController extends Controller
{
    public function index()
    {
        return AccountOption::all();
    }
}
