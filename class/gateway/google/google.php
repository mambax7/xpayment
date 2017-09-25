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

require_once __DIR__ . '/googlecart.php';
require_once __DIR__ . '/googleitem.php';
require_once __DIR__ . '/googleshipping.php';
require_once __DIR__ . '/googletax.php';

class GoogleGatewaysPlugin
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

    public function getSerialNumber($request)
    {
        if (!isset($request)) {
            $request = $_POST;
        }

        return $request['serial-number'];
    }

    public function getInvoice($request)
    {
        if (!isset($request)) {
            $request = $_POST;
        }

        return $request['cart-id'];
    }

    public function getTransactionArray($request)
    {
        if (!isset($request)) {
            $request = $_REQUEST;
        }

        return [];
    }

    // INBOUND FUNCTIONS
    public function goInvoiceObj()
    {
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');

        return $invoiceHandler->get(GoogleGatewaysPlugin::getInvoice($_REQUEST));
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
        if (0 == $invoice_transactionsHandler->countTransactionId($this->getTransactionId($request))) {
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
        if (true === $this->_gateway->getVar('testmode')) {
            $merchant_id  = $this->_gateway->_options['smerchantid'];  // Your Merchant ID
            $merchant_key = $this->_gateway->_options['smerchantkey'];  // Your Merchant Key
            $server_type  = 'sandbox';
            $currency     = $this->_invoice->getVar('currency');
        } else {
            $merchant_id  = $this->_gateway->_options['smerchantid'];  // Your Merchant ID
            $merchant_key = $this->_gateway->_options['smerchantkey'];  // Your Merchant Key
            $server_type  = 'checkout';
            $currency     = $this->_invoice->getVar('currency');
        }
        $certificate_path = $this->_gateway->_options['sslca']; // set your SSL CA cert path

        //Create the response object
        $Gresponse = new GoogleResponse($merchant_id, $merchant_key);

        //Setup the log file
        $Gresponse->SetLogFiles('', '', L_OFF);  //Change this to L_ON to log

        //Retrieve the XML sent in the HTTP POST request to the ResponseHandler
        $xml_response = file_get_contents('php://input');

        //If serial-number-notification pull serial number and request xml
        if (false === strpos($xml_response, 'xml')) {
            //Find serial-number ack notification
            $serial_array = [];
            parse_str($xml_response, $serial_array);
            $serial_number = $serial_array['serial-number'];

            //Request XML notification
            $Grequest      = new GoogleNotificationHistoryRequest($merchant_id, $merchant_key, $server_type);
            $raw_xml_array = $Grequest->SendNotificationHistoryRequest($serial_number);
            if (200 != $raw_xml_array[0]) {
                //Add code here to retry with exponential backoff
            } else {
                $raw_xml = $raw_xml_array[1];
            }
            $Gresponse->SendAck($serial_number, false);
        } else {
            //Else assume pre 2.5 XML notification
            //Check Basic Authentication
            $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);
            $status = $Gresponse->HttpAuthentication();
            if (!$status) {
                die('authentication failed');
            }
            $raw_xml = $xml_response;
            $Gresponse->SendAck(null, false);
        }

        if (get_magic_quotes_gpc()) {
            $raw_xml = stripslashes($raw_xml);
        }

        //Parse XML to array
        list($root, $data) = $Gresponse->getParsedXML($raw_xml);
        switch ($root) {
            case 'new-order-notification': {
                break;
            }
            case 'risk-information-notification': {
                break;
            }
            case 'charge-amount-notification': {
                break;
            }
            case 'authorization-amount-notification': {
                $google_order_number = $data[$root]['google-order-number']['VALUE'];
                $GChargeRequest      = new GoogleRequest($merchant_id, $merchant_key, $server_type);
                $GRequest->SetCertificatePath($certificate_path);
                $GChargeRequest->SendChargeAndShipOrder($google_order_number, $tracking_data);
                $this->_invoice->setVar('mode', 'PAID');
                $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
                $invoiceHandler->insert($this->_invoice, true);
                break;
            }
            case 'refund-amount-notification': {
                break;
            }
            case 'chargeback-amount-notification': {
                break;
            }
            case 'order-numbers': {
                break;
            }
            case 'invalid-order-numbers': {
                break;
            }
            case 'order-state-change-notification': {
                break;
            }
            default: {
                break;
            }
        }
    }

    /* In case the XML API contains multiple open tags
   with the same value, then invoke this function and
   perform a foreach on the resultant array.
   This takes care of cases when there is only one unique tag
   or multiple tags.
   Examples of this are "anonymous-address", "merchant-code-string"
   from the merchant-calculations-callback API
    */
    public function get_arr_result($child_node)
    {
        $result = [];
        if (isset($child_node)) {
            if (is_associative_array($child_node)) {
                $result[] = $child_node;
            } else {
                foreach ($child_node as $curr_node) {
                    $result[] = $curr_node;
                }
            }
        }

        return $result;
    }

    /* Returns true if a given variable represents an associative array */
    public function is_associative_array($var)
    {
        return is_array($var) && !is_numeric(implode('', array_keys($var)));
    }

    // HTML GENERATION FUNCTIONS FOR PAYMENT FORM
    public function getPaymentHTML()
    {
        $invoice_transactionHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');

        if (true === $this->_gateway->getVar('testmode')) {
            $merchant_id  = $this->_gateway->_options['smerchantid'];  // Your Merchant ID
            $merchant_key = $this->_gateway->_options['smerchantkey'];  // Your Merchant Key
            $server_type  = 'sandbox';
            $currency     = $this->_invoice->getVar('currency');
        } else {
            $merchant_id  = $this->_gateway->_options['smerchantid'];  // Your Merchant ID
            $merchant_key = $this->_gateway->_options['smerchantkey'];  // Your Merchant Key
            $server_type  = 'checkout';
            $currency     = $this->_invoice->getVar('currency');
        }
        // Create a new shopping cart object
        $cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);

        $invoice_itemsHandler = xoops_getModuleHandler('invoice_items', 'xpayment');
        if (1 == $invoice_itemsHandler->getCount(new Criteria('iid', $this->_invoice->getVar('iid')))) {
            $items = $invoice_itemsHandler->getObjects(new Criteria('iid', $this->_invoice->getVar('iid')), false);
            foreach ($items as $item) {
                ++$itm;
                $it[$itm] = new GoogleItem($item->getVar('name'), $item->getVar('description'), $item->getVar('quantity'), $item->getVar('amount') + $item->getVar('handling'));
                $it[$itm]->SetMerchantPrivateItemData(new MerchantPrivateItemData(['weight' => $item->getVar('weight')]));
                $it[$itm]->SetMerchantItemId($item->getVar('cat'));
            }
        }
        foreach ($it as $id => $item) {
            $cart->AddItem($it[$id]);
        }
        // Add shipping options
        $ship_1        = new GoogleFlatRateShipping('Ground', $this->_invoice->getVar('shipping'));
        $restriction_1 = new GoogleShippingFilters();
        $restriction_1->SetAllowedWorldArea(true);
        $ship_1->AddShippingRestrictions($restriction_1);

        $cart->AddShipping($ship_1);

        // Add alternate tax table
        $tax_table  = new GoogleAlternateTaxTable('food');
        $tax_rule_1 = new GoogleAlternateTaxRule($this->_invoice->getVar('tax') / $this->_invoice->getVar('grand'));
        $tax_table->AddAlternateTaxRules($tax_rule_1);
        $cart->AddAlternateTaxTables($tax_table);

        // Add <merchant-private-data>
        $cart->SetMerchantPrivateData(new MerchantPrivateData([
                                                                  'cart-id'       => $this->_invoice->getVar('iid'),
                                                                  'serial-number' => $this->_invoice->getVar('key')
                                                              ]));

        // Define rounding policy
        $cart->AddRoundingPolicy('CEILING', 'TOTAL');

        return $cart->CheckoutButtonCode('MEDIUM');
    }
}
