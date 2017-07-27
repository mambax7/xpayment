<?php

/*
 * Invoice Transaction Gateway with Modular Plugin set
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Co-Op http://www.chronolabs.coop/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         xpayment
 * @since           1.30.0
 * @author          Simon Roberts <simon@chronolabs.coop>
 * @translation     Erol Konik <aphex@aphexthemes.com>
 * @translation     Mariane <mariane_antoun@hotmail.com>
 * @translation     Voltan <voltan@xoops.ir>
 * @translation     Ezsky <ezskyyoung@gmail.com>
 * @translation     Richardo Costa <lusopoemas@gmail.com>
 * @translation     Kris_fr <kris@frxoops.org>
 */

class PaypalGatewaysPlugin
{
    public $_invoice = '';
    public $_gateway = '';

    public function __construct($invoice, $gateway)
    {
        if (is_a($gateway, 'XpaymentGateways')) {
            $this->_gateway = $gateway;
        }

        if (is_a($invoice, 'XpaymentInvoice')) {
            $this->_invoice = $invoice;
        }

        if (file_exists($GLOBALS['xoops']->path('modules/xpayment/language/' . $GLOBALS['xoopsConfig']['language'] . '/paypal.php'))) {
            require_once $GLOBALS['xoops']->path('modules/xpayment/language/' . $GLOBALS['xoopsConfig']['language'] . '/paypal.php');
        } else {
            require_once $GLOBALS['xoops']->path('modules/xpayment/language/english/paypal.php');
        }
    }

    public function getTransactionId($request)
    {
        if (!isset($request)) {
            $request = $_POST;
        }

        return $request['txn_id'];
    }

    public function getEmail($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['business'];
    }

    public function getInvoice($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }
        $invoice = explode('-', $_REQUEST['invoice']);

