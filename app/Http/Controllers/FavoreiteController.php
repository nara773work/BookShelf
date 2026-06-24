<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoreiteController extends Controller
{
    public function index(){
        return view('favorite.index');
    }
}
