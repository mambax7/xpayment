<?php

function PaidAdslightlistingHook($invoice)
{
    $sql = 'update ' . $GLOBALS['xoopsDB']->prefix('adslight_listing') . ' set `valid` = \'Yes\' where `lid`= "' . $invoice->getVar('key') . '"';
    $GLOBALS['xoopsDB']->queryF($sql);
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PaidXpaymentHook($invoice);
}

function UnpaidAdslightlistingHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return UnpaidXpaymentHook($invoice);
}

function CancelAdslightlistingHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return CancelXpaymentHook($invoice);
}
