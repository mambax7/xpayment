<?php
function PaidDonationsHook($invoice)
{
    $donationsHandler = xoops_getModuleHandler('donations', 'donations');
    $donation         = $donationsHandler->create();
    $donation->setVar('uid', $invoice->getVar('user_uid'));
    $donation->setVar('amount', $invoice->getVar('grand'));
    $donation->setVar('currency', $invoice->getVar('currency'));
    $donation->setVar('realname', $invoice->getVar('drawto'));
    $donation->setVar('email', $invoice->getVar('drawto_email'));
    $donation->setVar('iid', $invoice->getVar('iid'));

    $invoice_transactionHandler = xoops_getModuleHandler('invoice_transactions', 'xpayment');
    $criteria                   = new Criteria('iid', $invoice->getVar('iid'));
    $criteria->setSort('`date`');
    $criteria->setOrder('DESC');
    $criteria->setLimit(1);

    $trans = $invoice_transactionHandler->getObjects($criteria);
    if (is_object($trans[0])) {
        $donation->setVar('state', $trans[0]->getVar('state'));
        $donation->setVar('country', $trans[0]->getVar('country'));
    }

    @$donationsHandler->insert($donation, true);

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $configHandler = xoops_getHandler('config');
    $xoMod         = $moduleHandler->getByDirname('donations');
    $xoConfig      = $configHandler->getConfigList($xoMod->getVar('mid'));

    if ($xoConfig['change_group'] && $donation->getVar('uid') <> 0) {
        $memberHandler = xoops_getHandler('member');
        $memberHandler->addUserToGroup($xoConfig['donation_group'], $donation->getVar('uid'));
    }

    $xoMod = $moduleHandler->getByDirname('profile');
    if (is_object($xoMod) && $donation->getVar('uid') <> 0) {
        $profileHandler = xoops_getModuleHandler('profile', 'profile');
        $profile        = $profileHandler->get($donation->getVar('uid'));
        $profile->setVar($xoConfig['profile_field'], time());
        $profileHandler->insert($profile);
    }

    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PaidXpaymentHook($invoice);
}

function UnpaidDonationsHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return UnpaidXpaymentHook($invoice);
}

function CancelDonationsHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return CancelXpaymentHook($invoice);
}
