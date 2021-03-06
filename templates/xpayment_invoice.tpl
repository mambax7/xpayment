<h1><{$smarty.const._XPY_MN_INVOICE_H1}></h1>
<p><{$smarty.const._XPY_MN_INVOICE_P}></p>
<p><{$smarty.const._XPY_MN_INVOICE_P1}>&nbsp;<strong><{$xoConfig.currency}></strong></p>
<form action="<{$php_self}>" method="post">
    <{securityToken}><{*//mb*}>
    <!-- This is the header of the invoice -->
    <input type="hidden" name="op" value="createinvoice">
    <input type="hidden" name="plugin" value="xpayment">
    <input type="hidden" name="drawfor" value="<{$xoops_sitename}>"><b>
        <label><{$smarty.const._XPY_MN_DRAWTO}></label>&nbsp;<input type="text" name="drawto" value="<{$user.name}>"
                                                                    size="45" maxlen="255"><b>
            <label><{$smarty.const._XPY_MN_DRAWTOEMAIL}></label>&nbsp;<input type="text" name="drawto_email"
                                                                             value="<{$user.email}>" size="45"
                                                                             maxlen="255"><b>
                <label><{$smarty.const._XPY_MF_DONATION}></label>&nbsp;<input type="checkbox" name="donatiion"
                                                                              value="1"><b>
                    <input type="hidden" name="currency" value="<{$xoConfig.currency}>">
                    <input type="hidden" name="key" value="<{php}> echo time(); <{/php}>">
                    <input type="hidden" name="weight_unit" value="<{$xoConfig.weightunit}>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="head">
                            <td width="14%">
                                <div align="center"><{$smarty.const._XPY_MN_CATELOUGUENUMBER}></div>
                            </td>
                            <td width="13%">
                                <div align="center"><{$smarty.const._XPY_MN_ITEMNAME}></div>
                            </td>
                            <td width="12%">
                                <div align="center"><{$smarty.const._XPY_MN_UNITPRICE}></div>
                            </td>
                            <td width="12%">
                                <div align="center"><{$smarty.const._XPY_MN_QUANITY}></div>
                            </td>
                            <td width="12%">
                                <div align="center"><{$smarty.const._XPY_MN_UNITSHIPPING}></div>
                            </td>
                            <td width="12%">
                                <div align="center"><{$smarty.const._XPY_MN_UNITHANDLING}></div>
                            </td>
                            <td width="12%">
                                <div align="center"><{$smarty.const._XPY_MN_UNITWEIGHT}> (<{$xoConfig.weightunit}>)
                                </div>
                            </td>
                            <td width="13%">
                                <div align="center"><{$smarty.const._XPY_MN_TAX}></div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[A][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[B][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[C][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[D][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[E][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[F][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[H][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[I][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[J][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[K][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[L][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[M][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[N][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[O][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][cat]" value="" size="7" maxlength="128">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][name]" value="" size="23" maxlength="255">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][amount]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][quantity]" value="1" size="4" maxlength="10">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][shipping]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][handling]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][weight]" value="0.00" size="6" maxlength="16">
                                </div>
                            </td>
                            <td>
                                <div align="center">
                                    <input type="text" name="item[P][tax]" value="0" size="4" maxlength="10">
                                </div>
                            </td>
                        </tr>
                        <tr class="even">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <!-- This is the footer of the invoice -->
                    <input type="hidden" name="return" value="<{$xoops_url}>/modules/xpayment/return.php">
                    <input type="hidden" name="cancel" value="<{$xoops_url}>/modules/xpayment/cancel.php">
                    <input type="hidden" name="ipn" value="<{$xoops_url}>/modules/xpayment/ipn.php">
                    <input type="submit" name='submit' value="<{$smarty.const._XPY_MN_CREATEINVOICE}>">
</form>
