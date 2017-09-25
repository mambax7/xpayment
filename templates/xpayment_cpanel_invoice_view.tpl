<h1><{$smarty.const._XPY_AM_INVOICE_H1}> : <{$invoice.invoicenumber}></h1>
<div style="float:right;"><a href='<{$invoice.pdfurl}>'><img src='<{$xoops_url}>/modules/xpayment/assets/images/pdf.png'
                                                             border='0'></a></div>
<p><{$smarty.const._XPY_AM_INVOICE_P}></p>
<div style='width:100%; clear:both;'>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_INVOICENUMBER}> :</div>
        <div><strong><{$invoice.invoicenumber}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_DRAWNFOR}> :</div>
        <div><strong><{$invoice.drawfor}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_DRAWNTO}> :</div>
        <div><strong><{$invoice.drawto}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_AMOUNT}> :</div>
        <div><strong><{$invoice.amount}> <{$invoice.currency}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_TOTALSHIPPING}> :</div>
        <div><strong><{$invoice.shipping}> <{$invoice.currency}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_TOTALHANDLING}> :</div>
        <div><strong><{$invoice.handling}> <{$invoice.currency}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_TOTALTAX}> :</div>
        <div><strong><{$invoice.tax}> <{$invoice.currency}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_CREATED}> :</div>
        <div><strong><{$invoice.created_datetime}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_MODE}> :</div>
        <div><strong><{$invoice.mode}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_ITEMS}> :</div>
        <div><strong><{$invoice.items}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_TOTALWEIGHT}> :</div>
        <div><strong><{$invoice.weight}> <{$invoice.weight_unit}></strong></div>
    </div>
    <{if $invoice.mode != 'UNPAID'}>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_ACTIONED}> :</div>
            <div><strong><{$invoice.actioned_datetime}></strong></div>
        </div>
    <{/if}>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_GRANDAMOUNT}> :</div>
        <div><strong><{$invoice.grand}> <{$invoice.currency}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_PAID}> :</div>
        <div><strong><{$invoice.paid}> <{$invoice.currency}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_DUE}> :</div>
        <div><strong><{$invoice.due_datetime}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_COLLECT}> :</div>
        <div><strong><{$invoice.collect_datetime}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_WAIT}> :</div>
        <div><strong><{$invoice.wait_datetime}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_OFFLINE}> :</div>
        <div><strong><{$invoice.offline_datetime}></strong></div>
    </div>
    <div>
        <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_DONATION}> :</div>
        <div><strong><{$invoice.donation}></strong></div>
    </div>
    <{if $invoice.remitted > 0}>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_REMITTION}> :</div>
            <div><strong><{$invoice.remittion}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_REMITTED}> :</div>
            <div><strong><{$invoice.remitted_datetime}></strong></div>
        </div>
    <{/if}>
