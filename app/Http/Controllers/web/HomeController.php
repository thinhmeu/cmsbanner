<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use App\Helpers\Binance;
use Binance\API;

class HomeController extends WebController
{
    public function index()
    {
        $binance = new API(env("BINANCE_API_KEY"), env("BINANCE_API_SECRET"), env("BINANCE_TESTNET"));
        $b = new Binance($binance, "AXSUSDT", 10, 7.5, 8.4);
        $b->auto();
    }
}
