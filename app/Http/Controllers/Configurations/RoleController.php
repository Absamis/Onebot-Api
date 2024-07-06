<?php

namespace App\Http\Controllers\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Configurations\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return Role::all();
    }
}
