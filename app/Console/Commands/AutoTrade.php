<?php

namespace App\Console\Commands;

use App\Helpers\Binance;
use Binance\API;
use Illuminate\Console\Command;

class AutoTrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autotrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $binance = new API(env("BINANCE_API_KEY_TEST"), env("BINANCE_API_SECRET_TEST"), 1);
        $binance = new API(env("BINANCE_API_KEY"), env("BINANCE_API_SECRET"), env("BINANCE_TESTNET"));
        $a = new Binance($binance,"BNBUSDT",10,290,310);
        try {
            $a->auto();
        } catch (\Exception $e){
            dd($e);
        }
        dd(123);
        return 0;
    }
}
