<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Lara\Http\Middleware\RejectGuests;

class SecondFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware(RejectGuests::class);
    }

    public function index()
    {
        return \View::make('secondfactor.index');
    }

    public function verify()
    {

    }
}
