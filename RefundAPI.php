<?php
/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once("lib/config_paytm.php");

require_once("lib/PaytmChecksum.php");

$paytmParams = array();

$paytmParams["body"] = array(
    "mid"          => PAYTM_MERCHANT_MID,
    "txnType"      => "REFUND",
    "orderId"      => "266",
    "txnId"        => "20210601111212800110168902502670254",
    "refId"        => "REFUNDID_987876578",
    "refundAmount" => "400.00",
);

/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), PAYTM_MERCHANT_KEY);

$paytmParams["head"] = array(
    "signature"	  => $checksum
);

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
$url = "https://securegw-stage.paytm.in/refund/apply";

/* for Production */
// $url = "https://securegw.paytm.in/refund/apply";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
$response = curl_exec($ch);

$response=json_decode($response,true);
print_r($response['body']);