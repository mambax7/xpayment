<{if $invoice}>
    <h2><{$smarty.const._XPY_AM_INVOICE_H1}> : <{$invoice.invoicenumber}></h2>
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
    </div>
<{/if}>
<h1><{$smarty.const._XPY_AM_TRANSACTIONSLIST_H1}></h1>
<p><{$smarty.const._XPY_AM_TRANSACTIONSLIST_P}></p>
<div style="float:right;"><{$pagenav}></div>
<table>
    <tr class="head">
        <th><{$transactionid_th}></th>
        <th><{$email_th}></th>
        <th><{$invoice_th}></th>
        <th><{$status_th}></th>
        <th><{$date_th}></th>
        <th><{$gross_th}></th>
        <th><{$fee_th}></th>
        <th><{$settle_th}></th>
        <th><{$firstname_th}></th>
        <th><{$lastname_th}></th>
        <th><{$payer_email_th}></th>
        <th><{$smarty.const._XPY_AM_ACTIONS_TH}></th>
    </tr>
    <{foreach item=transaction from=$transactions}>
        <tr class="<{cycle values="even,odd"}>">
            <td align='center'><{$transaction.transactionid}></td>
            <td><{$transaction.email}></td>
            <td><{$transaction.invoice}></td>
            <td><{$transaction.status}></td>
            <td><{$transaction.date_datetime}></a></td>
            <td align='right'><{$transaction.gross}></td>
            <td align='right'><{$transaction.fee}></td>
            <td align='right'><{$transaction.settle}></td>
            <td align='right'><{$transaction.firstname}></td>
            <td align='right'><strong><{$transaction.lastname}></strong></td>
            <td align='center'><{$transaction.payer_email}></td>
            <td align='center'><a
                        href="<{$php_self}>?op=transactions&amp;fct=view&tiid=<{$transaction.tiid}>"><{$smarty.const._XPY_AM_VIEWTRANSACTION}></a>&nbsp;|&nbsp;<a
                        href="<{$php_self}>?op=invoice&fct=view&iid=<{$transaction.iid}>"><{$smarty.const._XPY_AM_VIEWINVOICE}></a>
            </td>
        </tr>
    <{/foreach}>
    <tr class="foot">
        <td colspan="12">&nbsp;</td>
    </tr>
</table>
