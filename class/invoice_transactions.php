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

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * Class for Blue Room Xcenter
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class XpaymentInvoice_transactions extends XoopsObject
{
    public function __construct($id = null)
    {
        $this->initVar('tiid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('iid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('transactionid', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('invoice', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('custom', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('status', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gross', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('fee', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('deposit', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('settle', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('exchangerate', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('firstname', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('lastname', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('street', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('city', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('state', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('postcode', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('country', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('address_status', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('payer_email', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('payer_status', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('gateway', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('plugin', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('mode', XOBJ_DTYPE_ENUM, 'PAYMENT', false, false, false, ['PAYMENT', 'REFUND', 'PENDING', 'NOTICE', 'OTHER']);
    }

    public function toArray()
    {
        $ret                  = parent::toArray();
        $ret['date_datetime'] = date(_DATESTRING, $this->getVar('date'));

        return $ret;
    }

    public function runPlugin()
    {
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
        $invoice        = $invoiceHandler->get($this->getVar('iid'));

        require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/' . $invoice->getVar('plugin') . '.php');

        switch ($invoice->getVar('mode')) {
            case 'PAID':
            case 'CANCEL':
            case 'UNPAID':
                switch ($this->getVar('mode')) {
                    case 'PAYMENT':
                    case 'REFUND':
                    case 'PENDING':
                    case 'NOTICE':
                    case 'OTHER':
                        $func = ucfirst($this->getVar('mode')) . ucfirst($invoice->getVar('mode')) . ucfirst($invoice->getVar('plugin')) . 'TransactionHook';
                        break;
                    default:
                        return false;
                        break;
                }
                // no break
            default:
                return false;
                break;
        }

        if (function_exists($func)) {
            @$func($this, $invoice);
        }

        return true;
    }
}

/**
 * XOOPS policies handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 *
 * @author  Simon Roberts <simon@chronolabs.coop>
 * @package kernel
 */
class XpaymentInvoice_transactionsHandler extends XoopsPersistableObjectHandler
{
    public function __construct(XoopsDatabase $db)
    {
        $this->db = $db;
        parent::__construct($db, 'xpayment_invoice_transactions', 'XpaymentInvoice_transactions', 'tiid', 'transactionid');
    }

    public function getFeePercentile($gateway, $grand)
    {
        $sql    = 'SELECT SUM(`fee`)/SUM(`gross`)*100 AS feepercentile FROM ' . $GLOBALS['xoopsDB']->prefix('xpayment_invoice_transactions') . " WHERE `gateway` = '" . $gateway . "' AND `gross` <= '" . $grand . "'";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        list($feepercentile) = $GLOBALS['xoopsDB']->fetchRow($result);

        return (0 <> $feepercentile ? $feepercentile : false);
    }

    public function getDepositPercentile($gateway, $grand)
    {
        $sql    = 'SELECT SUM(`deposit`)/SUM(`gross`)*100 AS depositpercentile FROM ' . $GLOBALS['xoopsDB']->prefix('xpayment_invoice_transactions') . " WHERE `gateway` = '" . $gateway . "' AND `gross` <= '" . $grand . "'";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        list($depositpercentile) = $GLOBALS['xoopsDB']->fetchRow($result);

        return (0 <> $depositpercentile ? $depositpercentile : false);
    }

    public function sumOfGross($iid = 0)
    {
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
        $invoice        = $invoiceHandler->get($iid);

        $sql    = 'SELECT sum(`gross`) AS gross FROM ' . $GLOBALS['xoopsDB']->prefix('xpayment_invoice_transactions') . ' WHERE `iid` = ' . $iid . ' AND `date` > ' . $invoice->getVar('previous') . ' AND `mode` IN ("PAYMENT", "REFUND")';
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        list($gross) = $GLOBALS['xoopsDB']->fetchRow($result);

        $invoice->setVar('paid', $gross);
        $invoiceHandler->insert($invoice);

        return (0 <> $gross ? $gross : false);
    }

    public function countTransactionId($transactionid)
    {
        $criteria = new Criteria('transactionid', $transactionid);

        return $this->getCount($criteria);
    }

    public function insert(XoopsObject $obj, $force = true)
    {
        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
        $invoice        = $invoiceHandler->get($obj->getVar('iid'));

        $base = (float)abs($obj->getVar('gross')) / abs($invoice->getVar('grand'));

        $invoice->setVar('transactionid', $obj->getVar('transactionid'));

        if ($this->sumOfGross($obj->getVar('iid')) + $obj->getVar('gross') >= $invoice->getVar('grand')
            && 'UNPAID' === $invoice->getVar('mode')) {
            $invoice->setVar('mode', 'PAID');
        } elseif ($this->sumOfGross($obj->getVar('iid')) + $obj->getVar('gross') < $invoice->getVar('grand')
                  && 'PAID' === $invoice->getVar('mode')) {
            $invoice->setVar('mode', 'UNPAID');
        }

        if ('SETTLED' === $invoice->getVar('remittion') && 'UNPAID' === $invoice->getVar('mode')) {
            if ($this->sumOfGross($obj->getVar('iid')) + $obj->getVar('gross') >= $invoice->getVar('remittion_settled')) {
                $invoice->setVar('mode', 'PAID');
            }
        }

        if ($obj->getVar('gross') >= 0 && ('PAYMENT' === $obj->getVar('mode') || 'REFUND' === $obj->getVar('mode'))) {
            $obj->setVar('mode', 'PAYMENT');
        } elseif ($obj->getVar('gross') < 0
                  && ('PAYMENT' === $obj->getVar('mode')
                      || 'REFUND' === $obj->getVar('mode'))) {
            $obj->setVar('mode', 'REFUND');
        }

        switch ($obj->getVar('mode')) {
            case 'PAYMENT':
                if ($invoice->getVar('reoccurence') > 0) {
                    $invoice->setVar('occurence_grand', $invoice->getVar('occurence_grand') + ($invoice->getVar('grand') * $base));
                    $invoice->setVar('occurence_amount', $invoice->getVar('occurence_amount') + ($invoice->getVar('amount') * $base));
                    $invoice->setVar('occurence_handling', $invoice->getVar('occurence_handling') + ($invoice->getVar('handling') * $base));
                    $invoice->setVar('occurence_shipping', $invoice->getVar('occurence_shipping') + ($invoice->getVar('shipping') * $base));
                    $invoice->setVar('occurence_tax', $invoice->getVar('occurence_tax') + ($invoice->getVar('tax') * $base));
                    $invoice->setVar('occurence_weight', $invoice->getVar('occurence_weight') + ($invoice->getVar('weight') * $base));
                }
                break;
            case 'REFUND':
                if ($invoice->getVar('reoccurence') > 0) {
                    $invoice->setVar('occurence_grand', $invoice->getVar('occurence_grand') - ($invoice->getVar('grand') * $base));
                    $invoice->setVar('occurence_amount', $invoice->getVar('occurence_amount') - ($invoice->getVar('amount') * $base));
                    $invoice->setVar('occurence_handling', $invoice->getVar('occurence_handling') - ($invoice->getVar('handling') * $base));
                    $invoice->setVar('occurence_shipping', $invoice->getVar('occurence_shipping') - ($invoice->getVar('shipping') * $base));
                    $invoice->setVar('occurence_tax', $invoice->getVar('occurence_tax') - ($invoice->getVar('tax') * $base));
                    $invoice->setVar('occurence_weight', $invoice->getVar('occurence_weight') - ($invoice->getVar('weight') * $base));
                }
                break;
            case 'PENDING':
                break;
            case 'NOTICE':
                break;
            case 'OTHER':
                break;
        }

        if (true === $obj->vars['mode']['changed']) {
            $run_plugin = true;
        }
        $tiid = parent::insert($obj, $force);
        @$invoiceHandler->insert($invoice, true);
        if (true === $run_plugin) {
            $obj->runPlugin();
        }

        return $tiid;
    }
}
