<?php

namespace Lara\Http\Controllers;

use View;
use Lara\Log;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::query()->orderBy('created_at', 'desc')->paginate(15);
        return View::make('modellogs', compact('logs'));
    }
}
