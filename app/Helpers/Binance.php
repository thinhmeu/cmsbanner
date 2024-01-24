<?php
namespace App\Helpers;
use App\Helpers\Telegram;
use Binance\API;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class Binance{
    protected $api, $symbol, $invest, $buyPrice, $sellPrice;
    protected $priceNow, $baseAsset, $quoteAsset, $orderId, $orderFilled;
    protected $timeInterval = 5;
    public function __construct(API $binance, $symbol, $invest, $buyPrice, $sellPrice){
        $this->api = $binance;
        $this->symbol = $symbol;
        $this->invest = $invest;
        $this->buyPrice = $buyPrice;
        $this->sellPrice = $sellPrice;

        $info = $this->exchangeInfo()[$this->symbol];
        if ($info['isSpotTradingAllowed'] == false){
            throw new \Exception("Token $this->symbol không mở giao dịch SPOT");
        }
        $this->baseAsset = $info['baseAsset'];
        $this->quoteAsset = $info['quoteAsset'];
    }
    public function auto(){
        $this->buy();
        $this->sell();
    }
    public function buy(){
        $this->findTheBestBuyPrice();
        $this->buyNow();
        $this->checkOrder();
    }
    public function sell(){
        $this->findTheBestSellPrice();
        $this->sellNow();
        $this->checkOrder();
    }

    private function findTheBestBuyPrice(){
        $min = $this->buyPrice;
        Telegram::sendMessage("Đang tìm giá mua $this->baseAsset <= $this->buyPrice");
        do{
            $this->priceNow = $this->api->price($this->symbol);
            if ($this->priceNow < $this->buyPrice){
                if ($this->priceNow <= $min){
                    $min = $this->priceNow;
                    sleep($this->timeInterval);
                } else {
                    Telegram::sendMessage("Tìm được giá phù hợp: ".rtrim($this->priceNow, '0'));
                    break;
                }
            } else {
                sleep($this->timeInterval);
            }
        } while (1);
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
    private function buyNow(){
        $quantity = $this->getQuantityToBuy();

        $order = $this->api->buy($this->symbol, $quantity, 0, "MARKET");
        Telegram::sendMessage("{$order['orderId']}: lên đơn mua $this->invest$ $this->baseAsset");
        $this->orderId = $order['orderId'];
    }

    private function checkOrder(){
        if (!$this->orderId)
            return;
        do{
            $order = $this->api->orderStatus($this->symbol, $this->orderId);
            if ($order['status'] !== 'FILLED'){
                sleep($this->timeInterval);
            } else {
                $check = ["BUY" => "Đã mua", "SELL" => "Đã bán"][$order['side']];
                Telegram::sendMessage("$this->orderId: $check ".rtrim($order['origQty'], '0')." {$this->baseAsset}");
                $this->orderId = false;
                break;
            }
        } while (1);
    }

    private function findTheBestSellPrice(){
        $max = $this->sellPrice;
        Telegram::sendMessage("Đang tìm giá bán $this->baseAsset >= $this->sellPrice");
        do{
            $this->priceNow = $this->api->price($this->symbol);
            if ($this->priceNow > $this->sellPrice){
                if ($this->priceNow >= $max){
                    $max = $this->priceNow;
                    sleep($this->timeInterval);
                } else {
                    Telegram::sendMessage("Tìm được giá bán $this->baseAsset đẹp $this->priceNow");
                    break;
                }
            } else {
                sleep($this->timeInterval);
            }
        } while (1);
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
    private function sellNow(){
        $quantity = $this->getQuantityToSell();
        $order = $this->api->sell($this->symbol, $quantity, 0, "MARKET");
        $this->orderId = $order['orderId'];
        Telegram::sendMessage("$this->orderId: Lên đơn bán $quantity $this->baseAsset");
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
