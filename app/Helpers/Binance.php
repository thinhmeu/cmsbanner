<?php
namespace App\Helpers;
use Binance\API;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class Binance{
    protected $api, $symbol, $invest, $buyPrice, $sellPrice, $tryToSell;
    protected $priceNow, $baseAsset, $quoteAsset, $orderId, $orderFilled;
    protected $timeInterval = 3;
    public function __construct(API $binance, $symbol, $invest, $buyPrice, $sellPrice){
        $this->api = $binance;
        $this->symbol = $symbol;
        $this->invest = $invest;
        $this->buyPrice = $buyPrice;
        $this->sellPrice = $sellPrice;
        $this->tryToSell = false;

        $info = $this->api->exchangeInfo()['symbols'][$this->symbol];
        if ($info['isSpotTradingAllowed'] == false){
            throw new \Exception("Token $this->symbol không mở giao dịch SPOT");
        }
        $this->baseAsset = $info['baseAsset'];
        $this->quoteAsset = $info['quoteAsset'];
    }
    public function auto(){
        $this->findTheBestBuyPrice();
        $this->buy();
        if ($this->orderId){
            $this->checkOrder();
        }
        if ($this->orderFilled){
            $this->findTheBestSellPrice();
            $this->sell();
        }
    }
    private function getQuantityToBuy(){
        $exchangeInfo = $this->exchangeInfo()[$this->symbol]['filters'][1];
        $stepSize = $exchangeInfo['stepSize'];
        $maxQuantity = $exchangeInfo['maxQty'];
        $minQuantity = $exchangeInfo['minQty'];
        $canBuy = floor($this->invest/$this->priceNow/$stepSize)*$stepSize;
        if ($minQuantity <= $canBuy && $canBuy <= $maxQuantity){
            return $canBuy;
        }
        $minInvest = $minQuantity*$this->priceNow;
        $maxInvest = $maxQuantity*$this->priceNow;
        throw new \Exception("Tiền đầu tư trong khoảng $minInvest$ -> $maxInvest$");
    }
    private function findTheBestBuyPrice(){
        $min = $this->buyPrice;
        $bought = false;
        do{
            $this->priceNow = $this->api->price($this->symbol);
            dump("Giá hiện tại $this->priceNow - Giá muốn mua $this->buyPrice");
            if ($this->priceNow < $this->buyPrice){
                if ($this->priceNow <= $min){
                    $min = $this->priceNow;
                    sleep($this->timeInterval);
                } else {
                    $bought = true;
                }
            } else {
                sleep($this->timeInterval);
            }
        } while (!$bought);
        return true;
    }
    private function buy(){
        try {
            $quantity = $this->getQuantityToBuy();

            $order = $this->api->buy($this->symbol, $quantity, 0, "MARKET");
            $this->orderId = $order['orderId'];
        } catch (\Exception $e){
            return null;
        }
    }
    private function checkOrder(){
        do{
            $order = $this->api->orderStatus($this->symbol, $this->orderId);
            if ($order['status'] !== 'FILLED'){
                sleep($this->timeInterval);
            } else {
                $this->orderFilled = true;
            }
        } while (!$this->orderFilled);
    }
    private function findTheBestSellPrice(){
        $max = $this->sellPrice;
        $sold = false;
        do{
            $this->priceNow = $this->api->price($this->symbol);
            dump("Giá hiện tại $this->priceNow - Giá muốn bán $this->buyPrice");
            if ($this->priceNow > $this->buyPrice){
                if ($this->priceNow >= $max){
                    $max = $this->priceNow;
                    sleep($this->timeInterval);
                } else {
                    $sold = true;
                }
            } else {
                sleep($this->timeInterval);
            }
        } while (!$sold);
        return true;
    }
    private function getQuantityToSell(){
        $totalBaseQuote = $this->api->balances()[$this->baseAsset]['available'];
        $exchangeInfo = $this->exchangeInfo()[$this->symbol]['filters'][1];
        $step = $exchangeInfo['stepSize'];
        $min = $exchangeInfo['minQty'];
        $max = $exchangeInfo['maxQty'];
        $quantity = floor($totalBaseQuote/$step)*$step;
        if ($min <= $quantity && $quantity <= $max){
            return $quantity;
        }
        throw new \Exception("Số lượng bán ra phải từ $min -> $max");
    }
    private function sell(){
        $quantity = $this->getQuantityToSell();
        $this->api->sell($this->symbol, $quantity, 0, "MARKET");
    }
    private function exchangeInfo(){
        $keyCache = __FUNCTION__;
        $data = Cache::get($keyCache);
        if (empty($data)){
            $data = $this->api->exchangeInfo()['symbols'];
            Cache::put($keyCache, $data, 86400);
        }
        return $data;
    }
}
