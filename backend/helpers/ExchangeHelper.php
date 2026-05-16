<?php

namespace backend\helpers;

class ExchangeHelper
{
    //const API_KEY = 'goldapi-bq8slytuo6j0-io';
    const API_KEY = 'goldapi-14sb4b19m8ngvkp2-io';
    public $start_date = null;
    public $end_date = null;

    public function __construct()
    {
    }

    public function setStartDate(string $startDate)
    {
        $this->start_date = $startDate;
    }

    public function setEndDate(string $endDate)
    {
        $this->end_date = $endDate;
    }
    
    public function getMetalPrice($symbol = 'XAU', $currency = 'USD')
    {
        $date = ($this->start_date != "") ? '/'.date('Ymd', strtotime($this->start_date)) : $this->start_date;

        $headers = array(
            'x-access-token: ' . self::API_KEY,
            'Content-Type: application/json'
        );

        $curl = curl_init();

        $url = "https://www.goldapi.io/api/{$symbol}/{$currency}{$date}";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return [];
        } else {
            return json_decode($response, true);
        }
    }
    
    public function getCurrencyRate($symbol = 'USD')
    {
        $url = "http://api.cba.am/exchangerates.asmx?op=ExchangeRatesLatest";
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
            <soap12:Body>
                <ExchangeRatesLatest xmlns="http://www.cba.am/" />
            </soap12:Body>
        </soap12:Envelope>';

        $headers = array(
            "Content-type: application/soap+xml",
            "Content-length: " . strlen($xml),
            "Connection: close",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $xmlString = curl_exec($ch);
        $xml = simplexml_load_string($xmlString);

        $xml->registerXPathNamespace('soap', 'http://www.w3.org/2003/05/soap-envelope');
        $xml->registerXPathNamespace('ns', 'http://www.cba.am/');

        $ns = $xml->xpath('//ns:ExchangeRate');
        $rates = [];

        foreach ($ns as $rate) {
            $iso = $rate->ISO->__toString();
            if ($iso === $symbol) {
                $rates['sell'] = round($rate->Rate->__toString(), 2);
            }
        }

        return $rates;
    }
}

?>
