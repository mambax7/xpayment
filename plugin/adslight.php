<?php

function PaidAdslightHook($invoice)
{
    $sql = 'update ' . $GLOBALS['xoopsDB']->prefix('adslight_listing') . ' set `status` = 2 where `lid`= "' . $invoice->getVar('key') . '"';
    $GLOBALS['xoopsDB']->queryF($sql);
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PaidXpaymentHook($invoice);
}

function UnpaidAdslightHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return UnpaidXpaymentHook($invoice);
}

function CancelAdslightHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return CancelXpaymentHook($invoice);
}
