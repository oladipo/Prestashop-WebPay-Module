<?php 

$apiEndpoint = "http://staging.microzahlen.com/api/merchants/transactions/";
$apiEndpoint = $apiEndpoint.$orderid;
	
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $apiEndpoint); 
curl_setopt($ch, CURLOPT_USERAGENT, "Useragent"); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json ; charset=UTF-8')); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, "oladipo:password"); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
//curl_setopt($ch, CURLOPT_GET, 1); 
curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 

$data = curl_exec($ch); 

$transaction = json_decode($data);

echo "amount :". $transaction->Amount;

print_r(json_decode($data));

?>