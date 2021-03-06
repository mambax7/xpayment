<h1><a href=<{$invoice.invurl}>><{$smarty.const._XPY_PDF_MF_INVOICE_H1}> : <{$invoice.invoicenumber}></a></h1>
<p><{$smarty.const._XPY_PDF_MF_INVOICE_P}></p>
<table style='width:100%; clear:both;'>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_INVOICENUMBER}> :</td>
        <td><strong><{$invoice.invoicenumber}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_DRAWNFOR}> :</td>
        <td><strong><{$invoice.drawfor}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_DRAWNTO}> :</td>
        <td><strong><{$invoice.drawto}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_AMOUNT}> :</td>
        <td><strong><{$invoice.amount}> <{$invoice.currency}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_TOTALSHIPPING}> :</td>
        <td><strong><{$invoice.shipping}> <{$invoice.currency}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_TOTALHANDLING}> :</td>
        <td><strong><{$invoice.handling}> <{$invoice.currency}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_TOTALTAX}> :</td>
        <td><strong><{$invoice.tax}> <{$invoice.currency}></strong></td>
    </tr>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_TOTALINTEREST}> :</div>
        <div><strong><{$invoice.interest}> <{$invoice.currency}> (<{$invoice.rate}>%)</strong></div>
    </div>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_CREATED}> :</td>
        <td><strong><{$invoice.created_datetime}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_MODE}> :</td>
        <td><strong><{$invoice.mode}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_ITEMS}> :</td>
        <td><strong><{$invoice.items}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_TOTALWEIGHT}> :</td>
        <td><strong><{$invoice.weight}> <{$invoice.weight_unit}></strong></td>
    </tr>
    <{if $invoice.mode != 'UNPAID'}>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_ACTIONED}> :</td>
            <td><strong><{$invoice.actioned_datetime}></strong></td>
        </tr>
    <{/if}>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_GRANDAMOUNT}> :</td>
        <td><strong><{$invoice.grand}> <{$invoice.currency}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_PAID}> :</td>
        <td><strong><{$invoice.paid}> <{$invoice.currency}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_DUE}> :</td>
        <td><strong><{$invoice.due_datetime}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_COLLECT}> :</td>
        <td><strong><{$invoice.collect_datetime}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_WAIT}> :</td>
        <td><strong><{$invoice.wait_datetime}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_OFFLINE}> :</td>
        <td><strong><{$invoice.offline_datetime}></strong></td>
    </tr>
    <tr>
        <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_DONATION}> :</td>
        <td><strong><{$invoice.donation}></strong></td>
    </tr>
    <{if $invoice.did != 0}>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_DISCOUNTPERCENTILE}> :</td>
            <td><strong><{$invoice.discount}>%</strong></td>
        </tr>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_DISCOUNTAMOUNT}> :</td>
            <td><strong><{$invoice.discount_amount}> <{$invoice.currency}></strong></td>
        </tr>
    <{/if}>
    <{if $invoice.remitted > 0}>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_REMITTION}> :</td>
            <td><strong><{$invoice.remittion}></strong></td>
        </tr>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_REMITTED}> :</td>
            <td><strong><{$invoice.remitted_datetime}></strong></td>
        </tr>
    <{/if}>
