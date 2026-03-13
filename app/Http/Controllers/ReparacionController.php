<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reparacion;

class ReparacionController extends Controller
{
    public function index(){
    return Reparacion::all();
}
}

