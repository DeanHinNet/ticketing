<?php
session_start();
require('new-connection.php');
require('vendor/autoload.php');
// define('PP_CONFIG_PATH', /path/to/your/sdk_config.ini)
// require __DIR__ . '/bootstrap.php';
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
$sdkConfig = array(
  "mode" => "sandbox"
);

$cred = new OAuthTokenCredential("AQaWvBDf74b9DHui9gfAEmcJnXgIqtENiSbHzY64XKIBe0HfJYyyXAR_C1Z6","EOTuwRA5yC8kt9ehNjmxEEMOWqtawNnsgIf158bI36acC_DklTRGo910LkBa", $sdkConfig);

// $apiContext = new ApiContext(new OAuthTokenCredential('<clientId>', '<clientSecret>'));
//     $payment = new Payment();
//     $payment->setIntent("Sale");
//     ...
//     $payment->create($apiContext);
//       *OR*
//     $payment = Payment::get('payment_id', $apiContext);

// var_dump($cred);

$apiContext = new ApiContext($cred, 'Request' . time());
$apiContext->setConfig($sdkConfig);

$payer = new Payer();
$payer->setPayment_method("paypal");

$amount = new Amount();
$amount->setCurrency("USD");
$amount->setTotal("12");

$transaction = new Transaction();
$transaction->setDescription("creating a payment");
$transaction->setAmount($amount);

$baseUrl = "http://localhost:8888/0FREELANCE-gray/ticketing/";
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturn_url("https://devtools-paypal.com/guide/pay_paypal/php?success=true");
$redirectUrls->setCancel_url("https://devtools-paypal.com/guide/pay_paypal/php?cancel=true");

$payment = new Payment();
$payment->setIntent("sale");
$payment->setPayer($payer);
$payment->setRedirect_urls($redirectUrls);
$payment->setTransactions(array($transaction));

$payment->create($apiContext);
?>