</table>
<{if $invoice.reoccurrence > 0}>
    <h2><{$smarty.const._XPY_PDF_MF_REOCCURRENCE_H2}></h2>
    <p><{$smarty.const._XPY_PDF_MF_REOCCURRENCE_P}></p>
    <table>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_REOCCURRENCE}> :</td>
            <td><strong><{$invoice.reoccurrence}></strong></td>
        </tr>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_REOCCURRENCES}> :</td>
            <td><strong><{$invoice.reoccurrences}></strong></td>
        </tr>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_PERIOD}> :</td>
            <td><strong><{$invoice.reoccurrence_period_days}> <{$smarty.const._XPY_PDF_MF_DAYS}></strong></td>
        </tr>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_MF_PREVIOUS}> :</td>
            <td><strong><{$invoice.previous_datetime}></strong></td>
        </tr>
        <tr>
            <td style='width:190px;float:left;'><{$smarty.const._XPY_MF_OCCURRENCE}> :</td>
            <td><strong><{$invoice.occurrence_datetime}></strong></td>
        </tr>
    </table>
    <table>
        <tr class="head">
            <th>&nbsp;</th>
            <th><{$smarty.const._XPY_PDF_MF_OCCURRENCE_PAID_TH}></th>
            <th><{$smarty.const._XPY_PDF_MF_OCCURRENCE_LEFT_TH}></th>
            <th><{$smarty.const._XPY_PDF_MF_OCCURRENCE_TOTAL_TH}></th>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_GRAND}></td>
            <td><{$invoice.occurrence_paid.grand}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.grand}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.grand}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_AMOUNT}></td>
            <td><{$invoice.occurrence_paid.amount}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.amount}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.amount}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_SHIPPING}></td>
            <td><{$invoice.occurrence_paid.shipping}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.shipping}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.shipping}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_HANDLING}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_TAX}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="foot">
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
<{elseif $invoice.reoccurrence == -1}>
    <h2><{$smarty.const._XPY_PDF_MF_REOCCURRENCE_H2}></h2>
    <p><{$smarty.const._XPY_PDF_MF_REOCCURRENCE_P}></p>
    <div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_REOCCURRENCE}> :</div>
            <div><strong><{$smarty.const._XPY_PDF_MF_REOCCURRENCE_ONGOING}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_REOCCURRENCES}> :</div>
            <div><strong><{$invoice.reoccurrences}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_PDF_MF_PERIOD}> :</div>
            <div><strong><{$invoice.reoccurrence_period_days}> <{$smarty.const._XPY_PDF_MF_DAYS}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_MF_PREVIOUS}> :</div>
            <div><strong><{$invoice.datetime_previous}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_MF_OCCURRENCE}> :</div>
            <div><strong><{$invoice.datetime_occurrence}></strong></div>
        </div>
    </div>
    <table>
        <tr class="head">
            <th>&nbsp;</th>
            <th><{$smarty.const._XPY_PDF_MF_OCCURRENCE_PAID_TH}></th>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_GRAND}></td>
            <td><{$invoice.occurrence_paid.grand}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_AMOUNT}></td>
            <td><{$invoice.occurrence_paid.amount}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_SHIPPING}></td>
            <td><{$invoice.occurrence_paid.shipping}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_HANDLING}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_PDF_MF_OCCURRENCE_TAX}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="foot">
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
<{/if}>
<h2><{$smarty.const._XPY_PDF_MF_ITEMS_H2}></h2>
<p><{$smarty.const._XPY_PDF_MF_ITEMS_P}></p>
<table>
    <tr class="head">
        <th><{$smarty.const._XPY_PDF_MF_QUANTITY_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_CAT_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_NAME_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TAX_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_UNITAMOUUNT_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TOTALAMOUUNT_TH}></th>
    </tr>
    <{foreach item=obj from=$items}>
        <tr class="<{cycle values="even,odd"}>">
            <td align='right'><{$obj.quantity}></td>
            <td><{$obj.cat}></td>
            <td><{$obj.name}></td>
            <td align='right'><{$obj.tax}>%</td>
            <td align='right'><{$obj.amount}> <{$invoice.currency}></td>
            <td align='right'><strong><{$obj.totals.grand}> <{$invoice.currency}></strong></td>
        </tr>
    <{/foreach}>
    <{if $invoice.did != 0}>
        <tr class="foot">
            <td colspan="4">&nbsp;</td>
            <td><{$smarty.const._XPY_PDF_MF_DISCOUNT_TD}></td>
            <td align='right'><strong><{$invoice.discount_amount}> <{$invoice.currency}></strong></td>
        </tr>
    <{/if}>
    <tr class="foot">
        <td colspan="4">&nbsp;</td>
        <td><{$smarty.const._XPY_PDF_MF_GRANDTOTAL_TD}></td>
        <td align='right'><strong><{$invoice.grand}> <{$invoice.currency}></strong></td>
    </tr>
