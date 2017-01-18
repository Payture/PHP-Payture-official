<?php
require 'payture/autoload.php';

// Example
$orderId = rand();
$amount = 1200;


PaytureConfiguration::setMerchantKey("MerchantSbis");

//ENV_PRODUCTION - для боевой среды
//ENV_DEVELOPMENT - для тестовой среды
PaytureConfiguration::setEnvironment(PaytureConfiguration::ENV_DEVELOPMENT);

echo "<h3>Test Payture InPay</h3>";

$initResult = PaytureInPay::Init([
    "SessionType" => "Pay",
    "OrderId" => $orderId,
    "Amount" => $amount,
    "IP" => $_SERVER["REMOTE_ADDR"]
]);

echo "Init result: ".print_r($initResult, true)."<br><br>";


if ($initResult->Success) {
    echo "Generated Link: ".PaytureInPay::generatePayLink()."<br><br>";
}