</div>
<{if $invoice.reoccurrence > 0}>
    <h2><{$smarty.const._XPY_AM_REOCCURRENCE_H2}></h2>
    <p><{$smarty.const._XPY_AM_REOCCURRENCE_P}></p>
    <div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_REOCCURRENCE}> :</div>
            <div><strong><{$invoice.reoccurrence}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_REOCCURRENCES}> :</div>
            <div><strong><{$invoice.reoccurrences}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_PERIOD}> :</div>
            <div><strong><{$invoice.reoccurrence_period_days}> <{$smarty.const._XPY_AM_DAYS}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_PREVIOUS}> :</div>
            <div><strong><{$invoice.previous_datetime}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_OCCURRENCE}> :</div>
            <div><strong><{$invoice.occurrence_datetime}></strong></div>
        </div>
    </div>
    <table>
        <tr class="head">
            <th>&nbsp;</th>
            <th><{$smarty.const._XPY_AM_OCCURRENCE_PAID_TH}></th>
            <th><{$smarty.const._XPY_AM_OCCURRENCE_LEFT_TH}></th>
            <th><{$smarty.const._XPY_AM_OCCURRENCE_TOTAL_TH}></th>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_GRAND}></td>
            <td><{$invoice.occurrence_paid.grand}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.grand}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.grand}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_AMOUNT}></td>
            <td><{$invoice.occurrence_paid.amount}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.amount}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.amount}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_SHIPPING}></td>
            <td><{$invoice.occurrence_paid.shipping}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.shipping}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.shipping}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_HANDLING}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_TAX}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_left.handling}> <{$invoice.currency}></td>
            <td><{$invoice.occurrence_total.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="foot">
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
<{elseif $invoice.reoccurrence == -1}>
    <h2><{$smarty.const._XPY_AM_REOCCURRENCE_H2}></h2>
    <p><{$smarty.const._XPY_AM_REOCCURRENCE_P}></p>
    <div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_REOCCURRENCE}> :</div>
            <div><strong><{$smarty.const._XPY_AM_REOCCURRENCE_ONGOING}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_REOCCURRENCES}> :</div>
            <div><strong><{$invoice.reoccurrences}></strong></div>
        </div>
        <div>
            <div style='width:190px;float:left;'><{$smarty.const._XPY_AM_PERIOD}> :</div>
            <div><strong><{$invoice.reoccurrence_period_days}> <{$smarty.const._XPY_AM_DAYS}></strong></div>
        </div>
    </div>
    <table>
        <tr class="head">
            <th>&nbsp;</th>
            <th><{$smarty.const._XPY_AM_OCCURRENCE_PAID_TH}></th>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_GRAND}></td>
            <td><{$invoice.occurrence_paid.grand}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_AMOUNT}></td>
            <td><{$invoice.occurrence_paid.amount}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_SHIPPING}></td>
            <td><{$invoice.occurrence_paid.shipping}> <{$invoice.currency}></td>
        </tr>
        <tr class="odd">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_HANDLING}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="even">
            <td align='right'><{$smarty.const._XPY_AM_OCCURRENCE_TAX}></td>
            <td><{$invoice.occurrence_paid.handling}> <{$invoice.currency}></td>
        </tr>
        <tr class="foot">
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
<{/if}>
<h2><{$smarty.const._XPY_AM_ITEMS_H2}></h2>
<p><{$smarty.const._XPY_AM_ITEMS_P}></p>
<table>
    <tr class="head">
        <th><{$smarty.const._XPY_AM_QUANTITY_TH}></th>
        <th><{$smarty.const._XPY_AM_CAT_TH}></th>
        <th><{$smarty.const._XPY_AM_NAME_TH}></th>
        <th><{$smarty.const._XPY_AM_TAX_TH}></th>
        <th><{$smarty.const._XPY_AM_UNITAMOUUNT_TH}></th>
        <th><{$smarty.const._XPY_AM_TOTALAMOUUNT_TH}></th>
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
    <tr class="foot">
        <td colspan="4">&nbsp;</td>
        <td><{$smarty.const._XPY_AM_GRANDTOTAL_TD}></td>
        <td align='right'><strong><{$invoice.grand}> <{$invoice.currency}></strong></td>
    </tr>
</table>
<h2><{$smarty.const._XPY_AM_BREAKDOWN_H2}></h2>
<p><{$smarty.const._XPY_AM_BREAKDOWN_P}></p>
<table>
    <tr class="head">
        <th><{$smarty.const._XPY_AM_QUANTITY_TH}></th>
        <th><{$smarty.const._XPY_AM_CAT_TH}></th>
        <th><{$smarty.const._XPY_AM_SHIPPING_TH}></th>
        <th><{$smarty.const._XPY_AM_HANDLING_TH}></th>
        <th><{$smarty.const._XPY_AM_UNITWEIGHT_TH}></th>
        <th><{$smarty.const._XPY_AM_TAX_TH}></th>
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
<h2><{$smarty.const._XPY_AM_BREAKDOWN_H2B}></h2>
<p><{$smarty.const._XPY_AM_BREAKDOWN_PB}></p>
<table>
    <tr class="head">
        <th><{$smarty.const._XPY_AM_QUANTITY_TH}></th>
        <th><{$smarty.const._XPY_AM_CAT_TH}></th>
        <th><{$smarty.const._XPY_AM_TOTALSHIPPING_TH}></th>
        <th><{$smarty.const._XPY_AM_TOTALHANDLING_TH}></th>
        <th><{$smarty.const._XPY_AM_TOTALWEIGHT_TH}></th>
        <th><{$smarty.const._XPY_AM_TOTALTAX_TH}></th>
        <th><{$smarty.const._XPY_AM_TOTALAMOUUNT_TH}></th>
        <th><{$smarty.const._XPY_AM_GRANDAMOUUNT_TH}></th>
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
        <td><{$smarty.const._XPY_AM_GRANDTOTAL_TD}></td>
        <td align='right'><em><{$invoice.shipping}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.handling}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.weight}> <{$invoice.weight_unit}></em></td>
        <td align='right'><em><{$invoice.tax}> <{$invoice.currency}></em></td>
        <td align='right'><em><{$invoice.amount}> <{$invoice.currency}></em></td>
        <td align='right'><strong><{$invoice.grand}> <{$invoice.currency}></strong></td>
    </tr>
</table>
<{if $settle_markup}>
    <h2><{$smarty.const._XPY_AM_SETTLE_H2}></h2>
    <p><{$smarty.const._XPY_AM_SETTLE_P}></p>
    <div align="center"><{$settle_markup}></div>
<{/if}>
<{if $payment_markup}>
    <h2><{$smarty.const._XPY_AM_MAKEPAYMENT_H2}></h2>
    <p><{$smarty.const._XPY_AM_MAKEPAYMENT_P}></p>
    <div align="center"><{$payment_markup}></div>
<{/if}>