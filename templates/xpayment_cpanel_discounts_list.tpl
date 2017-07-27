<h1><{$smarty.const._XPY_AM_NEWDISCOUNTS_H1}></h1>
<p><{$smarty.const._XPY_AM_NEWDISCOUNTS_P}></p>
<{$form}>
<h1><{$smarty.const._XPY_AM_DISCOUNTS_H1}></h1>
<p><{$smarty.const._XPY_AM_DISCOUNTS_P}></p>
<p style="height:55px;clear:both;">
<div style="float:right; position:relative; top:-35px;"><{$pagenav}></div>
</p>
<table>
    <tr class="head">
        <th><{$did_th}></th>
        <th><{$uid_th}></th>
        <th><{$code_th}></th>
        <th><{$email_th}></th>
        <th><{$validtill_th}></th>
        <th><{$redeems_th}></th>
        <th><{$discount_th}></th>
        <th><{$redeemed_th}></th>
        <th><{$created_th}></th>
        <th><{$updated_th}></th>
    </tr>
    <tr class="head">
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th><{$filter_code_th}></th>
        <th><{$filter_email_th}></th>
        <th>&nbsp;</th>
        <th><{$filter_redeems_th}></th>
        <th><{$filter_discount_th}></th>
        <th><{$filter_redeemed_th}></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
    <{foreach item=discount from=$discounts}>
        <tr class="<{cycle values="even,odd"}>">
            <td align='center'><{$discount.did}></td>
            <td align='center'><{$discount.user.uid}></td>
            <td align='center'><{$discount.code}></td>
            <td><a href="mailto:<{$discount.email}>"><{$discount.email}></a></td>
            <td align='right'><{$discount.date.validtill}></td>
            <td align='right'><{$discount.redeems}></td>
            <td align='right'><{$discount.discount}>%</td>
            <td align='right'><{$discount.redeemed}></td>
            <td align='center'><{$discount.date.created}></td>
            <td align='center'><{$discount.date.updated}></td>
        </tr>
    <{/foreach}>
    <tr class="foot">
        <td colspan="10">&nbsp;</td>
    </tr>
</table>
