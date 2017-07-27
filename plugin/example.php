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
 * @param $invoice
 * @return bool
 */

// for /modules/xpayment/plugin/example.php
// This will only send the xpayment 1.28 email notifications. When the plugin 'example' is specified in the
// Shopping cart form.

function PaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PaidXpaymentHook($invoice);
}

function UnpaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return UnpaidXpaymentHook($invoice);
}

function CancelExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return CancelXpaymentHook($invoice);
}

function NonePaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NonePaidXpaymentHook($invoice);
}

function NoneUnpaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoneUnpaidXpaymentHook($invoice);
}

function NoneCancelExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoneCancelXpaymentHook($invoice);
}

function PendingPaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PendingPaidXpaymentHook($invoice);
}

function PendingUnpaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PendingUnpaidXpaymentHook($invoice);
}

function PendingCancelExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PendingCancelXpaymentHook($invoice);
}

function NoticePaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoticePaidXpaymentHook($invoice);
}

function NoticeUnpaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoticeUnpaidXpaymentHook($invoice);
}

function NoticeCancelExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoticeCancelXpaymentHook($invoice);
}

function CollectPaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return CollectPaidXpaymentHook($invoice);
}

function CollectUnpaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return CollectUnpaidXpaymentHook($invoice);
}

function CollectCancelExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return CollectCancelXpaymentHook($invoice);
}

function FraudPaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return FraudPaidXpaymentHook($invoice);
}

function FraudUnpaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return FraudUnpaidXpaymentHook($invoice);
}

function FraudCancelExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return FraudCancelXpaymentHook($invoice);
}

function SettledPaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return SettledPaidXpaymentHook($invoice);
}

function SettledUnpaidExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return SettledUnpaidXpaymentHook($invoice);
}

function SettledCancelExampleHook($invoice)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return SettledCancelXpaymentHook($invoice);
}

// Individual Items Hooks - Real Time Transactional email and functions
function PurchasedPaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PurchasedPaidXpaymentItemHook($invoice, $item);
}

function PurchasedUnpaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PurchasedUnpaidXpaymentItemHook($invoice, $item);
}

function PurchasedCancelExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PurchasedCancelXpaymentItemHook($invoice, $item);
}

function RefundedPaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return RefundedPaidXpaymentItemHook($invoice, $item);
}

function RefundedUnpaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return RefundedUnpaidXpaymentItemHook($invoice, $item);
}

function RefundedCancelExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return RefundedCancelXpaymentItemHook($invoice, $item);
}

function UndeliveredPaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return UndeliveredPaidXpaymentItemHook($invoice, $item);
}

function UndeliveredUnpaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return UndeliveredUnpaidXpaymentItemHook($invoice, $item);
}

function UndeliveredCancelExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return UndeliveredCancelXpaymentItemHook($invoice, $item);
}

function DamagedPaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return DamagedPaidXpaymentItemHook($invoice, $item);
}

function DamagedUnpaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return DamagedUnpaidXpaymentItemHook($invoice, $item);
}

function DamagedCancelExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return DamagedCancelXpaymentItemHook($invoice, $item);
}

function ExpressPaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return ExpressPaidXpaymentItemHook($invoice, $item);
}

function ExpressUnpaidExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return ExpressUnpaidXpaymentItemHook($invoice, $item);
}

function ExpressCancelExampleItemHook($invoice, $item)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return ExpressCancelXpaymentItemHook($invoice, $item);
}

// Transaction Hooks - Real Time Transactional email and functions
function PaymentPaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PaymentPaidXpaymentTransactionHook($invoice, $transaction);
}

function PaymentUnpaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PaymentUnpaidXpaymentTransactionHook($invoice, $transaction);
}

function PaymentCancelExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PaymentCancelXpaymentTransactionHook($invoice, $transaction);
}

function RefundPaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return RefundPaidXpaymentTransactionHook($invoice, $transaction);
}

function RefundUnpaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return RefundUnpaidXpaymentTransactionHook($invoice, $transaction);
}

function RefundCancelExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return RefundCancelXpaymentTransactionHook($invoice, $transaction);
}

function PendingPaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PendingPaidXpaymentTransactionHook($invoice, $transaction);
}

function PendingUnpaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PendingUnpaidXpaymentTransactionHook($invoice, $transaction);
}

function PendingCancelExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return PendingCancelXpaymentTransactionHook($invoice, $transaction);
}

function NoticePaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoticePaidXpaymentTransactionHook($invoice, $transaction);
}

function NoticeUnpaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoticeUnpaidXpaymentTransactionHook($invoice, $transaction);
}

function NoticeCancelExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return NoticeCancelXpaymentTransactionHook($invoice, $transaction);
}

function OtherPaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return OtherPaidXpaymentTransactionHook($invoice, $transaction);
}

function OtherUnpaidExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return OtherUnpaidXpaymentTransactionHook($invoice);
}

function OtherCancelExampleTransactionHook($invoice, $transaction)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/plugin/xpayment.php');

    return OtherCancelXpaymentTransactionHook($invoice);
}
