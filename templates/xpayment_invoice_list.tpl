<h1><{$smarty.const._XPY_AM_INVOICELIST_H1}></h1>
<p><{$smarty.const._XPY_AM_INVOICELIST_P}></p>
<div style="float:right;"><{$pagenav}></div>
<table>
    <tr class="head">
        <th><{$mode_th}></th>
        <th><{$remittion_th}></th>
        <th><{$created_th}></th>
        <th><{$invoicenumber_th}></th>
        <th><{$drawfor_th}></th>
        <th><{$drawto_th}></th>
        <th><{$drawto_email_th}></th>
        <th><{$tax_th}></th>
        <th><{$shipping_th}></th>
        <th><{$handling_th}></th>
        <th><{$amount_th}></th>
        <th><{$grand_th}></th>
        <th><{$items_th}></th>
        <th><{$transactionid_th}></th>
        <th><{$smarty.const._XPY_AM_ACTIONS_TH}></th>
    </tr>
    <{foreach item=invoice from=$invoices}>
        <tr class="<{cycle values="even,odd"}>">
            <td align='center'><{$invoice.mode}></td>
            <td align='center'><{$invoice.remittion}></td>
            <td align='center'><{$invoice.created_datetime}></td>
            <td align='center'><{$invoice.invoicenumber}></td>
            <td><{$invoice.drawfor}></td>
            <td><{$invoice.drawto}></td>
            <td><a href="mailto:<{$invoice.drawto_email}>"><{$invoice.drawto_email}></a></td>
            <td align='right'><{$invoice.tax}> <{$invoice.currency}></td>
            <td align='right'><{$invoice.shipping}> <{$invoice.currency}></td>
            <td align='right'><{$invoice.handling}> <{$invoice.currency}></td>
            <td align='right'><{$invoice.amount}> <{$invoice.currency}></td>
            <td align='right'><strong><{$invoice.grand}> <{$invoice.currency}></strong></td>
            <td align='center'><{$invoice.items}></td>
            <td align='center'><{$invoice.transactionid}></td>
            <td align='center'><{if $invoice.mode == 'UNPAID'}><a
                    href="<{$php_self}>?op=invoices&amp;fct=cancel&iid=<{$invoice.iid}>"><{$smarty.const._XPY_AM_CANCEL}></a>&nbsp;|&nbsp;<{/if}>
                <a
                        href="<{$php_self}>?op=invoices&amp;fct=view&iid=<{$invoice.iid}>"><{$smarty.const._XPY_AM_VIEW}></a>&nbsp;|&nbsp;<a
                        href="<{$php_self}>?op=transactions&amp;fct=list&amp;iid=<{$invoice.iid}>"><{$smarty.const._XPY_AM_TRANSACTIONS}></a>
            </td>
        </tr>
    <{/foreach}>
    <tr class="foot">
        <td colspan="15">&nbsp;</td>
    </tr>
</table>
