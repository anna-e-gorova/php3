<?php
class Shop
{
    private $strategy;
    private $order;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function payOrder() : void
    {
        $pay = $this->strategy->goToPay($order->sum,$order->phone);
        if ($pay) {
            echo("Succes");    
        } else {
            echo("Fail");
        }
    }
}

interface Strategy
{
    public function goToPay($sum, $phone) : array;
}

class Qiwi implements Strategy
{
    public function goToPay($sum, $phone) : array
    {
        $response = qiwi($sum, $phone);
        return $response;
    }
}

class Yandex implements Strategy
{
    public function goToPay($sum, $phone) : array
    {
        $response = yandex($sum, $phone);
        return $response;
    }
}

class WebMoney implements Strategy
{
    public function goToPay($sum, $phone) : array
    {
        $response = webMoney($sum, $phone);
        return $response;
    }
}


$shop = new Shop(new Qiwi);
$shop->payOrder();
$shop = new Shop(new Yandex);
$shop->payOrder();
$shop = new Shop(new WebMoney);
$shop->payOrder();