<?php

use Tygh\Session;
use Tygh\Registry;
use Tygh\Http;

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }
if (defined('PAYMENT_NOTIFICATION')) {
    if ($mode == 'notify' && !empty($_REQUEST['order'])) {
	if ($_REQUEST['status'] == 'SALE')	{

		   $pp_response = array();
			$pp_response['order_status'] = 'P';
			$order_id = $_REQUEST['order'];
			fn_update_order_payment_info($order_id, $pp_response);
			fn_change_order_status($order_id, $pp_response['order_status']);
			fn_finish_payment($order_id, $pp_response);
			
		}
	}
} else
{
$first_name= $order_info['b_firstname'];
$last_name= $order_info['b_lastname'];
$phone= $order_info['phone'];	  
$email= $order_info['email'];
$shop_id = $processor_data['processor_params']['platoncharge_shop_id'];
$shop_pass = $processor_data['processor_params']['platoncharge_shop_pass'];
$amount = $order_info['total'];
$description = 'Оплата заказа #'.$order_id;

/* client's password */
$pass = $shop_pass;

$key            = $shop_id;			// Client's KEY
$url            = $success_url = "http://$_SERVER[SERVER_NAME]/index.php?dispatch=checkout.complete&order_id=";	// Return URL after success transaction
	
/* Prepare product data for coding */
$data           = base64_encode(json_encode(array('amount' => $amount,'name' => $description,'currency' => 'UAH')));

$payment = 'CC';
$lang = CART_LANGUAGE;
/* Calculation of signature */
$sign = md5(strtoupper( strrev($key).
                        strrev($payment).
			strrev($data).
			strrev($url).
			strrev($pass)
));

echo <<<EOT
<html>
<body>
<form action= "https://secure.platononline.com/payment/auth" method="post" name="process">
<input type="hidden" name="payment" value="{$payment}">
<input type="hidden" name="key" value="{$key}">
<input type="hidden" name="url" value="{$url}">

<input type="hidden" name="data" value="{$data}">
<input type="hidden" name="sign" value="{$sign}">
<input type="hidden" name="lang" value="{$lang}">
<input type="hidden" name="order" value="{$order_id}">
<input type="hidden" name="email" value="{$email}">
<input type="hidden" name="first_name" value="{$first_name}">
<input type="hidden" name="last_name" value="{$last_name}">
<input type="hidden" name="phone" value="{$phone}">
</form>                 
EOT;

$msg = __('text_cc_processor_connection', array(
    '[processor]' => 'Platon'
));

echo <<<EOT

    <p><div align=center>{$msg}</div></p>
    <script type="text/javascript">
    window.onload = function(){
        document.process.submit();
    };
    </script>
 </body>
</html>
EOT;
$_SESSION['cart'] = array(
                'user_data' => !empty($_SESSION['cart']['user_data']) ? $_SESSION['cart']['user_data'] : array(),
                'profile_id' => !empty($_SESSION['cart']['profile_id']) ? $_SESSION['cart']['profile_id'] : 0,
                'user_id' => !empty($_SESSION['cart']['user_id']) ? $_SESSION['cart']['user_id'] : 0,
            );
            $_SESSION['shipping_rates'] = array();
            unset($_SESSION['shipping_hash']);

            db_query('DELETE FROM ?:user_session_products WHERE session_id = ?s AND type = ?s', Session::getId(), 'C');

exit;		
}