</table>
<h2><{$smarty.const._XPY_PDF_MF_BREAKDOWN_H2}></h2>
<p><{$smarty.const._XPY_PDF_MF_BREAKDOWN_P}></p>
<table>
    <tr class="head">
        <th><{$smarty.const._XPY_PDF_MF_QUANTITY_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_CAT_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_SHIPPING_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_HANDLING_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_UNITWEIGHT_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TAX_TH}></th>
    </tr>
    <{foreach item=obj from=$items}>
        <tr class="<{cycle values="even,odd"}>">
            <td align='right'><{$obj.quantity}></td>
            <td><{$obj.cat}></td>
            <td align='right'><{$obj.shipping}> <{$invoice.currency}></td>
            <td align='right'><{$obj.handling}> <{$invoice.currency}></td>
            <td align='right'><{$obj.weight}> <{$invoice.weight_unit}></td>
            <td align='right'><strong><{$obj.tax}> %</strong></td>
        </tr>
    <{/foreach}>
    <tr class="foot">
        <td colspan="2">&nbsp;</td>
        <td align='right'><em><{$invoice.shipping}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.handling}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.weight}> <{$invoice.weight_unit}></em></td>
        <td align='right'><em><{$invoice.tax}> <{$invoice.currency}></em></td>
    </tr>
</table>
<h2><{$smarty.const._XPY_PDF_MF_BREAKDOWN_H2B}></h2>
<p><{$smarty.const._XPY_PDF_MF_BREAKDOWN_PB}></p>
<table>
    <tr class="head">
        <th><{$smarty.const._XPY_PDF_MF_QUANTITY_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_CAT_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TOTALSHIPPING_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TOTALHANDLING_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TOTALWEIGHT_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TOTALTAX_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_TOTALAMOUUNT_TH}></th>
        <th><{$smarty.const._XPY_PDF_MF_GRANDAMOUUNT_TH}></th>
    </tr>
    <{foreach item=obj from=$items}>
        <tr class="<{cycle values="even,odd"}>">
            <td align='right'><{$obj.quantity}></td>
            <td><{$obj.cat}></td>
            <td align='right'><strong><{$obj.totals.shipping}> <{$invoice.currency}></strong></td>
            <td align='right'><strong><{$obj.totals.handling}> <{$invoice.currency}></strong></td>
            <td align='right'><strong><{$obj.totals.weight}> <{$invoice.weight_unit}></strong></td>
            <td align='right'><strong><{$obj.totals.tax}> <{$invoice.currency}></strong></td>
            <td align='right'><strong><{$obj.totals.amount}> <{$invoice.currency}></strong></td>
            <td align='right'><strong><{$obj.totals.grand}> <{$invoice.currency}></strong></td>
        </tr>
    <{/foreach}>
    <tr class="foot">
        <td colspan="1">&nbsp;</td>
        <td><{$smarty.const._XPY_PDF_MF_GRANDTOTAL_TD}></td>
        <td align='right'><em><{$invoice.shipping}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.handling}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.weight}> <{$invoice.weight_unit}></em></td>
        <td align='right'><em><{$invoice.tax}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.amount}> <{$invoice.currency}></em></td>
        <td align='right'><strong><{$invoice.grand}> <{$invoice.currency}></strong></td>
    </tr>
</table>
<{if $payment_markup}>
    <h1><{$smarty.const._XPY_PDF_MF_MAKEPAYMENT_MANUAL}></h1>
    <p>The following details will allow you to make a manual payment with the bank. Send the total amount of
        <strong><{$invoice.grand}> <{$invoice.currency}></strong> to:<b><{$xoConfig.manual}><b><b>Quote
                    Reference Number: <strong><{$invoice.iid}></strong></p>
<{/if}>
<h2><{$smarty.const._XPY_PDF_MF_SOURCE_H2}></h2>
<p><{$smarty.const._XPY_PDF_MF_SOURCE_P}></p>
<div><a href=<{$invoice.invurl}>><{$invoice.invurl}></a></div>
<div><a href=<{$invoice.pdfurl}>><{$invoice.pdfurl}></a></div>