        return (int)$invoice[0];
    }

    public function getCustom($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['custom'];
    }

    public function getStatus($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['payment_status'];
    }

    public function getDate($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return strtotime($request['payment_date']);
    }

    public function getTax($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['tax'];
    }

    public function getType($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['txn_type'];
    }

    public function getGross($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['mc_gross'];
    }

    public function getFee($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['mc_fee'];
    }

    public function getCurrency($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['mc_currency'];
    }

    public function getSettle($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['settle_amount'];
    }

    public function getExchangeRate($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['exchange_rate'];
    }

    public function getFirstname($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['first_name'];
    }

    public function getLastname($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['last_name'];
    }

    public function getStreet($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['address_street'];
    }

    public function getCity($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['address_city'];
    }

    public function getState($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['address_state'];
    }

    public function getPostcode($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['address_zip'];
    }

    public function getCountry($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['address_country'];
    }

    public function getAddressStatus($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['address_status'];
    }

    public function getPayerEmail($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['payer_email'];
    }

    public function getPayerStatus($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['payer_status'];
    }

    public function getGateway($request)
    {
        if (!is_object($this->_invoice)) {
            return false;
        }

        return $this->_invoice->getVar('gateway');
    }

    public function getPlugin($request)
    {
        if (!is_object($this->_invoice)) {
            return false;
        }

        return $this->_invoice->getVar('plugin');
    }

    public function getKey()
    {
        return substr($_REQUEST['custom'], 0, 32);
    }

    public function getTransactionArray($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return array(
            'iid'            => $this->_invoice->getVar('iid'),
            'transactionid'  => $this->getTransactionId($request),
            'email'          => $this->getEmail($request),
            'invoice'        => $this->getInvoice($request),
            'custom'         => $this->getCustom($request),
            'status'         => $this->getStatus($request),
            'date'           => $this->getDate($request),
            'gross'          => $this->getGross($request),
            'fee'            => $this->getFee($request),
            'settle'         => $this->getSettle($request),
            'exchangerate'   => $this->getExchangeRate($request),
            'firstname'      => $this->getFirstname($request),
            'lastname'       => $this->getLastname($request),
            'street'         => $this->getStreet($request),
            'city'           => $this->getCity($request),
            'state'          => $this->getState($request),
            'postcode'       => $this->getPostcode($request),
            'country'        => $this->getCountry($request),
            'address_status' => $this->getAddressStatus($request),
            'payer_email'    => $this->getPayerEmail($request),
            'payer_status'   => $this->getPayerStatus($request),
            'gateway'        => $this->_invoice->getVar('gateway'),
            'plugin'         => $this->_invoice->getVar('plugin')
        );
    }

    public function checkCustom($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return (sha1($this->_gateway->getVar('salt') . $this->_invoice->getVar('key') . $this->_invoice->getVar('iid') . $this->_invoice->getVar('grand') . XOOPS_LICENSE_KEY) == $request['custom']);
    }

    // INBOUND FUNCTIONS
    public function goInvoiceObj()
    {
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');

        return $invoiceHandler->get(PaypalGatewaysPlugin::getInvoice());
    }

    public function goActionCancel($request)
    {
        if (!$this->checkCustom($request)) {
            return false;
        }

        $this->_invoice->setVar('mode', 'CANCELED');
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
        $invoiceHandler->insert($this->_invoice, true);
        foreach ($this->getTransactionArray() as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req   .= "&$key=$value";
        }
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $this->_invoice->getVar('cancel') . (strpos($this->_invoice->getVar('cancel'), '?') > 0 ? '&' : '?') . substr($req, 1, strlen($req) - 1));
        exit;
    }

    public function goActionReturn($request)
    {
        if (!$this->checkCustom($request)) {
            return false;
        }

        $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
        $transaction                 = $invoice_transactionsHandler->create();
        $transaction->setVars($this->getTransactionArray($request));
        if ($invoice_transactionsHandler->countTransactionId($this->getTransactionId($request)) == 0) {
            if ($tiid = $invoice_transactionsHandler->insert($transaction)) {
                $gross = $invoice_transactionsHandler->sumOfGross($this->_invoice->getVar('iid'));

                foreach ($this->getTransactionArray() as $key => $value) {
                    $value = urlencode(stripslashes($value));
                    $req   .= "&$key=$value";
                }
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ' . $this->_invoice->getVar('return') . (strpos($this->_invoice->getVar('return'), '?') > 0 ? '&' : '?') . substr($req, 1, strlen($req) - 1));
                exit;
            }
        }
    }

    public function goIPN($request)
    {
        $ERR    = 0;
        $log    = '';
        $loglvl = $this->_gateway->_options['ipn_dbg_lvl'];
        define('_ERR', 1);
        define('_INF', 2);

        if (isset($_GET['dbg'])) {
            $dbg = 1;
        } else {
            $dbg = 0;
        }

        if ($dbg) {
            $this->dprt(_XPY_MF_DEBUGACTIVE, _INF);
            echo _XPY_MF_DEBUGHEADER;
            $receiver_email = $this->_gateway->_options['email'];
        }

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($request as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req   .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= 'Content-Length: ' . strlen($req) . "\r\n\r\n";
        $fp     = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);

        // For Debug Purposes ONLY
        //$fp = fsockopen('www.eliteweaver.co.uk', 80, $errno, $errstr, 30);

        $this->dprt(_XPY_MF_OPENCONN, _INF);

        if (!$fp) {
            // HTTP ERROR
            $this->dprt(_XPY_MF_CONNFAIL, _ERR);
            die('Failed to post back.');
        }

        $this->dprt('OK!', _INF);

        // assign posted variables to local variables
        $item_name        = $request['item_name'];
        $item_number      = $request['item_number'];
        $payment_status   = $request['payment_status'];
        $payment_amount   = $request['mc_gross'];
        $payment_currency = $request['mc_currency'];
        $txn_id           = $request['txn_id'];
        $txn_type         = $request['txn_type'];
        $receiver_email   = $request['receiver_email'];
        $payer_email      = $request['payer_email'];

        fwrite($fp, $header . $req);

        // Perform PayPal email account verification
        if (!$dbg && strcasecmp($request['business'], $this->_gateway->_options['email']) != 0) {
            $this->dprt(sprintf(_XPY_MF_RCVINVALID, $receiver_email), _ERR);
            $ERR = 1;
        }

        $insertSQL = '';
        // Look for duplicate txn_id's
        if ($txn_id) {
            $sql            = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xpayment_invoice_transactions') . " WHERE tranactionid = '$txn_id'";
            $Recordset1     = $GLOBALS['xoopsDB']->query($sql);
            $row_Recordset1 = $GLOBALS['xoopsDB']->fetchArray($Recordset1);
            $NumDups        = $GLOBALS['xoopsDB']->getRowsNum($Recordset1);
        }
        while (!$dbg && !$ERR && !feof($fp)) {
            $res = fgets($fp, 1024);
            if (strcmp($res, 'VERIFIED') == 0) {
                $this->dprt(_XPY_MF_VERIFIED, _INF);
                // Ok, PayPal has told us we have a valid IPN here

                // Check for a reversal for a refund
                if (strcmp($payment_status, 'Refunded') == 0) {
                    // Verify the reversal
                    $this->dprt(_XPY_MF_REFUND, _INF);
                    if (($NumDups == 0) || strcmp($row_Recordset1['payment_status'], 'Completed')
                        || (strcmp($row_Recordset1['txn_type'], 'web_accept') != 0
                            && strcmp($row_Recordset1['txn_type'], 'send_money') != 0)) {
                        // This is an error.  A reversal implies a pre-existing completed transaction
                        $this->dprt(_XPY_MF_TRANSMISSING, _ERR);
                        foreach ($request as $key => $val) {
                            $this->dprt("$key => $val", _ERR);
                        }
                        break;
                    }
                    if ($NumDups != 1) {
                        $this->dprt(_XPY_MF_MULTITXNS, _ERR);
                        foreach ($request as $key => $val) {
                            $this->dprt("$key => $val", _ERR);
                        }
                        break;
                    }

                    // We flip the sign of these amount so refunds can be handled correctly
                    $request['mc_gross']         = -$request['mc_gross'];
                    $request['mc_fee']           = -$request['mc_fee'];
                    $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                    $transaction                 = $invoice_transactionsHandler->create();
                    $transaction->setVars($this->getTransactionArray($request));
                    if ($invoice_transactionsHandler->countTransactionId($this->getTransactionId($request)) == 0) {
                        if ($tiid = $invoice_transactionsHandler->insert($transaction)) {
                            $req = 'cmd=refund';
                            foreach ($this->getTransactionArray() as $key => $value) {
                                $value = urlencode(stripslashes($value));
                                $req   .= "&$key=$value";
                            }
                            $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
                            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                            $header .= 'Content-Length: ' . strlen($req) - 1 . "\r\n\r\n";
                            $ipn    = fsockopen($this->_invoice->getVar('ipn'), 80, $errno, $errstr, 30);
                            fwrite($ipn, $header . substr($req, 0, strlen($req) - 1));
                            if ($ipn) {
                                fclose($ipn);
                            }
                        }
                    }

                    break;
                } else { // Look for anormal payment
                    if ((strcmp($payment_status, 'Completed') == 0)
                        && ((strcmp($txn_type, 'web_accept') == 0)
                            || (strcmp($txn_type, 'send_money') == 0))) {
                        $this->dprt('Normal transaction', _INF);
                        if ($lp) {
                            fwrite($lp, $payer_email . ' ' . $payment_status . ' ' . $request['payment_date'] . "\n");
                        }

                        // Check for a duplicate txn_id
                        if ($NumDups != 0) {
                            $this->dprt(_XPY_MF_DUPLICATETXN, _ERR);
                            foreach ($request as $key => $val) {
                                $this->dprt("$key => $val", _ERR);
                            }
                            break;
                        }

                        $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                        $transaction                 = $invoice_transactionsHandler->create();
                        $transaction->setVars($this->getTransactionArray($request));
                        if ($invoice_transactionsHandler->countTransactionId($this->getTransactionId($request)) == 0) {
                            if ($tiid = $invoice_transactionsHandler->insert($transaction)) {
                                $req = 'cmd=payment';
                                foreach ($this->getTransactionArray() as $key => $value) {
                                    $value = urlencode(stripslashes($value));
                                    $req   .= "&$key=$value";
                                }
                                $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
                                $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                                $header .= 'Content-Length: ' . strlen($req) - 1 . "\r\n\r\n";
                                $ipn    = fsockopen($this->_invoice->getVar('ipn'), 80, $errno, $errstr, 30);
                                fwrite($ipn, $header . substr($req, 0, strlen($req) - 1));
                                if ($ipn) {
                                    fclose($ipn);
                                }
                            }
                        }

                        break;
                    } else { // We're not interested in this transaction, so we're done
                        $this->dprt(_XPY_MF_NOTINTERESTED, _ERR);
                        foreach ($request as $key => $val) {
                            $this->dprt("$key => $val", _ERR);
                        }
                        break;
                    }
                }
            } elseif (strcmp($res, 'INVALID') == 0) {
                // log for manual investigation
                $this->dprt(_XPY_MF_INVALIDIPN, _ERR);
                foreach ($request as $key => $val) {
                    $this->dprt("$key => $val", _ERR);
                }
                break;
            }
        }

        if ($dbg) {
            $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xpayment_invoice_transactions') . ' LIMIT 10';
            echo 'Executing test query...';
            $Result1 = $xoopsDB->query($sql);
            if ($Result1) {
                echo _XPY_MF_DEBUGPASS . '<br>';
            } else {
                echo '<b>' . _XPY_MF_DEBUGFAIL . '</b><br>';
            }
            echo sprintf(_XPY_MF_RCVEMAIL, $this->_gateway->_options['email']);
        }

        fclose($fp);
        if ($lp) {
            fwrite($lp, "Exiting\n");
        }
        if ($lp) {
            fclose($lp);
        }

        if ($dbg) {
            echo '<hr>';
            echo _XPY_MF_IFNOERROR . '<br>';
        }
    }

    public function dprt($str, $clvl)
    {
        global $dbg, $xoopsDB, $lp, $log, $loglvl;

        if ($lp) {
            fwrite($lp, $str . "\n");
        }
        if ($dbg) {
            echo $str . '<br>';
        }
        if ($clvl <= $loglvl) {
            $log .= $str . "\n";
        }
    }

    public function calcCustom()
    {
        return sha1($this->_gateway->getVar('salt') . $this->_invoice->getVar('key') . $this->_invoice->getVar('iid') . $this->_invoice->getVar('grand') . XOOPS_LICENSE_KEY);
    }

    // HTML GENERATION FUNCTIONS FOR PAYMENT FORM
    public function getPaymentHTML()
    {
        if ($GLOBALS['xoopsModuleConfig']['feecomphensate']) {
            $invoice_transactionHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
            $feepercentile              = $invoice_transactionHandler->getFeePercentile('paypal', $this->_invoice->getVar('grand')) / 100;
            $html                       .= '<div>' . _XPY_MF_FEE . number_format($this->_invoice->getVar('grand') * $feepercentile, 2) . ' ' . $this->_invoice->getVar('currency') . '</div>';
            $handling                   = ($this->_invoice->getVar('grand') * $feepercentile);
        }

        $html .= '<div>' . _XPY_MF_TOTAL . number_format($handling + $this->_invoice->getVar('grand'), 2) . ' ' . $this->_invoice->getVar('currency') . '</div><b>';

        if ($this->_gateway->getVar('testmode') === true) {
            $html .= '<form action="' . $this->_gateway->_options['sandbox'] . '" name="gateway" id="gateway" method="post">';
        } else {
            $html .= '<form action="' . $this->_gateway->_options['url'] . '" name="gateway" id="gateway" method="post">';
        }

        $html                 .= '<input type="hidden" name="cmd" value="_xclick">';
        $html                 .= '<input type="hidden" name="no_note" value="1">';
        $html                 .= '<input type="hidden" name="return" value="' . XOOPS_URL . '/modules/xpayment/return.php">';
        $html                 .= '<input type="hidden" name="cancel_url" value="' . XOOPS_URL . '/modules/xpayment/cancel.php">';
        $html                 .= '<input type="hidden" name="notify_url" value="' . XOOPS_URL . '/modules/xpayment/ipn.php">';
        $html                 .= '<input type="hidden" name="image_url" value="' . $this->_gateway->_options['image'] . '">';
        $html                 .= '<input type="hidden" name="currency_code" value="' . $this->_invoice->getVar('currency') . '">';
        $html                 .= '<input type="hidden" name="custom" value="' . $this->calcCustom() . '">';
        $html                 .= '<input type="hidden" name="invoice" value="' . $this->_invoice->getVar('iid') . '-' . substr(md5(XOOPS_LICENSE_KEY . date('Ymdhmi')), mt_rand(0, 28), 3) . '">';
        $html                 .= '<input type="hidden" name="business" value="' . $this->_gateway->_options['email'] . '">';
        $html                 .= '<input type="hidden" name="weight_unit" value="' . $this->_invoice->getVar('weight_unit') . '">';
        $html                 .= '<input type="hidden" name="weight" value="' . $this->_invoice->getVar('weight') . '">';
        $html                 .= '<input type="hidden" name="item_number" value="' . strtoupper($this->_invoice->getVar('items') . substr(md5($this->_invoice->getVar('iid')), 0, 2) . substr(md5($this->_invoice->getVar('iid')), 29, 2)) . '">';
        $invoice_itemsHandler = xoops_getModuleHandler('invoice_items', 'xpayment');
        if ($invoice_itemsHandler->getCount(new Criteria('iid', $this->_invoice->getVar('iid'))) == 1) {
            $items = $invoice_itemsHandler->getObjects(new Criteria('iid', $this->_invoice->getVar('iid')), false);
            if (is_object($items[0])) {
                $html .= '<input type="hidden" name="item_name" value="' . $items[0]->getVar('cat') . ' : ' . $items[0]->getVar('name') . '">';
            } else {
                $html .= '<b>' . _XPY_MF_ITEMNAME . '<input type="text" size="25" maxlen="128" name="item_name" value="' . $this->_invoice->getVar('items') . _XPY_PAYPAL_LINEITEMSDRAWNBY . $this->_invoice->getVar('drawfor') . '"><b>';
            }
        } else {
            $html .= '<b>' . _XPY_MF_ITEMNAME . '<input type="text" size="25" maxlen="128" name="item_name" value="' . $this->_invoice->getVar('items') . _XPY_PAYPAL_LINEITEMSDRAWNBY . $this->_invoice->getVar('drawfor') . '"><b>';
        }
        $html .= '<input type="hidden" name="amount" value="' . number_format($this->_invoice->getVar('amount'), 2) . '">';
        $html .= '<input type="hidden" name="shipping" value="' . number_format($this->_invoice->getVar('shipping'), 2) . '">';
        $html .= '<input type="hidden" name="handling" value="' . number_format($this->_invoice->getVar('handling') + $handling, 2) . '">';
        $html .= '<input type="hidden" name="tax" value="' . number_format($this->_invoice->getVar('tax'), 2) . '">';
        $html .= '<b><input type="submit" value="' . $this->_gateway->_options['paywith'] . '">';
        $html .= '</form>';

        return $html;
    }
}
