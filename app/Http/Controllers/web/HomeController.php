<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use App\Helpers\Telegram;

class HomeController extends WebController
{
    public function telegram()
    {
        Telegram::sendMessage("Test");
    }
}
