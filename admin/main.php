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
 * @copyright       Chronolabs Co-Op http://www.chronolabs.com.au/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         xpayment
 * @since           1.30.0
 * @author          Simon Roberts <simon@chronolabs.com.au>
 * @translation     Erol Konik <aphex@aphexthemes.com>
 * @translation     Mariane <mariane_antoun@hotmail.com>
 * @translation     Voltan <voltan@xoops.ir>
 * @translation     Ezsky <ezskyyoung@gmail.com>
 * @translation     Richardo Costa <lusopoemas@gmail.com>
 * @translation     Kris_fr <kris@frxoops.org>
 */
include __DIR__ . '/admin_header.php';
error_reporting(0);
xoops_cp_header();

$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);

xoops_loadLanguage('admin', 'xpayment');

switch ($_REQUEST['op']) {
    case 'invoices':
        switch ($_REQUEST['fct']) {
            case 'resendnotice':
                $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
                $invoice        = $invoiceHandler->get($_GET['iid']);

                $xoopsMailer = xoops_getMailer();
                $xoopsMailer->setHTML(true);
                $xoopsMailer->setTemplateDir($GLOBALS['xoops']->path('modules/xpayment/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_templates/'));
                $xoopsMailer->setTemplate('xpayment_invoice_reminder.tpl');
                $xoopsMailer->setSubject(sprintf(_XPY_EMAIL_REMINDER_SUBJECT, $invoice->getVar('grand'), $invoice->getVar('currency'), $invoice->getVar('drawto')));

                $xoopsMailer->setToEmails($invoice->getVar('drawto_email'));

                $xoopsMailer->assign('SITEURL', XOOPS_URL);
                $xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
                $xoopsMailer->assign('INVOICENUMBER', $invoice->getVar('invoicenumber'));
                $xoopsMailer->assign('CURRENCY', $invoice->getVar('currency'));
                $xoopsMailer->assign('DRAWTO', $invoice->getVar('drawto'));
                $xoopsMailer->assign('DRAWTO_EMAIL', $invoice->getVar('drawto_email'));
                $xoopsMailer->assign('DRAWFOR', $invoice->getVar('drawfor'));
                $xoopsMailer->assign('AMOUNT', $invoice->getVar('grand'));
                $xoopsMailer->assign('INVURL', $invoice->getURL());
                $xoopsMailer->assign('PDFURL', $invoice->getPDFURL());

                if (!$xoopsMailer->send()) {
                    xoops_error($xoopsMailer->getErrors(true), 'Email Send Error');
                }
                redirect_header($_SERVER['PHP_SELF'] . '?op=invoices&fct=list', 3, _XPY_MSG_EMAIL_REMINDER_SENT);
                break;
            default:
            case 'list':
                $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');

                $limit  = !empty($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 30;
                $start  = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
                $order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
                $sort   = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
                $filter = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '1,1';

                $criteria = $invoiceHandler->getFilterCriteria($filter);
                $ttl      = $invoiceHandler->getCount($criteria);

                $pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit=' . $limit . '&sort=' . $sort . '&order=' . $order . '&filter=' . $filter . '&op=' . $_REQUEST['op'] . '&fct=' . $_REQUEST['fct']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach ([
                             'mode',
                             'invoicenumber',
                             'drawfor',
                             'drawto',
                             'drawto_email',
                             'amount',
                             'grand',
                             'shipping',
                             'handling',
                             'weight',
                             'weight_unit',
                             'tax',
                             'currency',
                             'items',
                             'transactionid',
                             '`created`',
                             'updated',
                             'actioned',
                             'reoccurence',
                             'reoccurences',
                             'reoccurence_period_days',
                             'occurence',
                             'previous',
                             'occurence_grand',
                             'occurence_amount',
                             'occurence_tax',
                             'occurence_shipping',
                             'occurence_handling',
                             'occurence_weight',
                             'remittion',
                             'remittion_settled',
                             'donation',
                             'comment',
                             'user_ip',
                             'user_netaddy',
                             'user_uid',
                             'remitted',
                             'due',
                             'collect',
                             'wait',
                             'offline',
                             'remittion',
                             'discount',
                             'discount_amount',
                             'rate',
                             'interest'
                         ] as $id => $key) {
                    $GLOBALS['xoopsTpl']->assign(strtolower($key . '_th'), '<a href="'
                                                                           . $_SERVER['PHP_SELF']
                                                                           . '?start='
                                                                           . $start
                                                                           . '&limit='
                                                                           . $limit
                                                                           . '&sort='
                                                                           . $key
                                                                           . '&order='
                                                                           . (($key == $sort) ? ($order == 'ASC' ? 'DESC' : 'ASC') : $order)
                                                                           . '&op='
                                                                           . $_REQUEST['op']
                                                                           . '&fct='
                                                                           . $_REQUEST['fct']
                                                                           . '">'
                                                                           . (defined('_XPY_AM_TH_' . strtoupper($key)) ? constant('_XPY_AM_TH_' . strtoupper($key)) : '_XPY_AM_TH_' . strtoupper($key))
                                                                           . '</a>');
                    $GLOBALS['xoopsTpl']->assign('filter_' . strtolower(str_replace('-', '_', $key)) . '_th', $invoiceHandler->getFilterForm($filter, $key, $sort, isset($fct) ? $fct : ''));
                }

                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $criteria->setSort('`' . $sort . '`');
                $criteria->setOrder($order);

                $GLOBALS['xoopsTpl']->assign('start', $start);
                $GLOBALS['xoopsTpl']->assign('limit', $limit);
                $GLOBALS['xoopsTpl']->assign('sort', $sort);
                $GLOBALS['xoopsTpl']->assign('order', $order);
                $GLOBALS['xoopsTpl']->assign('filter', $filter);

                $invoices = $invoiceHandler->getObjects($criteria, true);
                foreach ($invoices as $iid => $invoice) {
                    $GLOBALS['xoopsTpl']->append('invoices', $invoice->toArray());
                }
                //loadModuleAdminMenu(1);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_invoice_list.tpl');
                break;
            case 'export':
                set_time_limit(3600);
                $GLOBALS['xoopsLogger']->activated = false;
                $invoiceHandler                    = xoops_getModuleHandler('invoice', 'xpayment');

                $limit    = !empty($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 30;
                $start    = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
                $order    = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
                $sort     = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
                $filter   = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '1,1';
                $criteria = $invoiceHandler->getFilterCriteria($filter);
                $criteria->setSort('`' . $sort . '`');
                $criteria->setOrder($order);

                header('Content-Disposition: attachment; filename="invoices_' . md5($filter) . '.csv"');
                header('Content-Type: text/comma-separated-values');
                if ($invoiceHandler->getCount($criteria) > 0) {
                    $invoices = $invoiceHandler->getObjects($criteria, false);
                    if ($invoices) {
                        $i = 0;
                        foreach ($invoices[0]->toArray(false) as $field => $value) {
                            ++$i;
                            print '"' . ucfirst($field) . '"';
                            if ($i < count($invoices[0]->toArray(false))) {
                                print ',';
                            } else {
                                print "\n";
                            }
                        }
                        foreach ($invoices as $iid => $invoice) {
                            $i = 0;
                            foreach ($invoice->toArray(false) as $field => $value) {
                                ++$i;
                                if (is_array($value)) {
                                    print '"' . implode(', ', $value) . '"';
                                } elseif (!is_numeric($value)) {
                                    print '"' . $value . '"';
                                } else {
                                    print '' . $value . '';
                                }
                                if ($i < count($invoice->toArray(false))) {
                                    print ',';
                                } else {
                                    print "\n";
                                }
                            }
                        }
                    }
                    exit(0);
                }
                redirect_header($_SERVER['PHP_SELF'] . '?op=invoices&fct=list', 3, _NOPERM);
                exit(0);

            case 'view':

                $invoiceHandler       = xoops_getModuleHandler('invoice', 'xpayment');
                $invoice_itemsHandler = xoops_getModuleHandler('invoice_items', 'xpayment');

                $invoice =& $invoiceHandler->get($_GET['iid']);

                $GLOBALS['xoopsTpl']->assign('invoice', $invoice->toArray());

                if ($invoice->getVar('mode') == 'UNPAID') {
                    $GLOBALS['xoopsTpl']->assign('payment_markup', $invoice->getAdminPaymentHtml());
                }

                if ($invoice->getVar('mode') == 'UNPAID'
                    && ($invoice->getVar('remittion') == 'COLLECT'
                        || $invoice->getVar('remittion') == 'SETTLED')) {
                    $GLOBALS['xoopsTpl']->assign('settle_markup', $invoice->getAdminSettleHtml());
                }

                $criteria = new Criteria('iid', $invoice->getVar('iid'));
                $items    = $invoice_itemsHandler->getObjects($criteria, true);
                foreach ($items as $iiid => $item) {
                    $GLOBALS['xoopsTpl']->append('items', $item->toArray());
                }

                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_invoice_view.tpl');

                break;
            case 'cancel':

                if (!isset($_POST['confirm'])) {
                    xoops_confirm([
                                      'confirm' => true,
                                      'op'      => $_REQUEST['op'],
                                      'fct'     => $_REQUEST['fct'],
                                      'iid'     => $_REQUEST['iid']
                                  ], $_SERVER['PHP_SELF'], _XPY_MSG_CONFIRM_CANCEL);
                    xoops_cp_footer();
                    exit(0);
                }

                $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
                $invoice        = $invoiceHandler->get($_REQUEST['iid']);
                $invoice->setVar('mode', 'CANCEL');
                $invoiceHandler->insert($invoice);
                $invoice->runPlugin();
                redirect_header($_SERVER['PHP_SELF'] . '?op=invoices&fct=list', 3, _XPY_MSG_INVOICE_CANCELED);
                exit(0);
                break;
            case 'transaction':

                $invoiceHandler              = xoops_getModuleHandler('invoice', 'xpayment');
                $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                $invoice_transactions        = $invoice_transactionsHandler->create();
                $invoice                     = $invoiceHandler->get($_REQUEST['iid']);
                $invoice_transactions->setVar('transactionid', $_REQUEST['transactionid']);
                $invoice_transactions->setVar('iid', $_REQUEST['iid']);
                $invoice_transactions->setVar('invoice', $_REQUEST['iid']);
                $invoice_transactions->setVar('date', time());
                $invoice_transactions->setVar('email', $GLOBALS['xoopsConfig']['adminmail']);
                $invoice_transactions->setVar('gross', $_REQUEST['amount']);
                $invoice_transactions->setVar('status', 'Manual');
                $invoice_transactionsHandler->insert($invoice_transactions);
                $gross = $invoice_transactionsHandler->sumOfGross($_REQUEST['iid']);
                if ($gross >= $invoice->getVar('grand')) {
                    $invoice->setVar('mode', 'PAID');
                }
                $invoice->setVar('transactionid', $_REQUEST['transactionid']);
                $invoiceHandler->insert($invoice);
                redirect_header($_SERVER['PHP_SELF'] . '?op=invoices&fct=list', 3, _XPY_MSG_INVOICE_PAID);
                exit(0);
                break;
            case 'settle':
                $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
                $invoice        = $invoiceHandler->get($_REQUEST['iid']);
                $invoice->setVar('remittion', 'SETTLED');
                $invoice->setVar('remittion_settled', $_REQUEST['settlement']);
                $invoiceHandler->insert($invoice);
                redirect_header($_SERVER['PHP_SELF'] . '?op=invoices&fct=list', 3, _XPY_MSG_INVOICE_SETTLEMENT);
                exit(0);
                break;

        }
        break;
    case 'discounts':
        switch ($_REQUEST['fct']) {
            default:
            case 'list':
                $discountHandler = xoops_getModuleHandler('discounts', 'xpayment');

                $limit  = !empty($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 30;
                $start  = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
                $order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
                $sort   = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
                $filter = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '1,1';

                $criteria = $discountHandler->getFilterCriteria($filter);
                $ttl      = $discountHandler->getCount($criteria);

                $pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit=' . $limit . '&sort=' . $sort . '&order=' . $order . '&filter=' . $filter . '&op=' . $_REQUEST['op'] . '&fct=' . $_REQUEST['fct']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach ([
                             'did',
                             'uid',
                             'code',
                             'email',
                             'validtill',
                             'redeems',
                             'discount',
                             'redeemed',
                             'iids',
                             '`created`',
                             'updated'
                         ] as $id => $key) {
                    $GLOBALS['xoopsTpl']->assign(strtolower($key . '_th'), '<a href="'
                                                                           . $_SERVER['PHP_SELF']
                                                                           . '?start='
                                                                           . $start
                                                                           . '&limit='
                                                                           . $limit
                                                                           . '&sort='
                                                                           . $key
                                                                           . '&order='
                                                                           . (($key == $sort) ? ($order == 'ASC' ? 'DESC' : 'ASC') : $order)
                                                                           . '&op='
                                                                           . $_REQUEST['op']
                                                                           . '&fct='
                                                                           . $_REQUEST['fct']
                                                                           . '">'
                                                                           . (defined('_XPY_AM_TH_' . strtoupper($key)) ? constant('_XPY_AM_TH_' . strtoupper($key)) : '_XPY_AM_TH_' . strtoupper($key))
                                                                           . '</a>');
                    $GLOBALS['xoopsTpl']->assign('filter_' . strtolower(str_replace('-', '_', $key)) . '_th', $discountHandler->getFilterForm($filter, $key, $sort, isset($fct) ? $fct : ''));
                }

                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $criteria->setSort('`' . $sort . '`');
                $criteria->setOrder($order);

                $GLOBALS['xoopsTpl']->assign('start', $start);
                $GLOBALS['xoopsTpl']->assign('limit', $limit);
                $GLOBALS['xoopsTpl']->assign('sort', $sort);
                $GLOBALS['xoopsTpl']->assign('order', $order);
                $GLOBALS['xoopsTpl']->assign('filter', $filter);

                $GLOBALS['xoopsTpl']->assign('form', xpayment_admincreatediscounts());

                $discounts = $discountHandler->getObjects($criteria, true);
                foreach ($discounts as $iid => $discount) {
                    $GLOBALS['xoopsTpl']->append('discounts', $discount->toArray());
                }
                //loadModuleAdminMenu(8);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_discounts_list.tpl');
                break;
            case 'create':
                extract($_POST);
                if ((int)$redeems == 0) {
                    redirect_header($_SERVER['PHP_SELF'] . '?op=discounts&fct=list&sort=' . $sort . '&order=' . $order . '&start=' . $start . '&limit=' . $limit . '&filter=' . $filter, 3, _XPY_MSG_DISCOUNT_NOREDEEMS_SPECIFIED);
                    exit(0);
                }
                if ((int)$discount == 0) {
                    redirect_header($_SERVER['PHP_SELF'] . '?op=discounts&fct=list&sort=' . $sort . '&order=' . $order . '&start=' . $start . '&limit=' . $limit . '&filter=' . $filter, 3, _XPY_MSG_DISCOUNT_NODISCOUNT_SPECIFIED);
                    exit(0);
                }
                $created         = 0;
                $reminders       = 0;
                $prefix          = str_replace(' ', '', $prefix);
                $discountHandler = xoops_getModuleHandler('discounts', 'xpayment');
                foreach (explode('|', $emails) as $email) {
                    if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email)) {
                        if (!$dis = $discountHandler->getByEmail($email)) {
                            if ($discountHandler->sendDiscountCode($email, ($validtill_infinte === true ? 0 : strtotime($validtill['date']) + $validtill['time']), (int)$redeems, (float)$discount, $prefix, 0)) {
                                ++$created;
                            }
                        } else {
                            if ($dis->sendReminderEmail()) {
                                ++$reminders;
                            }
                        }
                    }
                }
                if ($scan === true) {
                    foreach ($groups as $group) {
                        foreach ($discountHandler->getUsersByGroup($group, ($logon === true ? strtotime($logon_datetime['date']) + $logon_datetime['time'] : 0), ($since === true ? strtotime($since_datetime['date']) + $since_datetime['time'] : 0), true) as $user) {
                            if (!$dis = $discountHandler->getByEmail($user->getVar('email'))) {
                                if ($discountHandler->sendDiscountCode($user->getVar('email'), ($validtill_infinte === true ? 0 : strtotime($validtill['date']) + $validtill['time']), (int)$redeems, (float)$discount, $prefix, $user->getVar('uid'))) {
                                    ++$created;
                                }
                            } else {
                                if ($dis->sendReminderEmail()) {
                                    ++$reminders;
                                }
                            }
                        }
                    }
                }
                redirect_header($_SERVER['PHP_SELF'] . '?op=discounts&fct=list&sort=' . $sort . '&order=' . $order . '&start=' . $start . '&limit=' . $limit . '&filter=' . $filter, 3, sprintf(_XPY_MSG_DISCOUNT_CREATED_REMINDED, $created, $reminders));
                exit(0);
                break;
        }
        break;
    case 'tax':
        switch ($_REQUEST['fct']) {
            default:
            case 'list':
                $autotaxHandler = xoops_getModuleHandler('autotax', 'xpayment');

                $ttl   = $autotaxHandler->getCount(null);
                $limit = !empty($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 30;
                $start = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
                $order = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'ASC';
                $sort  = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'country';

                $pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit=' . $limit . '&sort=' . $sort . '&order=' . $order . '&op=' . $_REQUEST['op'] . '&fct=' . $_REQUEST['fct']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                $criteria = new Criteria('1', '1');
                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $criteria->setSort('`' . $sort . '`');
                $criteria->setOrder($order);

                $rates = $autotaxHandler->getObjects($criteria, true);

                foreach ($rates as $id => $rate) {
                    $GLOBALS['xoopsTpl']->append('rates', $rate->toArray());
                }

                foreach (['country', 'code', 'rate'] as $id => $key) {
                    $GLOBALS['xoopsTpl']->assign($key . '_th', '<a href="'
                                                               . $_SERVER['PHP_SELF']
                                                               . '?'
                                                               . 'start='
                                                               . $start
                                                               . '&limit='
                                                               . $limit
                                                               . '&sort='
                                                               . $key
                                                               . '&order='
                                                               . (($key == $sort) ? ($order == 'ASC' ? 'DESC' : 'ASC') : $order)
                                                               . '&op='
                                                               . $_REQUEST['op']
                                                               . '&fct='
                                                               . $_REQUEST['fct']
                                                               . '">'
                                                               . (defined('_XPY_AM_TH_' . strtoupper($key)) ? constant('_XPY_AM_TH_' . strtoupper($key)) : '_XPY_AM_TH_' . strtoupper($key))
                                                               . '</a>');
                }
                //loadModuleAdminMenu(6);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_tax_list.tpl');
                break;
            case 'save':
                $autotaxHandler = xoops_getModuleHandler('autotax', 'xpayment');
                foreach ($_POST['id'] as $key => $id) {
                    $tax = $autotaxHandler->get($id);
                    $tax->setVars($_POST[$id]);
                    $autotaxHandler->insert($tax, true);
                }
                redirect_header($_SERVER['PHP_SELF'] . '?op=tax&fct=list', 3, _XPY_MSG_TAX_SAVED);
                exit(0);
                break;
        }
        break;
    case 'transactions':

        switch ($_REQUEST['fct']) {
            default:
            case 'list':
                $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                isset($fct) ? $fct : '';
                $ttl   = $invoice_transactionsHandler->getCount(null);
                $limit = !empty($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 30;
                $start = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
                $order = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
                $sort  = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'date';

                if (!isset($_GET['iid']) || $_GET['iid'] == 0) {
                    //            if ($_GET['iid']==0) {
                    $pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit=' . $limit . '&sort=' . $sort . '&order=' . $order . '&op=' . $_REQUEST['op'] . '&fct=' . $_REQUEST['fct']);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                    $criteria = new Criteria('1', '1');
                    $criteria->setStart($start);
                    $criteria->setLimit($limit);
                    $criteria->setSort('`' . $sort . '`');
                    $criteria->setOrder($order);
                } else {
                    $pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit=' . $limit . '&sort=' . $sort . '&order=' . $order . '&iid=' . $_REQUEST['iid'] . '&op=' . $_REQUEST['op'] . '&fct=' . $_REQUEST['fct']);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                    $criteria = new Criteria('iid', $_REQUEST['iid']);
                    $criteria->setStart($start);
                    $criteria->setLimit($limit);
                    $criteria->setSort('`' . $sort . '`');
                    $criteria->setOrder($order);

                    $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');
                    $invoice        =& $invoiceHandler->get($_GET['iid']);
                    $GLOBALS['xoopsTpl']->assign('invoice', $invoice->toArray());
                }

                $transactions = $invoice_transactionsHandler->getObjects($criteria, true);

                foreach ($transactions as $tiid => $transaction) {
                    $GLOBALS['xoopsTpl']->append('transactions', $transaction->toArray());
                }

                if (!isset($_GET['iid']) || $_GET['iid'] == 0) {
                    foreach ([
                                 'transactionid',
                                 'email',
                                 'invoice',
                                 'status',
                                 'date',
                                 'gross',
                                 'fee',
                                 'settle',
                                 'exchangerate',
                                 'firstname',
                                 'lastname',
                                 'street',
                                 'city',
                                 'state',
                                 'postcode',
                                 'country',
                                 'address_status',
                                 'payer_email',
                                 'payer_status',
                                 'gateway',
                                 'plugin'
                             ] as $id => $key) {
                        $GLOBALS['xoopsTpl']->assign($key . '_th', '<a href="'
                                                                   . $_SERVER['PHP_SELF']
                                                                   . '?'
                                                                   . 'start='
                                                                   . $start
                                                                   . '&limit='
                                                                   . $limit
                                                                   . '&sort='
                                                                   . $key
                                                                   . '&order='
                                                                   . (($key == $sort) ? ($order == 'ASC' ? 'DESC' : 'ASC') : $order)
                                                                   . '&op='
                                                                   . $_REQUEST['op']
                                                                   . '&fct='
                                                                   . $_REQUEST['fct']
                                                                   . '">'
                                                                   . (defined('_XPY_AM_TH_' . strtoupper($key)) ? constant('_XPY_AM_TH_' . strtoupper($key)) : '_XPY_AM_TH_' . strtoupper($key))
                                                                   . '</a>');
                    }
                } else {
                    foreach ([
                                 'transactionid',
                                 'email',
                                 'invoice',
                                 'status',
                                 'date',
                                 'gross',
                                 'fee',
                                 'settle',
                                 'exchangerate',
                                 'firstname',
                                 'lastname',
                                 'street',
                                 'city',
                                 'state',
                                 'postcode',
                                 'country',
                                 'address_status',
                                 'payer_email',
                                 'payer_status',
                                 'gateway',
                                 'plugin'
                             ] as $id => $key) {
                        $GLOBALS['xoopsTpl']->assign($key . '_th', '<a href="'
                                                                   . $_SERVER['PHP_SELF']
                                                                   . '?'
                                                                   . 'start='
                                                                   . $start
                                                                   . '&limit='
                                                                   . $limit
                                                                   . '&sort='
                                                                   . $key
                                                                   . '&order='
                                                                   . (($key == $sort) ? ($order == 'ASC' ? 'DESC' : 'ASC') : $order)
                                                                   . '&op='
                                                                   . $_REQUEST['op']
                                                                   . '&fct='
                                                                   . $_REQUEST['fct']
                                                                   . '&iid='
                                                                   . $_REQUEST['iid']
                                                                   . '">'
                                                                   . (defined('_XPY_AM_TH_' . strtoupper($key)) ? constant('_XPY_AM_TH_' . strtoupper($key)) : '_XPY_AM_TH_' . strtoupper($key))
                                                                   . '</a>');
                    }
                }
                //loadModuleAdminMenu(2);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_transactions_list.tpl');
                break;
            case 'view':
                $invoice_transactionsHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
                $transaction                 =& $invoice_transactionsHandler->get($_GET['tiid']);
                $GLOBALS['xoopsTpl']->assign('transaction', $transaction->toArray());
                //loadModuleAdminMenu(2);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_transactions_view.tpl');
                break;
        }
        break;
    case 'gateways':
        $gatewaysHandler = xoops_getModuleHandler('gateways', 'xpayment');
        $gateway         = isset($_REQUEST['gid']) ? $gatewaysHandler->get($_REQUEST['gid']) : '';
        if (is_object($gateway)) {
            require_once $GLOBALS['xoops']->path('modules/xpayment/class/gateway/' . $gateway->getVar('class') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . $gateway->getVar('class') . '.php');
        }

        switch ($_REQUEST['fct']) {
            default:
            case 'list':
                $gatewaysHandler = xoops_getModuleHandler('gateways', 'xpayment');

                $ttl   = $gatewaysHandler->getCount(null);
                $limit = !empty($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 30;
                $start = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
                $order = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
                $sort  = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'name';

                $pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit=' . $limit . '&sort=' . $sort . '&order=' . $order . '&op=' . $_REQUEST['op'] . '&fct=' . $_REQUEST['fct']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach (['name', 'description', 'author', 'testmode'] as $id => $key) {
                    $GLOBALS['xoopsTpl']->assign(strtolower($key . '_th'), '<a href="'
                                                                           . $_SERVER['PHP_SELF']
                                                                           . '?start='
                                                                           . $start
                                                                           . '&limit='
                                                                           . $limit
                                                                           . '&sort='
                                                                           . $key
                                                                           . '&order='
                                                                           . (($key == $sort) ? ($order == 'ASC' ? 'DESC' : 'ASC') : $order)
                                                                           . '&op='
                                                                           . $_REQUEST['op']
                                                                           . '&fct='
                                                                           . $_REQUEST['fct']
                                                                           . '">'
                                                                           . (defined('_XPY_AM_TH_' . strtoupper($key)) ? constant('_XPY_AM_TH_' . strtoupper($key)) : '_XPY_AM_TH_' . strtoupper($key))
                                                                           . '</a>');
                }

                $criteria = new Criteria('1', '1');
                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $criteria->setSort('`' . $sort . '`');
                $criteria->setOrder($order);

                $gateways = $gatewaysHandler->getObjects($criteria, true);
                foreach ($gateways as $gid => $gateway) {
                    xoops_loadLanguage($gateway->getVar('class'), 'xpayment');

                    $ret                = $gateway->toArray();
                    $ret['name']        = (defined($ret['name']) ? constant($ret['name']) : $ret['name']);
                    $ret['description'] = (defined($ret['description']) ? constant($ret['description']) : $ret['description']);
                    $ret['author']      = (defined($ret['author']) ? constant($ret['author']) : $ret['author']);
                    $GLOBALS['xoopsTpl']->append('gateways', $ret);
                    $installed[$gateway->getVar('class')] = $gateway->getVar('class');
                }

                xoops_load('XoopsLists');
                $gateways = XoopsLists::getDirListAsArray($GLOBALS['xoops']->path('modules/xpayment/class/gateway/'));

                foreach ($gateways as $class) {
                    if (!in_array($class, $installed)) {
                        include $GLOBALS['xoops']->path('modules/xpayment/class/gateway/' . $class . '/gateway_info.php');
                        if (!empty($gateway)) {
                            $ret                = $gateway;
                            $ret['name']        = (defined($ret['name']) ? constant($ret['name']) : $ret['name']);
                            $ret['description'] = (defined($ret['description']) ? constant($ret['description']) : $ret['description']);
                            $ret['author']      = (defined($ret['author']) ? constant($ret['author']) : $ret['author']);
                            $GLOBALS['xoopsTpl']->append('uninstalled', $ret);
                        }
                    }
                }
                //loadModuleAdminMenu(3);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_gateways_list.tpl');
                break;

            case 'options':
                $gateways_optionsHandler = xoops_getModuleHandler('gateways_options', 'xpayment');
                $gatewaysHandler         = xoops_getModuleHandler('gateways', 'xpayment');
                $gateway                 = $gatewaysHandler->get($_GET['gid']);

                xoops_loadLanguage($gateway->getVar('class'), 'xpayment');

                $criteria = new Criteria('gid', $_GET['gid']);

                $options = $gateways_optionsHandler->getObjects($criteria, true);
                foreach ($options as $goid => $option) {
                    $ret         = $option->toArray();
                    $ret['name'] = (defined($ret['name']) ? constant($ret['name']) : $ret['name']);
                    $GLOBALS['xoopsTpl']->append('options', $ret);
                }
                //loadModuleAdminMenu(3);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_gateways_options.tpl');
                break;

            case 'settestmode':
                $gatewaysHandler = xoops_getModuleHandler('gateways', 'xpayment');
                $gateways        = $gatewaysHandler->getObjects(null, true);

                foreach ($gateways as $gid => $gateway) {
                    $gateway->setVar('testmode', ($_POST['testmode'][$gid] === true ? true : false));
                    $gatewaysHandler->insert($gateway);
                }
                redirect_header($_SERVER['PHP_SELF'] . '?op=gateways&fct=list', 3, _XPY_MSG_TESTMODES_SAVED);
                exit(0);
                break;
            case 'setoptions':
                $gateways_optionsHandler = xoops_getModuleHandler('gateways_options', 'xpayment');
                foreach ($_POST['value'] as $goid => $value) {
                    $option = $gateways_optionsHandler->get($goid);
                    $option->setVar('value', $value);
                    $gateways_optionsHandler->insert($option);
                }
                redirect_header($_SERVER['PHP_SELF'] . '?op=gateways&fct=list', 3, _XPY_MSG_OPTIONS_SAVED);
                exit(0);
                break;
            case 'update':
                xpayment_update_gateway($_GET['class']);
                redirect_header($_SERVER['PHP_SELF'] . '?op=gateways&fct=list', 3, _XPY_MSG_GATEWAY_UPDATED);
                exit(0);
                break;
            case 'install':
                xpayment_install_gateway($_GET['class']);
                redirect_header($_SERVER['PHP_SELF'] . '?op=gateways&fct=list', 3, _XPY_MSG_GATEWAY_INSTALL);
                exit(0);
                break;
        }
        break;
    case 'permissions':

        //      loadModuleAdminMenu(4);

        $opform    = new XoopsSimpleForm(_XPY_AM_PERM_FCT, 'actionform', 'main.php', 'get');
        $op_select = new XoopsFormSelect('', 'fct', $_REQUEST['fct']);
        $op_select->setExtra('onchange="document.forms.actionform.submit()"');
        $op_select->addOptionArray([
                                       'email'    => _XPY_AM_PERM_EMAIL,
                                       'gateways' => _XPY_AM_PERM_GATEWAYS,
                                   ]);
        $opform->addElement($op_select);
        $opform->addElement(new XoopsFormHidden('op', 'permissions'));
        $opform->display();

        switch ($_REQUEST['fct']) {
            default:
            case 'email':

                $base                        = [];
                $base[_XPY_ENUM_MODE_PAID]   = _XPY_AM_MODE_DESC_PAID;
                $base[_XPY_ENUM_MODE_UNPAID] = _XPY_AM_MODE_DESC_UNPAID;
                $base[_XPY_ENUM_MODE_CANCEL] = _XPY_AM_MODE_DESC_CANCEL;

                $sub                                                        = [];
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_REMITTION_NONE]         = _XPY_AM_MODE_DESC_PAID_NONE;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_REMITTION_NONE]       = _XPY_AM_MODE_DESC_UNPAID_NONE;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_REMITTION_NONE]       = _XPY_AM_MODE_DESC_CANCEL_NONE;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_REMITTION_PENDING]      = _XPY_AM_MODE_DESC_PAID_PENDING;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_REMITTION_PENDING]    = _XPY_AM_MODE_DESC_UNPAID_PENDING;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_REMITTION_PENDING]    = _XPY_AM_MODE_DESC_CANCEL_PENDING;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_REMITTION_NOTICE]       = _XPY_AM_MODE_DESC_PAID_NOTICE;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_REMITTION_NOTICE]     = _XPY_AM_MODE_DESC_UNPAID_NOTICE;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_REMITTION_NOTICE]     = _XPY_AM_MODE_DESC_CANCEL_NOTICE;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_REMITTION_COLLECT]      = _XPY_AM_MODE_DESC_PAID_COLLECT;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_REMITTION_COLLECT]    = _XPY_AM_MODE_DESC_UNPAID_COLLECT;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_REMITTION_COLLECT]    = _XPY_AM_MODE_DESC_CANCEL_COLLECT;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_REMITTION_FRAUD]        = _XPY_AM_MODE_DESC_PAID_FRAUD;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_REMITTION_FRAUD]      = _XPY_AM_MODE_DESC_UNPAID_FRAUD;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_REMITTION_FRAUD]      = _XPY_AM_MODE_DESC_CANCEL_FRAUD;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_REMITTION_SETTLED]      = _XPY_AM_MODE_DESC_PAID_SETTLED;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_REMITTION_SETTLED]    = _XPY_AM_MODE_DESC_UNPAID_SETTLED;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_REMITTION_SETTLED]    = _XPY_AM_MODE_DESC_CANCEL_SETTLED;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_REMITTION_DISCOUNTED]   = _XPY_AM_MODE_DESC_PAID_DISCOUNTED;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_REMITTION_DISCOUNTED] = _XPY_AM_MODE_DESC_UNPAID_DISCOUNTED;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_REMITTION_DISCOUNTED] = _XPY_AM_MODE_DESC_CANCEL_DISCOUNTED;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_ITEMMODE_PURCHASED]     = _XPY_AM_MODE_DESC_PAID_ITEM_PURCHASED;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_ITEMMODE_PURCHASED]   = _XPY_AM_MODE_DESC_UNPAID_ITEM_PURCHASED;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_ITEMMODE_PURCHASED]   = _XPY_AM_MODE_DESC_CANCEL_ITEM_PURCHASED;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_ITEMMODE_REFUNDED]      = _XPY_AM_MODE_DESC_PAID_ITEM_REFUNDED;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_ITEMMODE_REFUNDED]    = _XPY_AM_MODE_DESC_UNPAID_ITEM_REFUNDED;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_ITEMMODE_REFUNDED]    = _XPY_AM_MODE_DESC_CANCEL_ITEM_REFUNDED;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_ITEMMODE_UNDELIVERED]   = _XPY_AM_MODE_DESC_PAID_ITEM_UNDELIVERED;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_ITEMMODE_UNDELIVERED] = _XPY_AM_MODE_DESC_UNPAID_ITEM_UNDELIVERED;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_ITEMMODE_UNDELIVERED] = _XPY_AM_MODE_DESC_CANCEL_ITEM_UNDELIVERED;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_ITEMMODE_DAMAGED]       = _XPY_AM_MODE_DESC_PAID_ITEM_DAMAGED;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_ITEMMODE_DAMAGED]     = _XPY_AM_MODE_DESC_UNPAID_ITEM_DAMAGED;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_ITEMMODE_DAMAGED]     = _XPY_AM_MODE_DESC_CANCEL_ITEM_DAMAGED;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_ITEMMODE_PENDING]       = _XPY_AM_MODE_DESC_PAID_ITEM_PENDING;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_ITEMMODE_PENDING]     = _XPY_AM_MODE_DESC_UNPAID_ITEM_PENDING;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_ITEMMODE_PENDING]     = _XPY_AM_MODE_DESC_CANCEL_ITEM_PENDING;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_ITEMMODE_EXPRESS]       = _XPY_AM_MODE_DESC_PAID_ITEM_EXPRESS;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_ITEMMODE_EXPRESS]     = _XPY_AM_MODE_DESC_UNPAID_ITEM_EXPRESS;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_ITEMMODE_EXPRESS]     = _XPY_AM_MODE_DESC_CANCEL_ITEM_EXPRESS;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_TRANSACTION_PAYMENT]    = _XPY_AM_MODE_DESC_PAID_TRANSACTION_PAYMENT;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_TRANSACTION_PAYMENT]  = _XPY_AM_MODE_DESC_UNPAID_TRANSACTION_PAYMENT;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_TRANSACTION_PAYMENT]  = _XPY_AM_MODE_DESC_CANCEL_TRANSACTION_PAYMENT;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_TRANSACTION_REFUND]     = _XPY_AM_MODE_DESC_PAID_TRANSACTION_REFUND;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_TRANSACTION_REFUND]   = _XPY_AM_MODE_DESC_UNPAID_TRANSACTION_REFUND;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_TRANSACTION_REFUND]   = _XPY_AM_MODE_DESC_CANCEL_TRANSACTION_REFUND;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_TRANSACTION_PENDING]    = _XPY_AM_MODE_DESC_PAID_TRANSACTION_PENDING;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_TRANSACTION_PENDING]  = _XPY_AM_MODE_DESC_UNPAID_TRANSACTION_PENDING;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_TRANSACTION_PENDING]  = _XPY_AM_MODE_DESC_CANCEL_TRANSACTION_PENDING;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_TRANSACTION_NOTICE]     = _XPY_AM_MODE_DESC_PAID_TRANSACTION_NOTICE;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_TRANSACTION_NOTICE]   = _XPY_AM_MODE_DESC_UNPAID_TRANSACTION_NOTICE;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_TRANSACTION_NOTICE]   = _XPY_AM_MODE_DESC_CANCEL_TRANSACTION_NOTICE;
                $sub[_XPY_ENUM_MODE_PAID][_XPY_ENUM_TRANSACTION_OTHER]      = _XPY_AM_MODE_DESC_PAID_TRANSACTION_OTHER;
                $sub[_XPY_ENUM_MODE_UNPAID][_XPY_ENUM_TRANSACTION_OTHER]    = _XPY_AM_MODE_DESC_UNPAID_TRANSACTION_OTHER;
                $sub[_XPY_ENUM_MODE_CANCEL][_XPY_ENUM_TRANSACTION_OTHER]    = _XPY_AM_MODE_DESC_CANCEL_TRANSACTION_OTHER;

                $perm_title = _XPY_AM_PERM_TITLE_EMAIL;
                $perm_name  = _XPY_AM_PERM_NAME_EMAIL;
                $perm_desc  = _XPY_AM_PERM_DESC_EMAIL;
                $anonymous  = true;
                break;
            case 'gateways':
                $gatewaysHandler = xoops_getModuleHandler('gateways', 'xpayment');
                $gateways        = $gatewaysHandler->getObjects(null, true);
                $base            = [];
                foreach ($gateways as $gid => $gateway) {
                    xoops_loadLanguage($gateway->getVar('class'), 'xpayment');
                    $base[$gid] = (defined($gateway->getVar('name')) ? constant($gateway->getVar('name')) : $gateway->getVar('name'));
                }
                $perm_title = _XPY_AM_PERM_TITLE_GATEWAY;
                $perm_name  = _XPY_AM_PERM_NAME_GATEWAY;
                $perm_desc  = _XPY_AM_PERM_DESC_GATEWAY;
                $anonymous  = true;
                break;
        }

        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('xpayment');
        $form          = new XpaymentGroupPermForm($perm_title, $module->getVar('mid'), $perm_name, $perm_desc, 'admin/main.php?op=permissions&fct=' . $_REQUEST['fct'], $anonymous);

        foreach (array_keys($base) as $c) {
            if (isset($sub[$c]) && is_array($sub[$c])) {
                $form->addItem($c, '<strong>' . $base[$c] . '</strong>');
                foreach (array_keys($sub[$c]) as $f) {
                    $form->addItem($c + $f, '<em>' . $sub[$c][$f] . '</em>');
                }
            } else {
                $form->addItem($c, '' . $base[$c] . '');
            }
        }

        $form->display();

        break;
    case 'groups':
        switch ($_REQUEST['fct']) {
            default:
            case 'brokers':
            case 'accounts':
            case 'officers':
                $groupsHandler = xoops_getModuleHandler('groups', 'xpayment');

                switch ($_REQUEST['fct']) {
                    case 'brokers':
                        $criteria = new Criteria('mode', 'BROKERS');
                        break;
                    case 'accounts':
                        $criteria = new Criteria('mode', 'ACCOUNTS');
                        break;
                    case 'officers':
                        $criteria = new Criteria('mode', 'OFFICERS');
                        break;
                }
                $ttl   = $groupsHandler->getCount($criteria);
                $limit = !empty($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 30;
                $start = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
                $order = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
                $sort  = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'plugin';

                $pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit=' . $limit . '&sort=' . $sort . '&order=' . $order . '&op=' . $_REQUEST['op'] . '&fct=' . $_REQUEST['fct']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach (['rid', 'mode', 'plugin', 'uid', 'limit', 'maximum', 'minimum'] as $id => $key) {
                    $GLOBALS['xoopsTpl']->assign(strtolower($key . '_th'), '<a href="'
                                                                           . $_SERVER['PHP_SELF']
                                                                           . '?start='
                                                                           . $start
                                                                           . '&limit='
                                                                           . $limit
                                                                           . '&sort='
                                                                           . $key
                                                                           . '&order='
                                                                           . (($key == $sort) ? ($order == 'ASC' ? 'DESC' : 'ASC') : $order)
                                                                           . '&op='
                                                                           . $_REQUEST['op']
                                                                           . '&fct='
                                                                           . $_REQUEST['fct']
                                                                           . '">'
                                                                           . (defined('_XPY_AM_TH_' . strtoupper($key)) ? constant('_XPY_AM_TH_' . strtoupper($key)) : '_XPY_AM_TH_' . strtoupper($key))
                                                                           . '</a>');
                }

                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $criteria->setSort('`' . $sort . '`');
                $criteria->setOrder($order);

                $groups = $groupsHandler->getObjects($criteria, true);
                foreach ($groups as $rid => $group) {
                    $GLOBALS['xoopsTpl']->append('groups', $group->toArray());
                }

                /** @var XoopsModuleHandler $moduleHandler */
                $moduleHandler = xoops_getHandler('module');
                $configHandler = xoops_getHandler('config');
                $xoMod         = $moduleHandler->getByDirname('xpayment');
                $xoConfig      = $configHandler->getConfigList($xoMod->getVar('mid'));

                $GLOBALS['xoopsTpl']->assign('form', xpayment_adminrule(0, $xoConfig[$_REQUEST['fct']]));

                $opform    = new XoopsSimpleForm(_XPY_AM_GROUP_FCT, 'actionform', 'main.php', 'get');
                $op_select = new XoopsFormSelect('', 'fct', $_REQUEST['fct']);
                $op_select->setExtra('onchange="document.forms.actionform.submit()"');
                $op_select->addOptionArray([
                                               'brokers'  => _XPY_AM_GROUP_BROKERS,
                                               'accounts' => _XPY_AM_GROUP_ACCOUNTS,
                                               'officers' => _XPY_AM_GROUP_OFFICERS
                                           ]);
                $opform->addElement($op_select);
                $opform->addElement(new XoopsFormHidden('op', 'groups'));
                $GLOBALS['xoopsTpl']->assign('selectform', $opform->render());

                //loadModuleAdminMenu(5);
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_groups.tpl');
                break;
            case 'save':
                $groupsHandler = xoops_getModuleHandler('groups', 'xpayment');
                if ($_REQUEST['rid'] == 0) {
                    $group = $groupsHandler->create();
                } else {
                    $group = $groupsHandler->get($_REQUEST['rid']);
                }

                $group->setVars($_POST);

                switch ($_REQUEST['action']) {
                    case 'brokers':
                        $group->setVar('mode', 'BROKERS');
                        $fct = $_REQUEST['action'];
                        break;
                    case 'accounts':
                        $group->setVar('mode', 'ACCOUNTS');
                        $fct = $_REQUEST['action'];
                        break;
                    case 'officers':
                        $group->setVar('mode', 'OFFICERS');
                        $fct = $_REQUEST['action'];
                        break;
                    default:
                        $fct = 'brokers';
                        break;
                }

                $groupsHandler->insert($group, true);
                redirect_header($_SERVER['PHP_SELF'] . '?op=groups&fct=' . $fct, 3, _XPY_MSG_RULE_SAVED);
                break;

            case 'edit':
                $groupsHandler = xoops_getModuleHandler('groups', 'xpayment');
                if ($_REQUEST['rid'] == 0) {
                    $group = $groupsHandler->create();
                } else {
                    $group = $groupsHandler->get($_REQUEST['rid']);
                }

                /** @var XoopsModuleHandler $moduleHandler */
                $moduleHandler = xoops_getHandler('module');
                $configHandler = xoops_getHandler('config');
                $xoMod         = $moduleHandler->getByDirname('xpayment');
                $xoConfig      = $configHandler->getConfigList($xoMod->getVar('mid'));

                switch ($group->getVar('mode')) {
                    case 'BROKERS':
                        $groupid = $xoConfig['brokers'];
                        break;
                    case 'ACCOUNTS':
                        $groupid = $xoConfig['accounts'];
                        break;
                    case 'OFFICERS':
                        $groupid = $xoConfig['officers'];
                        break;
                }
                $GLOBALS['xoopsTpl']->assign('form', xpayment_adminrule($_REQUEST['rid'], $groupid));
                $GLOBALS['xoopsTpl']->display('db:xpayment_cpanel_groups_edit.tpl');
                break;
            case 'delete':
                if (!isset($_POST['confirm'])) {
                    xoops_confirm([
                                      'confirm' => true,
                                      'op'      => $_REQUEST['op'],
                                      'fct'     => $_REQUEST['fct'],
                                      'rid'     => $_REQUEST['rid']
                                  ], $_SERVER['PHP_SELF'], _XPY_MSG_CONFIRM_DELETE);
                    xoops_cp_footer();
                    exit(0);
                }
                $groupsHandler = xoops_getModuleHandler('groups', 'xpayment');
                $group         = $groupsHandler->get($_REQUEST['rid']);
                $groupsHandler->delete($group);
                redirect_header($_SERVER['PHP_SELF'] . '?op=groups&fct=brokers', 3, _XPY_MSG_RULE_DELETED);
                exit(0);
                break;
        }
        break;
    case 'dashboard':
    default:

        //loadModuleAdminMenu(0);

        $invoiceHandler = xoops_getModuleHandler('invoice', 'xpayment');

        $adminObject = \Xmf\Module\Admin::getInstance();

        $parts = [
            _XPY_AM_INVOICES_ASTOTALING   => ['`created`' => ['value' => '0', 'operator' => '>=']],
            _XPY_AM_INVOICES_LAST12MONTHS => [
                '`created`' => [
                    'value'    => time() - (60 * 60 * 24 * 7 * 4 * 12),
                    'operator' => '>='
                ]
            ],
            _XPY_AM_INVOICES_LAST6MONTHS  => [
                '`created`' => [
                    'value'    => time() - (60 * 60 * 24 * 7 * 4 * 6),
                    'operator' => '>='
                ]
            ],
            _XPY_AM_INVOICES_LAST3MONTHS  => [
                '`created`' => [
                    'value'    => time() - (60 * 60 * 24 * 7 * 4 * 3),
                    'operator' => '>='
                ]
            ],
            _XPY_AM_INVOICES_LAST1MONTHS  => [
                '`created`' => [
                    'value'    => time() - (60 * 60 * 24 * 7 * 4 * 1),
                    'operator' => '>='
                ]
            ]
        ];
        foreach (array_reverse($parts) as $title => $part) {
            foreach ($invoiceHandler->getCurrenciesUsed($part) as $currency) {
                if ($invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                  '`currency`' => [
                                                                                                      'value'    => $currency,
                                                                                                      'operator' => '='
                                                                                                  ]
                                                                                              ], $part)) != '0.00'
                    || $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                   '`currency`' => [
                                                                                                       'value'    => $currency,
                                                                                                       'operator' => '='
                                                                                                   ]
                                                                                               ], $part)) != '0.00'
                    || $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                     '`currency`' => [
                                                                                                         'value'    => $currency,
                                                                                                         'operator' => '='
                                                                                                     ]
                                                                                                 ], $part)) != '0.00') {
                    $adminObject->addInfoBox(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency));
                    if ($part['`created`']['value'] != 0) {
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_FROM . '</label>', date(_DATESTRING, time()), 'Red');
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TO . '</label>', date(_DATESTRING, $part['`created`']['value']), 'Red');
                    }
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                          '`currency`' => [
                                                                                                                                                                                                                                                              'value'    => $currency,
                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                      ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                             '`currency`' => [
                                                                                                                                                                                                                                                                 'value'    => $currency,
                                                                                                                                                                                                                                                                 'operator' => '='
                                                                                                                                                                                                                                                             ]
                                                                                                                                                                                                                                                         ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_INTEREST . " $currency</label>", $invoiceHandler->getSumByField('`interest`', '1', '1', array_merge([
                                                                                                                                                                                                                                                     '`currency`' => [
                                                                                                                                                                                                                                                         'value'    => $currency,
                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                 ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID_NONE . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                               '`remittion`' => [
                                                                                                                                                                                                                                                                   'value'    => 'NONE',
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], [
                                                                                                                                                                                                                                                               '`currency`' => [
                                                                                                                                                                                                                                                                   'value'    => $currency,
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID_NONE . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                           '`remittion`' => [
                                                                                                                                                                                                                                                               'value'    => 'NONE',
                                                                                                                                                                                                                                                               'operator' => '='
                                                                                                                                                                                                                                                           ]
                                                                                                                                                                                                                                                       ], [
                                                                                                                                                                                                                                                           '`currency`' => [
                                                                                                                                                                                                                                                               'value'    => $currency,
                                                                                                                                                                                                                                                               'operator' => '='
                                                                                                                                                                                                                                                           ]
                                                                                                                                                                                                                                                       ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED_NONE . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                      'value'    => 'NONE',
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID_COLLECT . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                      'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID_COLLECT . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                              '`remittion`' => [
                                                                                                                                                                                                                                                                  'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], [
                                                                                                                                                                                                                                                              '`currency`' => [
                                                                                                                                                                                                                                                                  'value'    => $currency,
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED_COLLECT . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                                         'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], [
                                                                                                                                                                                                                                                                     '`currency`' => [
                                                                                                                                                                                                                                                                         'value'    => $currency,
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID_FRAUD . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                '`remittion`' => [
                                                                                                                                                                                                                                                                    'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], [
                                                                                                                                                                                                                                                                '`currency`' => [
                                                                                                                                                                                                                                                                    'value'    => $currency,
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID_FRAUD . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ], [
                                                                                                                                                                                                                                                            '`currency`' => [
                                                                                                                                                                                                                                                                'value'    => $currency,
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED_FRAUD . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                   '`remittion`' => [
                                                                                                                                                                                                                                                                       'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], [
                                                                                                                                                                                                                                                                   '`currency`' => [
                                                                                                                                                                                                                                                                       'value'    => $currency,
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID_SETTLED . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                      'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID_SETTLED . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                              '`remittion`' => [
                                                                                                                                                                                                                                                                  'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], [
                                                                                                                                                                                                                                                              '`currency`' => [
                                                                                                                                                                                                                                                                  'value'    => $currency,
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED_SETTLED . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                                         'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], [
                                                                                                                                                                                                                                                                     '`currency`' => [
                                                                                                                                                                                                                                                                         'value'    => $currency,
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                                         'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], [
                                                                                                                                                                                                                                                                     '`currency`' => [
                                                                                                                                                                                                                                                                         'value'    => $currency,
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                 '`remittion`' => [
                                                                                                                                                                                                                                                                     'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                     'operator' => '='
                                                                                                                                                                                                                                                                 ]
                                                                                                                                                                                                                                                             ], [
                                                                                                                                                                                                                                                                 '`currency`' => [
                                                                                                                                                                                                                                                                     'value'    => $currency,
                                                                                                                                                                                                                                                                     'operator' => '='
                                                                                                                                                                                                                                                                 ]
                                                                                                                                                                                                                                                             ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED_DISCOUNTED . " $currency</label>", $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                        '`remittion`' => [
                                                                                                                                                                                                                                                                            'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                                    ], [
                                                                                                                                                                                                                                                                        '`currency`' => [
                                                                                                                                                                                                                                                                            'value'    => $currency,
                                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                                    ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID_DISCOUNTED_AMOUNT . " $currency</label>", $invoiceHandler->getSumByField('`discount_amount`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                                          'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID_DISCOUNTED_AMOUNT . " $currency</label>", $invoiceHandler->getSumByField('`discount_amount`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                                      'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED_DISCOUNTED_AMOUNT . " $currency</label>", $invoiceHandler->getSumByField('`discount_amount`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                                                             'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_UNPAID_DONATED . '</label>', $invoiceHandler->getSumByField('`grand`', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                        '`donation`' => [
                                                                                                                                                                                                                                                            'value'    => true,
                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_PAID_DONATED . '</label>', $invoiceHandler->getSumByField('`grand`', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                    '`donation`' => [
                                                                                                                                                                                                                                                        'value'    => true,
                                                                                                                                                                                                                                                        'operator' => '='
                                                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                                                ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_SUM_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_SUMARE_CANCELLED_DONATED . '</label>', $invoiceHandler->getSumByField('`grand`', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                           '`donation`' => [
                                                                                                                                                                                                                                                               'value'    => true,
                                                                                                                                                                                                                                                               'operator' => '='
                                                                                                                                                                                                                                                           ]
                                                                                                                                                                                                                                                       ])), 'Green');
                }

                if ($invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge([
                                                                                                '`currency`' => [
                                                                                                    'value'    => $currency,
                                                                                                    'operator' => '='
                                                                                                ]
                                                                                            ], $part)) != '0.00'
                    || $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge([
                                                                                                 '`currency`' => [
                                                                                                     'value'    => $currency,
                                                                                                     'operator' => '='
                                                                                                 ]
                                                                                             ], $part)) != '0.00'
                    || $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge([
                                                                                                   '`currency`' => [
                                                                                                       'value'    => $currency,
                                                                                                       'operator' => '='
                                                                                                   ]
                                                                                               ], $part)) != '0.00') {
                    $adminObject->addInfoBox(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency));
                    if ($part['`created`']['value'] != 0) {
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_FROM . '</label>', date(_DATESTRING, time()), 'Red');
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TO . '</label>', date(_DATESTRING, $part['`created`']['value']), 'Red');
                    }
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_UNPAID . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                        '`currency`' => [
                                                                                                                                                                                                                                                            'value'    => $currency,
                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                    ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_PAID . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                    '`currency`' => [
                                                                                                                                                                                                                                                        'value'    => $currency,
                                                                                                                                                                                                                                                        'operator' => '='
                                                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                                                ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_CANCELLED . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                           '`currency`' => [
                                                                                                                                                                                                                                                               'value'    => $currency,
                                                                                                                                                                                                                                                               'operator' => '='
                                                                                                                                                                                                                                                           ]
                                                                                                                                                                                                                                                       ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_UNPAID_NONE . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                             '`remittion`' => [
                                                                                                                                                                                                                                                                 'value'    => 'NONE',
                                                                                                                                                                                                                                                                 'operator' => '='
                                                                                                                                                                                                                                                             ]
                                                                                                                                                                                                                                                         ], [
                                                                                                                                                                                                                                                             '`currency`' => [
                                                                                                                                                                                                                                                                 'value'    => $currency,
                                                                                                                                                                                                                                                                 'operator' => '='
                                                                                                                                                                                                                                                             ]
                                                                                                                                                                                                                                                         ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_PAID_NONE . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                             'value'    => 'NONE',
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_CANCELLED_NONE . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                '`remittion`' => [
                                                                                                                                                                                                                                                                    'value'    => 'NONE',
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], [
                                                                                                                                                                                                                                                                '`currency`' => [
                                                                                                                                                                                                                                                                    'value'    => $currency,
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_UNPAID_COLLECT . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                '`remittion`' => [
                                                                                                                                                                                                                                                                    'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], [
                                                                                                                                                                                                                                                                '`currency`' => [
                                                                                                                                                                                                                                                                    'value'    => $currency,
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_PAID_COLLECT . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ], [
                                                                                                                                                                                                                                                            '`currency`' => [
                                                                                                                                                                                                                                                                'value'    => $currency,
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_CANCELLED_COLLECT . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                   '`remittion`' => [
                                                                                                                                                                                                                                                                       'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], [
                                                                                                                                                                                                                                                                   '`currency`' => [
                                                                                                                                                                                                                                                                       'value'    => $currency,
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_UNPAID_FRAUD . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                              '`remittion`' => [
                                                                                                                                                                                                                                                                  'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], [
                                                                                                                                                                                                                                                              '`currency`' => [
                                                                                                                                                                                                                                                                  'value'    => $currency,
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_PAID_FRAUD . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                          '`remittion`' => [
                                                                                                                                                                                                                                                              'value'    => 'FRAUD',
                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                      ], [
                                                                                                                                                                                                                                                          '`currency`' => [
                                                                                                                                                                                                                                                              'value'    => $currency,
                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                      ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_CANCELLED_FRAUD . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                 '`remittion`' => [
                                                                                                                                                                                                                                                                     'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                     'operator' => '='
                                                                                                                                                                                                                                                                 ]
                                                                                                                                                                                                                                                             ], [
                                                                                                                                                                                                                                                                 '`currency`' => [
                                                                                                                                                                                                                                                                     'value'    => $currency,
                                                                                                                                                                                                                                                                     'operator' => '='
                                                                                                                                                                                                                                                                 ]
                                                                                                                                                                                                                                                             ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_UNPAID_SETTLED . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                '`remittion`' => [
                                                                                                                                                                                                                                                                    'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], [
                                                                                                                                                                                                                                                                '`currency`' => [
                                                                                                                                                                                                                                                                    'value'    => $currency,
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_PAID_SETTLED . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ], [
                                                                                                                                                                                                                                                            '`currency`' => [
                                                                                                                                                                                                                                                                'value'    => $currency,
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_CANCELLED_SETTLED . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                   '`remittion`' => [
                                                                                                                                                                                                                                                                       'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], [
                                                                                                                                                                                                                                                                   '`currency`' => [
                                                                                                                                                                                                                                                                       'value'    => $currency,
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_UNPAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                   '`remittion`' => [
                                                                                                                                                                                                                                                                       'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], [
                                                                                                                                                                                                                                                                   '`currency`' => [
                                                                                                                                                                                                                                                                       'value'    => $currency,
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_PAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                               '`remittion`' => [
                                                                                                                                                                                                                                                                   'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], [
                                                                                                                                                                                                                                                               '`currency`' => [
                                                                                                                                                                                                                                                                   'value'    => $currency,
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_CANCELLED_DISCOUNTED . " $currency</label>", $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                          'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_UNPAID_DONATED . '</label>', $invoiceHandler->getSumByField('`tax`', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                      '`donation`' => [
                                                                                                                                                                                                                                                          'value'    => true,
                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                  ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_PAID_DONATED . '</label>', $invoiceHandler->getSumByField('`tax`', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                  '`donation`' => [
                                                                                                                                                                                                                                                      'value'    => true,
                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                              ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_TAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TAXARE_CANCELLED_DONATED . '</label>', $invoiceHandler->getSumByField('`tax`', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                         '`donation`' => [
                                                                                                                                                                                                                                                             'value'    => true,
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ])), 'Green');
                }

                if ($invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                      '`currency`' => [
                                                                                                          'value'    => $currency,
                                                                                                          'operator' => '='
                                                                                                      ]
                                                                                                  ], $part)) != '0.00'
                    || $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                       '`currency`' => [
                                                                                                           'value'    => $currency,
                                                                                                           'operator' => '='
                                                                                                       ]
                                                                                                   ], $part)) != '0.00'
                    || $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                         '`currency`' => [
                                                                                                             'value'    => $currency,
                                                                                                             'operator' => '='
                                                                                                         ]
                                                                                                     ], $part)) != '0.00') {
                    $adminObject->addInfoBox(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency));
                    if ($part['`created`']['value'] != 0) {
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_FROM . '</label>', date(_DATESTRING, time()), 'Red');
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TO . '</label>', date(_DATESTRING, $part['`created`']['value']), 'Red');
                    }
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                              '`currency`' => [
                                                                                                                                                                                                                                                                  'value'    => $currency,
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                          '`currency`' => [
                                                                                                                                                                                                                                                              'value'    => $currency,
                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                      ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                 '`currency`' => [
                                                                                                                                                                                                                                                                     'value'    => $currency,
                                                                                                                                                                                                                                                                     'operator' => '='
                                                                                                                                                                                                                                                                 ]
                                                                                                                                                                                                                                                             ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID_NONE . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                   '`remittion`' => [
                                                                                                                                                                                                                                                                       'value'    => 'NONE',
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], [
                                                                                                                                                                                                                                                                   '`currency`' => [
                                                                                                                                                                                                                                                                       'value'    => $currency,
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID_NONE . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                               '`remittion`' => [
                                                                                                                                                                                                                                                                   'value'    => 'NONE',
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], [
                                                                                                                                                                                                                                                               '`currency`' => [
                                                                                                                                                                                                                                                                   'value'    => $currency,
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED_NONE . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                          'value'    => 'NONE',
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID_COLLECT . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                          'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID_COLLECT . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                      'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED_COLLECT . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                                             'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID_FRAUD . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                    '`remittion`' => [
                                                                                                                                                                                                                                                                        'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                        'operator' => '='
                                                                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                                                                ], [
                                                                                                                                                                                                                                                                    '`currency`' => [
                                                                                                                                                                                                                                                                        'value'    => $currency,
                                                                                                                                                                                                                                                                        'operator' => '='
                                                                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                                                                ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID_FRAUD . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                '`remittion`' => [
                                                                                                                                                                                                                                                                    'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], [
                                                                                                                                                                                                                                                                '`currency`' => [
                                                                                                                                                                                                                                                                    'value'    => $currency,
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED_FRAUD . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                       '`remittion`' => [
                                                                                                                                                                                                                                                                           'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                           'operator' => '='
                                                                                                                                                                                                                                                                       ]
                                                                                                                                                                                                                                                                   ], [
                                                                                                                                                                                                                                                                       '`currency`' => [
                                                                                                                                                                                                                                                                           'value'    => $currency,
                                                                                                                                                                                                                                                                           'operator' => '='
                                                                                                                                                                                                                                                                       ]
                                                                                                                                                                                                                                                                   ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID_SETTLED . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                          'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID_SETTLED . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                      'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED_SETTLED . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                                             'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                                             'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                                         'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], [
                                                                                                                                                                                                                                                                     '`currency`' => [
                                                                                                                                                                                                                                                                         'value'    => $currency,
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED_DISCOUNTED . " $currency</label>", $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                                'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                                        ], [
                                                                                                                                                                                                                                                                            '`currency`' => [
                                                                                                                                                                                                                                                                                'value'    => $currency,
                                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                                        ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID_DISCOUNTED_AMOUNT . " $currency</label>", $invoiceHandler->getAverageByField('`discount_amount`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                                          '`remittion`' => [
                                                                                                                                                                                                                                                                                              'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                                                      ], [
                                                                                                                                                                                                                                                                                          '`currency`' => [
                                                                                                                                                                                                                                                                                              'value'    => $currency,
                                                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                                                      ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID_DISCOUNTED_AMOUNT . " $currency</label>", $invoiceHandler->getAverageByField('`discount_amount`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                                          'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(
                        sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency),
                        '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED_DISCOUNTED_AMOUNT . " $currency</label>",
                        $invoiceHandler->getAverageByField('`discount_amount`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                                             '`remittion`' => [
                                                                                                                                                                                                                                                                                                 'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                                 'operator' => '='
                                                                                                                                                                                                                                                                                             ]
                                                                                                                ], [
                                                                                                                                                                                                                                                                                             '`currency`' => [
                                                                                                                                                                                                                                                                                                 'value'    => $currency,
                                                                                                                                                                                                                                                                                                 'operator' => '='
                                                                                                                                                                                                                                                                                             ]
                                                                                                                ], $part)),
                                                 'Green'
                    );
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_UNPAID_DONATED . '</label>', $invoiceHandler->getAverageByField('`grand`', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`donation`' => [
                                                                                                                                                                                                                                                                'value'    => true,
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_PAID_DONATED . '</label>', $invoiceHandler->getAverageByField('`grand`', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                        '`donation`' => [
                                                                                                                                                                                                                                                            'value'    => true,
                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_AVG_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_AVGARE_CANCELLED_DONATED . '</label>', $invoiceHandler->getAverageByField('`grand`', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                               '`donation`' => [
                                                                                                                                                                                                                                                                   'value'    => true,
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ])), 'Green');
                }
                if ($invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                      '`currency`' => [
                                                                                                          'value'    => $currency,
                                                                                                          'operator' => '='
                                                                                                      ]
                                                                                                  ], $part)) != '0.00'
                    || $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                       '`currency`' => [
                                                                                                           'value'    => $currency,
                                                                                                           'operator' => '='
                                                                                                       ]
                                                                                                   ], $part)) != '0.00'
                    || $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                         '`currency`' => [
                                                                                                             'value'    => $currency,
                                                                                                             'operator' => '='
                                                                                                         ]
                                                                                                     ], $part)) != '0.00') {
                    $adminObject->addInfoBox(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency));
                    if ($part['`created`']['value'] != 0) {
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_FROM . '</label>', date(_DATESTRING, time()), 'Red');
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TO . '</label>', date(_DATESTRING, $part['`created`']['value']), 'Red');
                    }
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                              '`currency`' => [
                                                                                                                                                                                                                                                                  'value'    => $currency,
                                                                                                                                                                                                                                                                  'operator' => '='
                                                                                                                                                                                                                                                              ]
                                                                                                                                                                                                                                                          ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                          '`currency`' => [
                                                                                                                                                                                                                                                              'value'    => $currency,
                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                      ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                 '`currency`' => [
                                                                                                                                                                                                                                                                     'value'    => $currency,
                                                                                                                                                                                                                                                                     'operator' => '='
                                                                                                                                                                                                                                                                 ]
                                                                                                                                                                                                                                                             ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID_NONE . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                   '`remittion`' => [
                                                                                                                                                                                                                                                                       'value'    => 'NONE',
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], [
                                                                                                                                                                                                                                                                   '`currency`' => [
                                                                                                                                                                                                                                                                       'value'    => $currency,
                                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                                               ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID_NONE . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                               '`remittion`' => [
                                                                                                                                                                                                                                                                   'value'    => 'NONE',
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], [
                                                                                                                                                                                                                                                               '`currency`' => [
                                                                                                                                                                                                                                                                   'value'    => $currency,
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED_NONE . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                          'value'    => 'NONE',
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID_COLLECT . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                          'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID_COLLECT . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                      'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED_COLLECT . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                                             'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID_FRAUD . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                    '`remittion`' => [
                                                                                                                                                                                                                                                                        'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                        'operator' => '='
                                                                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                                                                ], [
                                                                                                                                                                                                                                                                    '`currency`' => [
                                                                                                                                                                                                                                                                        'value'    => $currency,
                                                                                                                                                                                                                                                                        'operator' => '='
                                                                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                                                                ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID_FRAUD . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                '`remittion`' => [
                                                                                                                                                                                                                                                                    'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], [
                                                                                                                                                                                                                                                                '`currency`' => [
                                                                                                                                                                                                                                                                    'value'    => $currency,
                                                                                                                                                                                                                                                                    'operator' => '='
                                                                                                                                                                                                                                                                ]
                                                                                                                                                                                                                                                            ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED_FRAUD . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                       '`remittion`' => [
                                                                                                                                                                                                                                                                           'value'    => 'FRAUD',
                                                                                                                                                                                                                                                                           'operator' => '='
                                                                                                                                                                                                                                                                       ]
                                                                                                                                                                                                                                                                   ], [
                                                                                                                                                                                                                                                                       '`currency`' => [
                                                                                                                                                                                                                                                                           'value'    => $currency,
                                                                                                                                                                                                                                                                           'operator' => '='
                                                                                                                                                                                                                                                                       ]
                                                                                                                                                                                                                                                                   ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID_SETTLED . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                          'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID_SETTLED . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                                      'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], [
                                                                                                                                                                                                                                                                  '`currency`' => [
                                                                                                                                                                                                                                                                      'value'    => $currency,
                                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                                              ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED_SETTLED . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                                             'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                                             'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], [
                                                                                                                                                                                                                                                                         '`currency`' => [
                                                                                                                                                                                                                                                                             'value'    => $currency,
                                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                                     ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID_DISCOUNTED . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                                         'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], [
                                                                                                                                                                                                                                                                     '`currency`' => [
                                                                                                                                                                                                                                                                         'value'    => $currency,
                                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                                 ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED_DISCOUNTED . " $currency</label>", $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                                'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                                        ], [
                                                                                                                                                                                                                                                                            '`currency`' => [
                                                                                                                                                                                                                                                                                'value'    => $currency,
                                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                                        ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID_DISCOUNTED_AMOUNT . " $currency</label>", $invoiceHandler->getMaximumByField('`discount_amount`', '`mode`', 'UNPAID', array_merge([
                                                                                                                                                                                                                                                                                          '`remittion`' => [
                                                                                                                                                                                                                                                                                              'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                                                      ], [
                                                                                                                                                                                                                                                                                          '`currency`' => [
                                                                                                                                                                                                                                                                                              'value'    => $currency,
                                                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                                                      ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID_DISCOUNTED_AMOUNT . " $currency</label>", $invoiceHandler->getMaximumByField('`discount_amount`', '`mode`', 'PAID', array_merge([
                                                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                                                          'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                                  ], [
                                                                                                                                                                                                                                                                                      '`currency`' => [
                                                                                                                                                                                                                                                                                          'value'    => $currency,
                                                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                                                  ], $part)), 'Green');
                    $adminObject->addInfoBoxLine(
                        sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency),
                        '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED_DISCOUNTED_AMOUNT . " $currency</label>",
                        $invoiceHandler->getMaximumByField('`discount_amount`', '`mode`', 'CANCEL', array_merge([
                                                                                                                                                                                                                                                                                             '`remittion`' => [
                                                                                                                                                                                                                                                                                                 'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                                                 'operator' => '='
                                                                                                                                                                                                                                                                                             ]
                                                                                                                ], [
                                                                                                                                                                                                                                                                                             '`currency`' => [
                                                                                                                                                                                                                                                                                                 'value'    => $currency,
                                                                                                                                                                                                                                                                                                 'operator' => '='
                                                                                                                                                                                                                                                                                             ]
                                                                                                                ], $part)),
                                                 'Green'
                    );
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_UNPAID_DONATED . '</label>', $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`donation`' => [
                                                                                                                                                                                                                                                                'value'    => true,
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_PAID_DONATED . '</label>', $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                        '`donation`' => [
                                                                                                                                                                                                                                                            'value'    => true,
                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_MAX_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_MAXARE_CANCELLED_DONATED . '</label>', $invoiceHandler->getMaximumByField('`grand`', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                               '`donation`' => [
                                                                                                                                                                                                                                                                   'value'    => true,
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ])), 'Green');
                }

                if ($invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge([
                                                                                              '`currency`' => [
                                                                                                  'value'    => $currency,
                                                                                                  'operator' => '='
                                                                                              ]
                                                                                          ], $part)) != '0'
                    || $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge([
                                                                                               '`currency`' => [
                                                                                                   'value'    => $currency,
                                                                                                   'operator' => '='
                                                                                               ]
                                                                                           ], $part)) != '0'
                    || $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge([
                                                                                                 '`currency`' => [
                                                                                                     'value'    => $currency,
                                                                                                     'operator' => '='
                                                                                                 ]
                                                                                             ], $part)) != '0') {
                    $adminObject->addInfoBox(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency));
                    if ($part['`created`']['value'] != 0) {
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_FROM . '</label>', date(_DATESTRING, time()), 'Red');
                        $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_TO . '</label>', date(_DATESTRING, $part['`created`']['value']), 'Red');
                    }
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_NONE . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                      '`remittion`' => [
                                                                                                                                                                                                                                                          'value'    => 'NONE',
                                                                                                                                                                                                                                                          'operator' => '='
                                                                                                                                                                                                                                                      ]
                                                                                                                                                                                                                                                  ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_NONE . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                  '`remittion`' => [
                                                                                                                                                                                                                                                      'value'    => 'NONE',
                                                                                                                                                                                                                                                      'operator' => '='
                                                                                                                                                                                                                                                  ]
                                                                                                                                                                                                                                              ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_NONE . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                             'value'    => 'NONE',
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_PENDING . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                             'value'    => 'PENDING',
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_PENDING . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                         'value'    => 'PENDING',
                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                 ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_PENDING . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'PENDING',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_NOTICE . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                        '`remittion`' => [
                                                                                                                                                                                                                                                            'value'    => 'NOTICE',
                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_NOTICE . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                    '`remittion`' => [
                                                                                                                                                                                                                                                        'value'    => 'NOTICE',
                                                                                                                                                                                                                                                        'operator' => '='
                                                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                                                ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_NOTICE . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                           '`remittion`' => [
                                                                                                                                                                                                                                                               'value'    => 'NOTICE',
                                                                                                                                                                                                                                                               'operator' => '='
                                                                                                                                                                                                                                                           ]
                                                                                                                                                                                                                                                       ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_COLLECT . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                             'value'    => 'COLLECT',
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_COLLECT . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                         'value'    => 'COLLECT',
                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                 ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_COLLECT . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'COLLECT',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_FRAUD . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                       '`remittion`' => [
                                                                                                                                                                                                                                                           'value'    => 'FRAUD',
                                                                                                                                                                                                                                                           'operator' => '='
                                                                                                                                                                                                                                                       ]
                                                                                                                                                                                                                                                   ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_FRAUD . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                   '`remittion`' => [
                                                                                                                                                                                                                                                       'value'    => 'FRAUD',
                                                                                                                                                                                                                                                       'operator' => '='
                                                                                                                                                                                                                                                   ]
                                                                                                                                                                                                                                               ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_FRAUD . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                          '`remittion`' => [
                                                                                                                                                                                                                                                              'value'    => 'FRAUD',
                                                                                                                                                                                                                                                              'operator' => '='
                                                                                                                                                                                                                                                          ]
                                                                                                                                                                                                                                                      ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_SETTLED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                             'value'    => 'SETTLED',
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_SETTLED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                         'value'    => 'SETTLED',
                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                 ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_SETTLED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_SETTLED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                         '`remittion`' => [
                                                                                                                                                                                                                                                             'value'    => 'SETTLED',
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_SETTLED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                     '`remittion`' => [
                                                                                                                                                                                                                                                         'value'    => 'SETTLED',
                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                 ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_SETTLED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'SETTLED',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_DISCOUNTED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`remittion`' => [
                                                                                                                                                                                                                                                                'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_DISCOUNTED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                        '`remittion`' => [
                                                                                                                                                                                                                                                            'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                            'operator' => '='
                                                                                                                                                                                                                                                        ]
                                                                                                                                                                                                                                                    ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_DISCOUNTED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                               '`remittion`' => [
                                                                                                                                                                                                                                                                   'value'    => 'DISCOUNTED',
                                                                                                                                                                                                                                                                   'operator' => '='
                                                                                                                                                                                                                                                               ]
                                                                                                                                                                                                                                                           ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_UNPAID_DONATED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'UNPAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                         '`donation`' => [
                                                                                                                                                                                                                                                             'value'    => true,
                                                                                                                                                                                                                                                             'operator' => '='
                                                                                                                                                                                                                                                         ]
                                                                                                                                                                                                                                                     ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_PAID_DONATED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'PAID', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                     '`donation`' => [
                                                                                                                                                                                                                                                         'value'    => true,
                                                                                                                                                                                                                                                         'operator' => '='
                                                                                                                                                                                                                                                     ]
                                                                                                                                                                                                                                                 ])), 'Green');
                    $adminObject->addInfoBoxLine(sprintf(_XPY_AM_INVOICES_COUNTS_TOTAL, $title, $currency), '<label>' . _XPY_AM_INVOICES_THEREARE_CANCELLED_DONATED . '</label>', $invoiceHandler->getCountByField('*', '`mode`', 'CANCEL', array_merge($part, [
                        '`currency`' => [
                            'value'    => $currency,
                            'operator' => '='
                        ]
                    ], [
                                                                                                                                                                                                                                                            '`donation`' => [
                                                                                                                                                                                                                                                                'value'    => true,
                                                                                                                                                                                                                                                                'operator' => '='
                                                                                                                                                                                                                                                            ]
                                                                                                                                                                                                                                                        ])), 'Green');
                }
            }
        }

        $adminObject->displayIndex();

        break;
    case 'about':
        //loadModuleAdminMenu(8);
        $paypalitemno = 'XPAYMENTABOUT100';
        $aboutAdmin   = \Xmf\Module\Admin::getInstance();
        $about        = $aboutAdmin->displayAbout($paypalitemno, false);
        $donationform = [
            0   => '<form name="donation" id="donation" action="http://www.chronolabs.com.au/modules/xpayment/" method="post" onsubmit="return xoopsFormValidate_donation();">',
            1   => '<table class="outer" cellspacing="1" width="100%"><tbody><tr><th colspan="2">'
                   . constant('_XPY_AM_XPAYMENT_ABOUT_MAKEDONATE')
                   . '</th></tr><tr align="left" valign="top"><td class="head"><div class="xoops-form-element-caption-required"><span class="caption-text">Donation Amount</span><span class="caption-marker">*</span></div></td><td class="even"><select size="1" name="item[A][amount]" id="item[A][amount]" title="Donation Amount"><option value="5">5.00 AUD</option><option value="10">10.00 AUD</option><option value="20">20.00 AUD</option><option value="40">40.00 AUD</option><option value="60">60.00 AUD</option><option value="80">80.00 AUD</option><option value="90">90.00 AUD</option><option value="100">100.00 AUD</option><option value="200">200.00 AUD</option></select></td></tr><tr align="left" valign="top"><td class="head"></td><td class="even"><input class="formButton" name="submit" id="submit" value="'
                   . _SUBMIT
                   . '" title="'
                   . _SUBMIT
                   . '" type="submit"></td></tr></tbody></table>',
            2   => '<input name="op" id="op" value="createinvoice" type="hidden"><input name="plugin" id="plugin" value="donations" type="hidden"><input name="donation" id="donation" value="1" type="hidden"><input name="drawfor" id="drawfor" value="Chronolabs Co-Operative" type="hidden"><input name="drawto" id="drawto" value="%s" type="hidden"><input name="drawto_email" id="drawto_email" value="%s" type="hidden"><input name="key" id="key" value="%s" type="hidden"><input name="currency" id="currency" value="AUD" type="hidden"><input name="weight_unit" id="weight_unit" value="kgs" type="hidden"><input name="item[A][cat]" id="item[A][cat]" value="XDN%s" type="hidden"><input name="item[A][name]" id="item[A][name]" value="Donation for %s" type="hidden"><input name="item[A][quantity]" id="item[A][quantity]" value="1" type="hidden"><input name="item[A][shipping]" id="item[A][shipping]" value="0" type="hidden"><input name="item[A][handling]" id="item[A][handling]" value="0" type="hidden"><input name="item[A][weight]" id="item[A][weight]" value="0" type="hidden"><input name="item[A][tax]" id="item[A][tax]" value="0" type="hidden"><input name="return" id="return" value="http://www.chronolabs.com.au/modules/donations/success.php" type="hidden"><input name="cancel" id="cancel" value="http://www.chronolabs.com.au/modules/donations/success.php" type="hidden"></form>',
            'D' => '',
            3   => '',
            4   => '<!-- Start Form Validation JavaScript //-->
<script type="text/javascript">
<!--//
function xoopsFormValidate_donation() { var myform = window.document.donation;
var hasSelected = false; var selectBox = myform.item[A][amount];for (i = 0; i < selectBox.options.length; i++) { if (selectBox.options[i].selected === true && selectBox.options[i].value != \'\') { hasSelected = true; break; } }if (!hasSelected) { window.alert("Please enter Donation Amount"); selectBox.focus(); return false; }return true;
}
//--></script>
<!-- End Form Validation JavaScript //-->'
        ];
        $paypalform   = [
            0 => '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">',
            1 => '<input name="cmd" value="_s-xclick" type="hidden">',
            2 => '<input name="hosted_button_id" value="%s" type="hidden">',
            3 => '<img alt="" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" height="1" border="0" width="1">',
            4 => '<input src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" border="0" type="image">',
            5 => '</form>'
        ];
        for ($key = 0; $key <= 4; ++$key) {
            switch ($key) {
                case 2:
                    $donationform[$key] = sprintf(
                        $donationform[$key],
                                                  $GLOBALS['xoopsConfig']['sitename'] . ' - ' . (strlen($GLOBALS['xoopsUser']->getVar('name')) > 0 ? $GLOBALS['xoopsUser']->getVar('name') . ' [' . $GLOBALS['xoopsUser']->getVar('uname') . ']' : $GLOBALS['xoopsUser']->getVar('uname')),
                                                  $GLOBALS['xoopsUser']->getVar('email'),
                        XOOPS_LICENSE_KEY,
                        strtoupper($GLOBALS['xpaymentModule']->getVar('dirname')),
                        strtoupper($GLOBALS['xpaymentModule']->getVar('dirname')) . ' ' . $GLOBALS['xpaymentModule']->getVar('name')
                    );
                    break;
            }
        }

        $istart = strpos($about, $paypalform[0], 1);
        $iend   = strpos($about, $paypalform[5], $istart + 1) + strlen($paypalform[5]) - 1;
        echo(substr($about, 0, $istart - 1));
        echo implode("\n", $donationform);
        echo(substr($about, $iend + 1, strlen($about) - $iend - 1));
        break;
}

xoops_cp_footer();
