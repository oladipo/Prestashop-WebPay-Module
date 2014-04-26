Prestashop-WebPay-Module
========================

A payment module for prestashop for interswitch webpay. Setup a merchant account [at] http://www.merchant.microzahlen.com


Download the zip file and upload the folder to your /modules directory in your prestashop installation.

Go to the Admin panel, select modules tab, expand the payment and gateways section.

You will find "WebPay by Interswitch Nigeria v1.0.0 by Synkron Solutions Nigeria Limited".

Install and configure the module.

For API Password, Username and Merchant ID.

Create a merchant account at http://merchant.microzahlen.com/Account/Register

Sign in and update your merchant profile:

(1) set the Success and Failure URL to: http://yourshopurl/modules/webpay/orderstatus.php

(2) click the update button.

An email will be sent to you containing the following:

(a) API Username
(b) API Password
(c) Merchant ID
(d) Username and Password to login to the merchants portal.

update the webpay payment module configuration parameters with the above.

After all tests are completed, your merchant account will be migrated to the live/production environment.


