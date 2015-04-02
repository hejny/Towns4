<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2015
   _____________________________

   core/plus/paypal/preceive.php

   PayPal
*/
//==============================




/* 
  Copyright Injection Computers, Inc.
  
  PayPal receive (GETs from PayPal - roken, payerid; active (confirm the order or the order is finished))
  first the user confirms the order 
  then the order is processed
  
  the user can return back to where they clicked on the button "Checkout with PayPal"
*/

// important
require_once('paypal_pconfig.php'); 
require_once('paypal_plog.php');  
?>




<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Towns 4</title>
</head>

<style type="text/css">
/* button */
div.button
{
    width: 120px;
    height: 15px;
    background-color: #EFEFEF;
    margin: 0px 0px 0px 0px;
    padding: 2px 2px 2px 2px;
    text-align: center;
    vertical-align: middle;
    clear: none;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
    border-radius: 5px;
  	opacity: 0.9;
  	filter: alpha(opacity=90);
  	-moz-opacity: 0.9;
  	-khtml-opacity: 0.9; 
}
div.button:link
{
}
div.button:visited
{
}
div.button:hover
{
    background-color: #FEFEFE;
}
div.button:active
{
}
div.button:focus
{
}
</style>
<body>


<?php
// YOU do not have to define the following, just edit if necessary      
// receive from paypal 
$token = '';
$PayerID = '';
$active = FALSE;
if (isset($_GET['token']))
  $token = $_GET['token'];
if (isset($_GET['PayerID']))
  $PayerID = $_GET['PayerID'];
if (isset($_GET['active']))
  $active = (bool)$_GET['active'];
// custom
if ($token != '' && $PayerID != '')
{             
    // check the transaction and confirm
    $res = '';
    $nvp = "&TOKEN=" . $token;
    // info about transaction
    $info = prepareRequest('GetExpressCheckoutDetails', $nvp);  
    // decode
    foreach ($info as $k => $v)
    {
        $info[$k] = urldecode($v);
    }  
    if ($active == FALSE)
    {
        $res = $info;
    }
    else
    {
        if (!isset($info['PAYMENTREQUEST_0_AMT']))
        {
            // place for log? ---
            writeLog('FAIL: One order was NOT created.'); 
            echo '{paypal_fail}'/*'{paypal_fail']*/;
            return;
        }
        $nvp = "&TOKEN=" . $token . "&PAYERID=" . $PayerID . "&PAYMENTREQUEST_0_AMT=" . $info['PAYMENTREQUEST_0_AMT']. "&PAYMENTREQUEST_0_CURRENCYCODE=USD&PAYMENTREQUEST_0_PAYMENTACTION=Sale";
        $res = prepareRequest('DoExpressCheckoutPayment', $nvp);
     }
     // process
     if ($res && $res != '' && $info && $info != '')
     {
        if ($res['ACK'] == "Success") 
        {                        
            // order process
            $custom = preg_split('~[+]\s*~', $info['PAYMENTREQUEST_0_CUSTOM']);
            $line_id = (int)$custom[0];
            $line = getOffer($line_id);
            $user_id = (int)$custom[1]; 
            $user = getUser($user_id);                        
            // check line exists
            if ($line == NULL)
            {
                // place for log? ---
                writeLog('Hacker (line): the order was NOT created between user: ' . $user_id . ' and line: ' . $line_id);
                echo '{paypal_hack_line}'/*'{paypal_hack_line']*/;
                return;
            }
            // check user exists
            if ($user == NULL)
            {
                // place for log? ---
                writeLog('Hacker (user): the order was NOT created between user: ' . $user_id . ' and line: ' . $line_id);
                echo '{paypal_hack_user}'/*'{paypal_hack_user']*/;
                return;
            } 
            // check amount
            if ($info['PAYMENTREQUEST_0_AMT'] != $line['amount'])
            {
                // place for log? ---
                writeLog('Hacker (payment_gross): the order was NOT created between user: ' . $user_id . ' (' . $user['realname'] . ') and line: ' . $line_id);
                echo '{paypal_hack_amount}'/*'{paypal_hack_amount']*/;
                return;
            }
            // check currency
            if ($info['PAYMENTREQUEST_0_CURRENCYCODE'] != 'USD')
            {
                // place for log? ---
                writeLog('Hacker (mc_currency): the order was NOT created between user: ' . $user_id . ' (' . $user['realname'] . ') and line: ' . $line_id);
                echo '{paypal_hack_currency}'/*'{paypal_hack_currency']*/;
                return;
            }     
            
            // if one way, check if the offer is already purchased
                                        
            // data
            $data = array(
                "first_name" => $info['FIRSTNAME'],
                "last_name" => $info['LASTNAME'],
                "item_name" => $line['title'],
                "credit" => $line['credit'],
                "amount" => $line['amount'],
                "user" => $user['realname'],
            );
            if ($active == FALSE)
            {
                $checkpayment = TRUE;
            }   
            // new order
            else
            {                                      
                // log
                // place for log? ---
                writeLog('The order of ' . $line_id . ' was delivered to: ' . $user_id . ' (' . $user['realname'] . ')');
                
                // create the order NOW
                $credit = /*$user['credit'] +*/ $line['credit'];
                updateUser($user_id, $credit);                           
                echo '{paypal_purchased}'/*'{paypal_purchased']*/;
            }
         }
         // already purchased
         else if ($active != FALSE && $res['ACK'] == "Failure" && isset($res['L_ERRORCODE0']) && $res['L_ERRORCODE0'] == "10415") 
         {
            echo '{paypal_already_purchased}'/*'{paypal_already_purchased']*/;
            $alreadypurchased = TRUE;
         }
         else
         {
            // place for log? ---
            writeLog('FAIL: One order was NOT created.');
            echo '{paypal_fail}'/*'{paypal_fail']*/;
        }
    }
    else
    {
        // place for log? ---
        writeLog('NO RESULT: One order was NOT created.');
        echo '{paypal_noresult}'/*'{paypal_noresult']*/;
    }  
}

