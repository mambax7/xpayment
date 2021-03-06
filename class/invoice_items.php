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
class XpaymentInvoice_items extends XoopsObject
{
    public function __construct($id = null)
    {
        $this->initVar('iiid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('iid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('amount', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('quantity', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('shipping', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('handling', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('weight', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('tax', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 5000);
        $this->initVar('mode', XOBJ_DTYPE_ENUM, 'PURCHASED', false, false, false, ['PURCHASED', 'REFUNDED', 'UNDELIVERED', 'DAMAGED', 'EXPRESS']);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
    }

    public function toArray($apply_discount = true)
    {
        $ret = parent::toArray();
        foreach ($this->getTotalsArray($apply_discount) as $field => $value) {
            if ('weight' === $field) {
                $ret['totals'][$field] = number_format($value, 4);
            } else {
                $ret['totals'][$field] = number_format($value, 2);
            }
        }
        $ret['created_datetime']  = date(_DATESTRING, $this->getVar('created'));
        $ret['updated_datetime']  = date(_DATESTRING, $this->getVar('updated'));
        $ret['actioned_datetime'] = date(_DATESTRING, $this->getVar('actioned'));

        return $ret;
    }

    public function getDiscountShipping($apply_discount = true)
    {
        static $invoice = null;
        if (!isset($invoice) || @$invoice->getVar('iid') != $this->getVar('iid')) {
            $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
            $invoice        = $invoiceHandler->get($this->getVar('iid'));
        }

        return (float)str_replace(',', '', number_format((false === $apply_discount ? 0 : ($this->getVar('shipping') * $this->getVar('quantity')) * ($invoice->getVar('discount') / 100)), 2));
    }

    public function getDiscountHandling($apply_discount = true)
    {
        static $invoice = null;
        if (!isset($invoice) || @$invoice->getVar('iid') != $this->getVar('iid')) {
            $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
            $invoice        = $invoiceHandler->get($this->getVar('iid'));
        }

        return (float)str_replace(',', '', number_format((false === $apply_discount ? 0 : ($this->getVar('handling') * $this->getVar('quantity')) * ($invoice->getVar('discount') / 100)), 2));
    }

    public function getDiscountAmount($apply_discount = true)
    {
        static $invoice = null;
        if (!isset($invoice) || @$invoice->getVar('iid') != $this->getVar('iid')) {
            $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
            $invoice        = $invoiceHandler->get($this->getVar('iid'));
        }

        return (float)str_replace(',', '', number_format((false === $apply_discount ? 0 : ($this->getVar('amount') * $this->getVar('quantity')) * ($invoice->getVar('discount') / 100)), 2));
    }

    public function getTotalShipping($apply_discount = true)
    {
        return (float)str_replace(',', '', number_format(($this->getVar('shipping') * $this->getVar('quantity')) - $this->getDiscountShipping($apply_discount), 2));
    }

    public function getTotalHandling($apply_discount = true)
    {
        return (float)str_replace(',', '', number_format(($this->getVar('handling') * $this->getVar('quantity')) - $this->getDiscountHandling($apply_discount), 2));
    }

    public function getTotalWeight()
    {
        return (float)str_replace(',', '', number_format($this->getVar('weight') * $this->getVar('quantity'), 4));
    }

    public function getTotalAmount($apply_discount = true)
    {
        return (float)str_replace(',', '', number_format(($this->getVar('amount') * $this->getVar('quantity')) - $this->getDiscountAmount($apply_discount), 2));
    }

    public function getTotalTax($apply_discount = true)
    {
        if ($this->getVar('tax') > 0) {
            return (float)str_replace(',', '', number_format(($this->getTotalAmount(true === $apply_discount
                                                                                    && true === $GLOBALS['xoopsModuleConfig']['discount_amount']) + $this->getTotalShipping(true === $apply_discount
                                                                                                                                                                            && true === $GLOBALS['xoopsModuleConfig']['discount_shipping']) + $this->getTotalHandling(true === $apply_discount
                                                                                                                                                                                                                                                                      && true
                                                                                                                                                                                                                                                                         === $GLOBALS['xoopsModuleConfig']['discount_handling'])) * ($this->getVar('tax')
                                                                                                                                                                                                                                                                                                                                     / 100), 2));
        }

        return 0;
    }

    public function getTotalsArray($apply_discount = true)
    {
        return [
            'amount'            => $this->getTotalAmount(true === $apply_discount
                                                         && true === $GLOBALS['xoopsModuleConfig']['discount_amount']),
            'handling'          => $this->getTotalHandling(true === $apply_discount
                                                           && true === $GLOBALS['xoopsModuleConfig']['discount_handling']),
            'weight'            => $this->getTotalWeight(),
            'shipping'          => $this->getTotalShipping(true === $apply_discount
                                                           && true === $GLOBALS['xoopsModuleConfig']['discount_shipping']),
            'tax'               => $this->getTotalTax($apply_discount),
            'grand'             => $this->getTotalTax($apply_discount) + $this->getTotalShipping(true === $apply_discount
                                                                                                 && true === $GLOBALS['xoopsModuleConfig']['discount_shipping']) + $this->getTotalHandling(true === $apply_discount
                                                                                                                                                                                           && true === $GLOBALS['xoopsModuleConfig']['discount_handling']) + $this->getTotalAmount(true === $apply_discount
                                                                                                                                                                                                                                                                                   && true
                                                                                                                                                                                                                                                                                      === $GLOBALS['xoopsModuleConfig']['discount_amount']),
            'discount_amount'   => $this->getDiscountAmount(true === $apply_discount
                                                            && true === $GLOBALS['xoopsModuleConfig']['discount_amount']),
            'discount_handling' => $this->getDiscountHandling(true === $apply_discount
                                                              && true === $GLOBALS['xoopsModuleConfig']['discount_handling']),
            'discount_shipping' => $this->getDiscountShipping(true === $apply_discount
                                                              && true === $GLOBALS['xoopsModuleConfig']['discount_shipping']),
            'discount_grand'    => $this->getDiscountShipping(true === $apply_discount
                                                              && true === $GLOBALS['xoopsModuleConfig']['discount_shipping']) + $this->getDiscountHandling(true === $apply_discount
                                                                                                                                                           && true === $GLOBALS['xoopsModuleConfig']['discount_handling']) + $this->getDiscountAmount(true === $apply_discount
                                                                                                                                                                                                                                                      && true
                                                                                                                                                                                                                                                         === $GLOBALS['xoopsModuleConfig']['discount_amount'])
        ];
    }

    public function runPlugin()
    {
        if (is_object($this->_invoice)) {
            require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/' . $this->_invoice->getVar('plugin') . '.php');

            switch ($this->getVar('mode')) {
                case 'PURCHASED':
                case 'REFUNDED':
                case 'UNDELIVERED':
                case 'DAMAGED':
                case 'EXPRESS':
                    $func = ucfirst($this->getVar('mode')) . ucfirst($this->_invoice->getVar('mode')) . ucfirst($this->_invoice->getVar('plugin')) . 'ItemHook';
                    break;
                default:
                    return false;
                    break;
            }

            if (function_exists($func)) {
                @$func($this, $invoice);
            }
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
class XpaymentInvoice_itemsHandler extends XoopsPersistableObjectHandler
{
    public function __construct(XoopsDatabase $db)
    {
        $this->db = $db;
        parent::__construct($db, 'xpayment_invoice_items', 'XpaymentInvoice_items', 'iiid', 'name');
    }

    public function getDiscount(&$invoice)
    {
        if (is_a($invoice, 'XpaymentInvoice')) {
            foreach (parent::getObjects(new Criteria('iid', $invoice->getVar('iid')), true) as $iiid => $item) {
                if (is_a($item, 'XpaymentInvoice_items')) {
                    $totals            = $item->getTotalsArray(true);
                    $amount            = $amount + $totals['amount'];
                    $shipping          = $shipping + $totals['shipping'];
                    $handling          = $handling + $totals['handling'];
                    $tax               = $tax + $totals['tax'];
                    $discount_amount   = $discount_amount + $totals['discount_amount'];
                    $discount_handling = $discount_handling + $totals['discount_handling'];
                    $discount_shipping = $discount_shipping + $totals['discount_shipping'];
                    $discount_grand    = $discount_grand + $totals['discount_grand'];
                }
            }

            return [
                'amount'            => $amount,
                'shipping'          => $shipping,
                'handling'          => $handling,
                'tax'               => $tax,
                'grand'             => $tax + $handling + $shipping + $amount,
                'discount_amount'   => $discount_amount,
                'discount_handling' => $discount_handling,
                'discount_shipping' => $discount_shipping,
                'discount_grand'    => $discount_grand
            ];
        }
    }

    public function insert(XoopsObject $obj, $force = true)
    {
        static $rates;

        if (!isset($obj->_invoice) && $obj->getVar('iid') > 0) {
            $obj->_invoice = $obj;
        }

        if (true === $GLOBALS['xoopsModuleConfig']['autotax']) {
            if ($obj->getVar('iid') > 0) {
                $autotaxHandler = xoops_getModuleHandler('autotax', 'xpayment');
                $obj->setVar('tax', $autotaxHandler->getTaxRate($obj->_invoice->getVar('user_ipdb_country_code')));
            }
        }

        if ($obj->isNew()) {
            $obj->setVar('created', time());
        } else {
            $obj->setVar('updated', time());
        }

        if (true === $obj->vars['mode']['changed']) {
            $obj->setVar('actioned', time());
            $run_plugin = true;
        }

        $iiid = parent::insert($obj, $force);
        if (true === $run_plugin) {
            $obj->runPlugin();
        }

        return $iiid;
    }
}
