<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/../../header.php');
include('webpay.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
	
$orderid = $_REQUEST['ref'];
$errcode = $_REQUEST['errcode'];
$desc = $_REQUEST['desc'];

	$webpay = new webpay();
	$order = new Order($orderid);
	
	$apiUsername = Configuration::get("ISW_MERCHANT_APIUSERNAME");
	$apiPassword = Configuration::get("ISW_MERCHANT_APIPASSWORD");
		
	$total = (float)($cart->getOrderTotal(true, Cart::BOTH));
	$currency = new Currency(Tools::getValue('currency_payement', false) ? Tools::getValue('currency_payement') : $cookie->id_currency);
	$customer = new Customer((int)$cart->id_customer);

	//validate amount paid against order amount..
	//use curl to query transaction -> with customer ref, get amount paid
	$apiEndpoint = "http://staging.microzahlen.com/api/merchants/transactions/";
	$apiEndpoint = $apiEndpoint.$orderid;
		
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $apiEndpoint); 
	curl_setopt($ch, CURLOPT_USERAGENT, "Useragent"); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json ; charset=UTF-8')); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_USERPWD, "$apiUsername:$apiPassword"); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	//curl_setopt($ch, CURLOPT_GET, 1); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 

	$data = curl_exec($ch); 
	$transaction = json_decode($data);
	

	if($errcode == "00"){
		//if amount  and total are not same...
		if($transaction->Amount != $total){

			echo "<div>Error Occurred while processing your payment : Please contact the administrator </div>";
			$webpay->validateOrder($cart->id, Configuration::get('PS_OS_ERROR'), $total, $webpay->displayName, 
			"Payment Error: Suspected Tampering with Transaction value, Order Total and Amount Paid are different", $mailVars, (int)$currency->id, false, $customer->secure_key);

		}
		else
		{
			echo "<div>Payment was successful</div>";
			echo "<div>Your Order will be shipped shortly</div>";
		
			$webpay->validateOrder($cart->id, Configuration::get('PS_OS_PAYMENT'), $total, $webpay->displayName, 
			NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
		}
		
	}
	else
	{
		echo "<div>Error Occurred while processing your payment : $desc , please contact the administrator </div>";
		
		$webpay->validateOrder($cart->id, Configuration::get('PS_OS_ERROR'), $total, $webpay->displayName, 
			$desc, $mailVars, (int)$currency->id, false, $customer->secure_key);
	}

include_once(dirname(__FILE__).'/../../footer.php');

?>