?>

<?php if(isset($data)) { ?>
    <div class="center">
        <?php if(isset($checkpayment)) { ?>
                <?php echo '{paypal_p_check}'; ?><br /><br />
                <?php echo '{paypal_p_payer}'; ?>: <b><?php echo $data['first_name']; ?> <?php echo $data['last_name']; ?></b><br />
                <?php echo '{paypal_the}'; ?>: <b><?php echo $data['item_name']; ?></b><br /> 
                <?php echo '{paypal_credit}'; ?>: <b><?php echo $data['credit']; ?></b><br />
                <?php echo '{paypal_p_user}'; ?>: <b><?php echo $data['user']; ?></b><br />
                <?php echo '{paypal_p_amount}'; ?>: <b>$<?php echo $data['amount']; ?></b><br /><br />

                <center><div class="button"><a href="<?php echo RECEIVEURL ?><?php echo "&amp;token=" . $token . "&PayerID=" . $PayerID . "&active=1"  ?>"><?php echo '{paypal_confirm}'; ?></a></div><br /></center>
        <?php } else { ?>
            <?php if(!isset($checkpayment)) { ?>
                <strong><?php echo '{paypal_thanks}'; ?></strong><br />
                <?php echo '{paypal_o_completed}'; ?> <?php echo $data['first_name'] ?> <?php echo $data['last_name'] ?>.<br />
                <?php echo '{paypal_the}'; ?> <b><?php echo $data['item_name'] ?></b> <?php echo '{paypal_o_assoc}'; ?> <b><?php echo $data['user'] ?></b><?php echo '{paypal_account}'; ?>.<br />
                <?php echo '{paypal_o_paid}'; ?>: <b>$<?php echo $data['amount'] ?></b><br /><br />

                <?php echo '{paypal_o_receipt}'; ?><br />
                <?php echo '{paypal_o_login1}'; ?> <a href='https://www.paypal.com'>www.paypal.com</a> <?php echo '{paypal_o_login2}'; ?><br /><br />

                <center><div class="button"><a href="<?php echo(OFFERSURL); ?>" onclick="window.close();">{paypal_close}</a></div><br /></center>
            <?php } ?>
        <?php } ?>
    </div>
<?php } else { ?>
    <center><div class="button"><a href="<?php echo(OFFERSURL); ?>" onclick="window.close();">{paypal_close}</a></div><br /></center>
<?php } ?>

</body>
</html>


