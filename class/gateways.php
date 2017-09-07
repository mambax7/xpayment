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
class XpaymentGateways extends XoopsObject
{
    public $_classname = '';
    public $_gateway   = '';
    public $_options   = [];

    public function __construct($id = null)
    {
        $this->initVar('gid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('testmode', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('class', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('author', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('salt', XOBJ_DTYPE_TXTBOX, null, false, 255);
    }

    public function loadGateway($invoice)
    {
        if (!is_a($invoice, 'XpaymentInvoice')) {
            return false;
        }

        $gateways_optionsHandler = xoops_getModuleHandler('gateways_options', 'xpayment');
        $criteria                = new Criteria('gid', $this->getVar('gid'));
        $gateways_options        = $gateways_optionsHandler->getObjects($criteria, true);
        foreach ($gateways_options as $goid => $option) {
            $this->_options[$option->getVar('refereer')] = $option->getVar('value');
        }

        xoops_loadLanguage($this->getVar('class'), 'xpayment');

        require_once $GLOBALS['xoops']->path('modules/xpayment/class/gateway/' . $this->getVar('class') . '/' . $this->getVar('class') . '.php');
        $this->_classname = ucfirst($this->getVar('class')) . 'GatewaysPlugin';
        if (class_exists($this->_classname)) {
            $this->_gateway = new $this->_classname($invoice, $this);
        }
    }

    public function goActionCancel($request)
    {
        if (is_a($this->_gateway, $this->_classname)) {
            return $this->_gateway->goActionCancel($request);
        } else {
            return false;
        }
    }

    public function goActionReturn($request)
    {
        if (is_a($this->_gateway, $this->_classname)) {
            return $this->_gateway->goActionReturn($request);
        } else {
            return false;
        }
    }

    public function goIPN($request)
    {
        if (is_a($this->_gateway, $this->_classname)) {
            return $this->_gateway->goIPN($request);
        } else {
            return false;
        }
    }

    public function getPaymentHTML()
    {
        if (is_a($this->_gateway, $this->_classname)) {
            return $this->_gateway->getPaymentHTML();
        } else {
            return false;
        }
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
class XpaymentGatewaysHandler extends XoopsPersistableObjectHandler
{
    public function __construct(XoopsDatabase $db)
    {
        $this->db = $db;
        parent::__construct($db, 'xpayment_gateway', 'XpaymentGateways', 'gid', 'name');
    }

    public function getGateway($class = 'paypal', $invoice)
    {
        if (!is_a($invoice, 'XpaymentInvoice')) {
            return false;
        }

        $criteria = new Criteria('class', $class);
        $gateways = $this->getObjects($criteria);
        if (is_object($gateways[0])) {
            $invoice->setVar('gateway', $class);
            $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
            $invoiceHandler->insert($invoice);
            $gateways[0]->loadGateway($invoice);

            return $gateways[0];
        } else {
            return false;
        }
    }
}
