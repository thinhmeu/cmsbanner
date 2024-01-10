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
    protected $signature = 'autotrade {param}';

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
        $params = $this->argument("param");
        $params = explode(',', $params);
        if (count($params) != 5){
            dd("param truyền vào {action,symbol,invest,buyPrice,sellPrice}");
        }
        [$action, $symbol, $invest, $buyPrice, $sellPrice] = $params;
//        $binance = new API(env("BINANCE_API_KEY_TEST"), env("BINANCE_API_SECRET_TEST"), 1);
        $binance = new API(env("BINANCE_API_KEY"), env("BINANCE_API_SECRET"), env("BINANCE_TESTNET"));
        $a = new Binance($binance,strtoupper($symbol),$invest,$buyPrice,$sellPrice);

        try {
            switch ($action){
                case "auto":
                    $a->auto(); break;
                case "buy":
                    $a->buy(); break;
                case "sell":
                    $a->sell(); break;
            }
        } catch (\Exception $e){
            dd($e->getMessage());
        }
        return 0;
    }
}
