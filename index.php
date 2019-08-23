<?php
require 'payture/autoload.php';

// Example
$orderId = GenerateGuid();
$min = 1000;
$max = 9999;
$amount = rand( $min, $max );


PaytureConfiguration::setMerchantKey("Merchant");

//ENV_PRODUCTION - для боевой среды
//ENV_DEVELOPMENT - для тестовой среды
PaytureConfiguration::setEnvironment(PaytureConfiguration::ENV_DEVELOPMENT);

echo "<h3>Test Payture InPay</h3>";

$initResult = PaytureInPay::Init([
    "SessionType" => "Pay",
    "OrderId" => $orderId,
    "Amount" => $amount,
    "Product" => "Ticket",
    "Description" => "MyTestTransaction",
    "IP" => $_SERVER["REMOTE_ADDR"],
    "Url" => urlencode("https://payture.com/result?orderid={orderid}&result={success}")
]);

echo "Init result: ".print_r($initResult, true)."<br><br>";


if ($initResult->Success) {
    echo "Generated Link: ".PaytureInPay::generatePayLink()."<br><br>";
}


function GenerateGuid()
{
    if (function_exists('com_create_guid') === true) { return trim(com_create_guid(), '{}'); }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
