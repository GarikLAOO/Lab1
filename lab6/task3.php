<?php

// --- DollarCalc.php ---
class DollarCalc 
{
    private $dollar;
    private $product;
    private $service;
    public $rate = 1;

    public function requestCalc($productNow, $serviceNow) 
    {
        $this->product = $productNow;
        $this->service = $serviceNow;
        $this->dollar = $this->product + $this->service;
        return $this->requestTotal();
    }

    public function requestTotal() 
    {
        $this->dollar *= $this->rate;
        return $this->dollar;
    }
}

// --- EuroCalc.php ---
class EuroCalc 
{
    private $euro;
    private $product;
    private $service;
    public $rate = 1;

    public function requestCalc($productNow, $serviceNow) 
    {
        $this->product = $productNow;
        $this->service = $serviceNow;
        $this->euro = $this->product + $this->service;
        return $this->requestTotal();
    }

    public function requestTotal() 
    {
        $this->euro *= $this->rate;
        return $this->euro;
    }
}

// --- ITarget.php ---
interface ITarget 
{
    function requester();
}

// --- EuroAdapter.php ---
class EuroAdapter extends EuroCalc implements ITarget 
{
    public function __construct() 
    {
        $this->requester();
    }

    function requester() 
    {
        $this->rate = .8111;
        return $this->rate;
    }
}

// --- Client.php ---
class Client 
{
    private $requestNow;
    private $dollarRequest;

    public function __construct() 
    {
        $this->requestNow = new EuroAdapter();
        $this->dollarRequest = new DollarCalc();

        $euro = "&#8364;";
        echo "Euros: $euro" . $this->makeAdapterRequest($this->requestNow) . "<br/>";

        echo "Dollars: $" . $this->makeDollarRequest($this->dollarRequest);
    }

    private function makeAdapterRequest(ITarget $req) 
    {
        return $req->requestCalc(40, 50);
    }

    private function makeDollarRequest(DollarCalc $req) 
    {
        return $req->requestCalc(40, 50);
    }
}

$worker = new Client();

?>