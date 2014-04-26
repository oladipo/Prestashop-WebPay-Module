{capture name=path}{l s='Pay by Credit Card (Interswitch WebPay)' mod='webpay'}{/capture}

<h2>{l s='Order summary' mod='webpay'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.'}</p>
{else}
<h3>{l s='Pay by Credit Card (Interswitch WebPay)' mod='webpay'}</h3>

<form name="microzahlen_form" action="{$this_action}" method="post">
<p>
	<img src="logo.jpg" alt="{l s='Pay by Credit Card (Interswitch WebPay)' mod='webpay'}" width="150" height="49" style="float:left; margin: 0px 10px 5px 0px;" />
	{l s='You have chosen to pay with credit card.' mod='webpay'}
	<br/><br />
	{l s='Here is a short summary of your order:' mod='webpay'}
</p>
<p style="margin-top:20px;">
	- {l s='The total amount of your order is' mod='webpay'}
	<span id="amount" class="price">{displayPrice price=$this_amount}</span>
	{if $use_taxes == 1}
    	{l s='(tax incl.)' mod='webpay'}
    {/if}
</p>
<p>
	{l s='Credit Card payment process will start at next page' mod='webpay'}
	<br /><br />
	<b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='webpay'}.</b>
	<input type="hidden" ID="MerchantTxnRef" name="MerchantTxnRef" Value="{$this_merchantRef}"/>
	<input type="hidden" ID="MerchantRef" name="MerchantRef" Value="{$this_merchantID}"/>	
	<input type="hidden" ID="Amount" name="Amount" Value="{$this_amount}"/>
	<input type="hidden" ID="PassKey" name="PassKey" value="nopasskey"/>
</p>
<p class="cart_navigation">
	<a href="{$link->getPageLink('order.php', true)}?step=3" class="button_large hideOnSubmit">{l s='Other payment methods' mod='webpay'}</a>
	<input type="submit" name="submit" value="{l s='I confirm my order' mod='webpay'}" class="exclusive_large hideOnSubmit" />
</p>
</form>
{/if}