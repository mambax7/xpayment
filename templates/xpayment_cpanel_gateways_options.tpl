<h1><{$smarty.const._XPY_AM_OPTIONSGATEWAY_H1}></h1>
<p><{$smarty.const._XPY_AM_OPTIONSGATEWAY_P}></p>
<form action="<{$php_self}>" method="post">
    <{securityToken}><{*//mb*}>
    <table>
        <tr class="head">
            <th><{$name_th}></th>
            <th><{$value_th}></th>
        </tr>
        <{foreach item=option from=$options}>
            <tr>
                <td align='left' class="<{cycle values="even,odd"}>"><{$option.name}></td>
                <td align='left' class="<{cycle values="odd,even"}>"><input type='text' name='value[<{$option.goid}>]'
                                                                            value="<{$option.value}>" size="45"
                                                                            maxlen="255"></td>
            </tr>
        <{/foreach}>
        <tr class="foot">
            <td colspan="5"><input type="submit" name="submit" value="<{$smarty.const._SUBMIT}>"></td>
        </tr>
    </table>
    <input type="hidden" name="op" value="gateways">
    <input type="hidden" name="fct" value="setoptions">
</form>
