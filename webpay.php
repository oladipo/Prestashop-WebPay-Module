<?php

if (!defined('_PS_VERSION_'))
	exit;

class webpay extends PaymentModule
{
	private $_html = '';
	
	public function __construct()
	{
		$this->name = 'webpay';
		$this->tab = 'payments_gateways';
		$this->version = '1.0.0';
		$this->author = 'Synkron Solutions Nigeria Limited';
		$this->currencies = true;
		$this->currencies_mode = 'radio';
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
		
		parent::__construct();
		$this->displayName = $this->l('WebPay by Interswitch Nigeria');
		$this->description = $this->l('WebPay API Implementation. Accept Nigerian Debit cards on the interswitch network, mastercard, verve');
		
		if (!sizeof(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('No currency set for this module');
		
		$this->_errors = array();		
	}
	
	public function uninstall() 
	{
		return (parent::uninstall() AND
			Configuration::deleteByName('ISW_MERCHANT_ID') AND
			Configuration::deleteByName('ISW_LOGS') AND
			Configuration::deleteByName('ISW_MODE') AND
			Configuration::deleteByName('ISW_NO_SHIPPING')) AND
			Configuration::deleteByName('ISW_MERCHANT_APIUSERNAME') AND
			Configuration::deleteByName('ISW_MERCHANT_APIPASSWORD') AND
			Configuration::deleteByName('ISW_MERCHANT_PASSKEY');
	}
	
	public function install() 
	{
		if (!parent::install() OR !$this->registerHook('payment') OR
				!$this->registerHook('paymentReturn') OR
				!Configuration::updateValue('ISW_MERCHANT_ID', '') OR
				!Configuration::updateValue('ISW_LOGS', '1') OR
				!Configuration::updateValue('ISW_MODE', 'real') OR
				!Configuration::updateValue('ISW_NO_SHIPPING', '0') OR
				!Configuration::updateValue('ISW_MERCHANT_APIUSERNAME', '') OR
				!Configuration::updateValue('ISW_MERCHANT_APIPASSWORD', '') OR
				!Configuration::updateValue('ISW_MERCHANT_PASSKEY', '')
				)
				return false;
			return true;
		
		return true;
	}
	public function hookPayment($params)  
	{
		if (!$this->active)
				return;

			global $smarty;

			$smarty->assign('buttonText', $this->l('Pay with Interswitch WebPay'));
			return $this->display(__FILE__, 'payment.tpl');
	
	}
	
	public function hookPaymentReturn($params)   
	{
	
	}
	
	public function getContent()
	{
		$output = null;
		
		if(Tools::isSubmit('submit'))
		{
			//do some validation before update.....
			Configuration::updateValue('ISW_MERCHANT_ID', Tools::getValue('TXT_ISW_MERCHANT_ID'));
			Configuration::updateValue('ISW_MERCHANT_APIUSERNAME', Tools::getValue('TXT_MERCHANT_APIUSERNAME'));
			Configuration::updateValue('ISW_MERCHANT_APIPASSWORD', Tools::getValue('TXT_MERCHANT_APIPASSWORD'));
			Configuration::updateValue('ISW_MERCHANT_PASSKEY', Tools::getValue('TXT_MERCHANT_PASSKEY'));
			
			$output .= $this->displayConfirmation($this->l('Settings updated'));
		}
		$this->_displayForm();
		//return $this->_html;
		
		return $output.$this->_html;
	}
	
	public function preparePayment()
	{
		global $smarty, $cart, $cookie;
				
		$currency = $this->getCurrency((int)$cart->id_currency);

		if ($cart->id_currency != $currency->id)
		{
			$cart->id_currency = (int)$currency->id;
			$cookie->id_currency = (int)$cart->id_currency;
			$cart->update();
			Tools::redirect('modules/'.$this->name.'/payment.php');
		}
		
		$total = $cart->getOrderTotal();
	}
	
	public function _displayForm()
	{
		$this->_html .= '<h2>'.$this->displayName.'</h2>
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
				<p>
					<fieldset>
					<legend>
						<img src="'.__PS_BASE_URI__.'modules/webpay/logo.gif" />
					</legend>
				<div class="margin-form">
					<p><label>Merchant ID</label><input type="text" name="TXT_ISW_MERCHANT_ID" value="'.Configuration::get("ISW_MERCHANT_ID").'"/></p>
					<p><label>MICROZAHLEN API USERNAME</label><input type="text" name="TXT_MERCHANT_APIUSERNAME" value="'.Configuration::get("ISW_MERCHANT_APIUSERNAME").'"/></p>
					<p><label>MICROZAHLEN API PASSWORD</label><input type="text" name="TXT_MERCHANT_APIPASSWORD" value="'.Configuration::get("ISW_MERCHANT_APIPASSWORD").'"/></p>
					<p><label>MICROZAHLEN PASSKEY</label><input type="text" name="TXT_MERCHANT_PASSKEY" value="'.Configuration::get("ISW_MERCHANT_PASSKEY").'"/></p>
				</div>
				<input type="submit" name="submit" value="'.$this->l('Update').'" class="button" />
				</fieldset>
			</form>';
	}
	
	public function execPayment($cart){
		if(!$this->active)
			return;
		
		global $cookie, $smarty;
		
		$merchantId = Configuration::get("ISW_MERCHANT_ID");
		$apiUsername = Configuration::get("ISW_MERCHANT_APIUSERNAME");
		$apiPassword = Configuration::get("ISW_MERCHANT_APIPASSWORD");
		$merchantRef = $cart->id; /* Order ID*/
		
		$action = "http://".$apiUsername.":".$apiPassword."@staging.microzahlen.com/api/transactions/";
		
		$smarty->assign(array('nbProducts' => $cart->nbProducts(),'this_merchantRef' => $merchantRef,'this_action' => $action,'this_merchantID' => $merchantId,'this_amount' => $cart->getOrderTotal(true, Cart::BOTH),'this_path' => $this->path,
		'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED')? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'));
		
		return $this->display(__FILE__, 'payment_execution.tpl');
		
		
	}

	public function updateOrder($cart){
		global $cookie, $smarty;
		
		//check if order exists 
		$num = $cart->OrderExists();
		//find order with id $orderId
		if($num != 0){
			//update the order status....
			
		}
		
		//$cart->getCartByOrderId();
		//$order->getOrderByCartId();
	}
}
?>