<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/../../header.php');
include('webpay.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');

	
	$webpay = new webpay();
	/*$order = new Order($webpay->currentOrder);
	
	$total = (float)($cart->getOrderTotal(true, Cart::BOTH));
	$currency = new Currency(Tools::getValue('currency_payement', false) ? Tools::getValue('currency_payement') : $cookie->id_currency);
	$customer = new Customer((int)$cart->id_customer);
	
	$webpay->validateOrder($cart->id, Configuration::get('PS_OS_PREPARATION'), $total, $webpay->displayName, 
		NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
	
	*/
	echo $webpay->execPayment($cart);
	
include_once(dirname(__FILE__).'/../../footer.php');

?>