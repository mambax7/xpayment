<?php

/**
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
class TwocheckoutGatewaysPlugin
{
    public function __construct($invoice, $gateway)
    {
        if (is_a($gateway, 'XpaymentGateways')) {
            $this->_gateway = $gateway;
        }

        if (is_a($invoice, 'XpaymentInvoice')) {
            $this->_invoice = $invoice;
        }

        if (file_exists($GLOBALS['xoops']->path('modules/xpayment/language/' . $GLOBALS['xoopsConfig']['language'] . '/twocheckout.php'))) {
            require_once $GLOBALS['xoops']->path('modules/xpayment/language/' . $GLOBALS['xoopsConfig']['language'] . '/twocheckout.php');
        } else {
            require_once $GLOBALS['xoops']->path('modules/xpayment/language/english/twocheckout.php');
        }
    }

    public function getTransactionId($request)
    {
        if (!isset($request)) {
            $request = $_POST;
        }

        switch ($request['message_type']) {
            case 'ORDER_CREATED':
                return 'ORD' . date('Ym') . '-' . $request['invoice_id'];
            case 'FRAUD_STATUS_CHANGED':
                return 'FRD' . date('Ym') . '-' . $request['invoice_id'];
            case 'SHIP_STATUS_CHANGED':
                return 'SHP' . date('Ym') . '-' . $request['invoice_id'];
            case 'INVOICE_STATUS_CHANGED':
                return 'INV' . date('Ym') . '-' . $request['invoice_id'];
                break;
            case 'REFUND_ISSUED':
                return 'REF' . date('Ym') . '-' . $request['invoice_id'];
                break;
            case 'RECURRING_INSTALLMENT_SUCCESS':
            case 'RECURRING_INSTALLMENT_FAILED':
            case 'RECURRING_STOPPED':
            case 'RECURRING_COMPLETE':
            case 'RECURRING_RESTARTED':
                return 'REC' . date('Ym') . '-' . $request['invoice_id'];
                break;

        }
    }

    public function getEmail($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['customer_email'];
    }

    public function getInvoice($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return (int)$_REQUEST['vendor_order_id'];
    }

    public function getCustom($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['md5_hash'];
    }

    public function getStatus($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['invoice_status'];
    }

    public function getDate($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return strtotime($request['sale_date_placed']);
    }

    public function getTax($request)
    {
        return 0;
    }

    public function getType($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['fraud_status'];
    }

    public function getGross($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }
        if (!empty($request['invoice_list_amount'])) {
            return $request['invoice_list_amount'];
        } else {
            return $this->_invoice->getVar('grand');
        }
    }

    public function getFee($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $this->getGross($request) * ($this->_gateway->_options['fee'] / 100);
    }

    public function getDeposit($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $this->getGross($request) * ($this->_gateway->_options['deposit'] / 100);
    }

    public function getCurrency($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['list_currency'];
    }

    public function getSettle($request)
    {
        return $request['invoice_usd_amount'];
    }

    public function getExchangeRate($request)
    {
        return $request['invoice_list_amount'] / $request['invoice_usd_amount'];
    }

    public function getFirstname($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['customer_first_name'];
    }

    public function getLastname($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['customer_last_name'];
    }

    public function getStreet($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['bill_street_address'] . (!empty($request['bill_street_address2']) ? "\n" . $request['bill_street_address2'] : '');
    }

    public function getCity($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['bill_city'];
    }

    public function getState($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['bill_state'];
    }

    public function getPostcode($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['bill_postal_code'];
    }

    public function getCountry($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['bill_country'];
    }

    public function getAddressStatus($request)
    {
        return 'Billing Address';
    }

    public function getPayerEmail($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['customer_email'];
    }

    public function getPayerStatus($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return $request['fraud_status'];
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

        return [
            'iid'            => $this->_invoice->getVar('iid'),
            'transactionid'  => $this->getTransactionId($request),
            'email'          => $this->getEmail($request),
            'invoice'        => $this->getInvoice($request),
            'custom'         => $this->getCustom($request),
            'status'         => $this->getStatus($request),
            'date'           => $this->getDate($request),
            'gross'          => $this->getGross($request),
            'fee'            => $this->getFee($request),
            'deposit'        => $this->getDeposit($request),
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
        ];
    }

    public function checkHash($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return ($this->calcHash($request) == $request['md5_hash']);
    }

    // INBOUND FUNCTIONS
    public function goInvoiceObj()
    {
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');

        return $invoiceHandler->get(TwocheckoutGatewaysPlugin::getInvoice());
    }

    public function goActionCancel($request)
    {
        if (!$this->checkHash($request) && !empty($request['md5_hash'])) {
            return false;
        }

        $this->_invoice->setVar('mode', 'CANCELED');
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
        $invoiceHandler->insert($this->_invoice, true);
        $req = 'cmd=canceled';
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
        if ($this->goIPN($request)) {
            $req = 'cmd=payment';
            foreach ($this->getTransactionArray() as $key => $value) {
                $value = urlencode(stripslashes($value));
                $req   .= "&$key=$value";
            }
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $this->_invoice->getVar('return') . (strpos($this->_invoice->getVar('return'), '?') > 0 ? '&' : '?') . substr($req, 1, strlen($req) - 1));
            exit;
        }
    }

    public function goIPN($request)
    {
        if (!$this->checkHash($request) && !empty($request['md5_hash'])) {
            return false;
        }

        switch ($request['message_type']) {
            case 'INVOICE_STATUS_CHANGED':
                switch ($request['invoice_status']) {
                    case 'pending':
                        $this->_invoice->getVar('remittion', 'PENDING');
                        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
                        $invoiceHandler->insert($this->_invoice, true);
                        break;
                    case 'deposited':
                        $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                        $transaction                 = $invoice_transactionsHandler->create();
                        $transaction->setVars($this->getTransactionArray($request));
                        if ($invoice_transactionsHandler->countTransactionId($this->getTransactionId($request)) == 0) {
                            if ($tiid = $invoice_transactionsHandler->insert($transaction)) {
                                $gross = $invoice_transactionsHandler->sumOfGross($this->_invoice->getVar('iid'));

                                $this->_invoice->getVar('remittion', 'NONE');
                                $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
                                $invoiceHandler->insert($this->_invoice, true);

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
                }
                break;
            case 'REFUND_ISSUED':

                $request['invoice_list_amount'] = 0;
                for ($x = 1; $x < $request['item_count']; ++$x) {
                    if ($request['item_type_' . $x] == 'refund') {
                        $request['invoice_list_amount'] += ($request['item_list_amount_' . $x] * (!empty($request['item_quantity_' . $x]) ? $request['item_quantity_' . $x] : 1));
                    }
                }
                $request['invoice_list_amount'] = -$request['invoice_list_amount'];

                $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                $transaction                 = $invoice_transactionsHandler->create();
                $transaction->setVars($this->getTransactionArray($request));
                if ($invoice_transactionsHandler->countTransactionId($this->getTransactionId($request)) == 0) {
                    if ($tiid = $invoice_transactionsHandler->insert($transaction)) {
                        $gross = $invoice_transactionsHandler->sumOfGross($this->_invoice->getVar('iid'));
                        $req   = 'cmd=refund';
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
            case 'ORDER_CREATED':
            case 'FRAUD_STATUS_CHANGED':
            case 'SHIP_STATUS_CHANGED':
            case 'RECURRING_INSTALLMENT_SUCCESS':
                $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                $transaction                 = $invoice_transactionsHandler->create();
                $transaction->setVars($this->getTransactionArray($request));
                if ($invoice_transactionsHandler->countTransactionId($this->getTransactionId($request)) == 0) {
                    if ($tiid = $invoice_transactionsHandler->insert($transaction)) {
                        $gross = $invoice_transactionsHandler->sumOfGross($this->_invoice->getVar('iid'));
                        $req   = 'cmd=payment';
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
                // no break
            case 'RECURRING_INSTALLMENT_FAILED':
            case 'RECURRING_STOPPED':
            case 'RECURRING_COMPLETE':
            case 'RECURRING_RESTARTED':
                $request['invoice_list_amount'] = 0;
                $invoice_transactionsHandler    = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                $transaction                    = $invoice_transactionsHandler->create();
                $transaction->setVars($this->getTransactionArray($request));
                if ($invoice_transactionsHandler->countTransactionId($this->getTransactionId($request)) == 0) {
                    if ($tiid = $invoice_transactionsHandler->insert($transaction)) {
                        return true;
                    }
                }
                break;
        }

        return true;
    }

    public function calcHash($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return md5((!empty($request['sale_id']) ? $request['sale_id'] : $this->_gateway->_options['sale_id']) . (!empty($request['vendor_id']) ? $request['vendor_id'] : $this->_gateway->_options['vendor_id']) . $request['invoice_id'] . $this->_gateway->_options['secretword']);
    }

    // HTML GENERATION FUNCTIONS FOR PAYMENT FORM
    public function getPaymentHTML()
    {
        $grand = $this->_invoice->getVar('grand');

        $feepercentile = 0;
        if ($GLOBALS['xoopsModuleConfig']['feecomphensate']) {
            $invoice_transactionHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
            $feepercentile              = (($invoice_transactionHandler->getFeePercentile('ccbill', $grand) + $this->_gateway->_options['fee']) / 2) / 100;
            $html                       .= '<div>' . _XPY_MF_FEE . number_format($grand * $feepercentile, 2) . ' ' . $this->_invoice->getVar('currency') . '</div>';
            $grand                      = $grand + ($grand * $feepercentile);
        }

        $depositpercentile = 0;
        if ($GLOBALS['xoopsModuleConfig']['depositcomphensate']) {
            $invoice_transactionHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
            $depositpercentile          = (($invoice_transactionHandler->getDepositPercentile('ccbill', $grand) + $this->_gateway->_options['deposit']) / 2) / 100;
            $html                       .= '<div>' . _XPY_MF_DEPOSIT . number_format($grand * $depositpercentile, 2) . ' ' . $this->_invoice->getVar('currency') . '</div>';
            $grand                      = $grand + ($grand * $depositpercentile);
        }

        $html .= '<div>' . _XPY_MF_TOTAL . number_format($grand, 2) . ' ' . $this->_invoice->getVar('currency') . '</div><b>';

        $html .= '<form action="' . $this->_gateway->_options['url'] . '"  name="gateway" id="gateway" method="post">';

        $invoice_itemsHandler = xoops_getModuleHandler('invoice_items', 'xpayment');
        $items                = $invoice_itemsHandler->getObjects(new Criteria('iid', $this->_invoice->getVar('iid')));

        foreach ($items as $iiid => $item) {
            $x    = $iiid + 1;
            $html .= '<input type="hidden" name="c_prod_' . $x . '" value="' . $item->getVar('cat') . ',' . $item->getVar('quantity') . '">';
            $html .= '<input type="hidden" name="c_name_' . $x . '" value="' . $item->getVar('name') . '">';
            $html .= '<input type="hidden" name="c_price_' . $x . '" value="' . (($item->getVar('amount') + $item->getVar('shipping') + $item->getVar('handling')) + (($item->getVar('amount') + $item->getVar('shipping') + $item->getVar('handling')) * $feepercentile) + (($item->getVar('amount')
                                                                                                                                                                                                                                                                              + $item->getVar('shipping')
                                                                                                                                                                                                                                                                              + $item->getVar('handling')
                                                                                                                                                                                                                                                                              + (($item->getVar('amount')
                                                                                                                                                                                                                                                                                  + $item->getVar('shipping')
                                                                                                                                                                                                                                                                                  + $item->getVar('handling'))
                                                                                                                                                                                                                                                                                 * $feepercentile))
                                                                                                                                                                                                                                                                             * $depositpercentile)) . '">';
            $html .= '<input type="hidden" name="c_description_' . $x . '" value="' . $item->getVar('quantity') . ' x ' . $item->getVar('cat') . ' - ' . $item->getVar('name') . '">';
        }

        $html .= '<input type="hidden" name="currency" value="' . $this->_invoice->getVar('currency') . '">';
        $html .= '<input type="hidden" name="merchant_order_id" value="' . $this->_invoice->getVar('iid') . '">';
        $html .= '<input type="hidden" name="cart_order_id" value="' . $this->_invoice->getVar('iid') . '">';
        $html .= '<input type="hidden" name="sid" value="' . $this->_gateway->_options['vendor_id'] . '">';
        $html .= '<input type="hidden" name="id_type" value="' . $this->_gateway->_options['id_type'] . '">';
        $html .= '<input type="hidden" name="total" value="' . $grand . '">';
        $html .= '<input type="hidden" name="x_Receipt_Link_URL" value="' . XOOPS_URL . '/modules/xpayment/return.php">';
        $html .= '<input type="hidden" name="x_login" value="' . $this->_gateway->_options['vendor_id'] . '">';
        $html .= '<input type="hidden" name="x_amount" value="' . $this->_invoice->getVar('grand') . '">';
        $html .= '<input type="hidden" name="x_invoice_num" value="' . $this->_invoice->getVar('iid') . '">';
        $html .= '<input type="submit" value="' . $this->_gateway->_options['paywith'] . '">';
        $html .= '</form>';

        return $html;
    }
}
