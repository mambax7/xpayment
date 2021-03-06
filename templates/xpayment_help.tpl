<h1>Help</h1>
With the form below you can submit with a button an example invoice.

<h2>Code</h2>
<p>This is the form for the code you have just seen</p>
<blockquote>
    &lt;form action="<{$php_self}>" method="post"&gt;
    <b>&lt;!-- This is the header of the invoice --&gt;
        <b>&lt;input type="hidden" name="op" value="createinvoice"&gt;
            <b>&lt;input type="hidden" name="plugin" value="xpayment"&gt;
                <b>&lt;input type="hidden" name="drawfor" value="<{$xoops_sitename}>"&gt;<b>
                        <b>&lt;input type="hidden" name="drawto" value="<{$xoops_sitename}>"&gt;
                            <b>&lt;input type="hidden" name="drawto_email" value="<{$xoops_siteemail}>"&gt;
                                <b>&lt;input type="hidden" name="currency" value="AUD"&gt;
                                    <b>&lt;input type="hidden" name="key" value="<{php}> echo md5('example'); <{/php}>"&gt;
                                        <b>&lt;input type="hidden" name="weight_unit" value="kgs"&gt;
                                            <b>&lt;!-- This is the item trail of the invoice --&gt;
                                                <b>
                                                    <b>&lt;!-- Example Item A --&gt;
                                                        <b>&lt;input type="hidden" name="item[A][cat]" value="EXPITEMA"&gt;
                                                            <b>&lt;input type="hidden" name="item[A][name]"
                                                                value="Example Item A"&gt;
                                                                <b>&lt;input type="hidden" name="item[A][amount]"
                                                                    value="0.02"&gt;
                                                                    <b>&lt;input type="hidden" name="item[A][quantity]"
                                                                        value="2"&gt;
                                                                        <b>&lt;input type="hidden"
                                                                            name="item[A][shipping]" value="1.00"&gt;
                                                                            <b>&lt;input type="hidden"
                                                                                name="item[A][handling]" value="2.00"&gt;
                                                                                <b>&lt;input type="hidden"
                                                                                    name="item[A][weight]" value="0.1"&gt;
                                                                                    <b>&lt;input type="hidden"
                                                                                        name="item[A][tax]" value="10"&gt;
                                                                                        <b>
                                                                                            <b>&lt;!-- Example Item B --&gt;
                                                                                                <b>&lt;input
                                                                                                    type="hidden"
                                                                                                    name="item[B][cat]"
                                                                                                    value="EXPITEMB"&gt;
                                                                                                    <b>&lt;input
                                                                                                        type="hidden"
                                                                                                        name="item[B][name]"
                                                                                                        value="Example
                                                                                                        Item B"&gt;
                                                                                                        <b>&lt;input
                                                                                                            type="hidden"
                                                                                                            name="item[B][amount]"
                                                                                                            value="0.01"&gt;
                                                                                                            <b>&lt;input
                                                                                                                type="hidden"
                                                                                                                name="item[B][quantity]"
                                                                                                                value="1"&gt;
                                                                                                                <b>&lt;input
                                                                                                                    type="hidden"
                                                                                                                    name="item[B][shipping]"
                                                                                                                    value="2.00"&gt;
                                                                                                                    <b>
                                                                                                                        &lt;input
                                                                                                                        type="hidden"
                                                                                                                        name="item[B][handling]"
                                                                                                                        value="1.00"&gt;
                                                                                                                        <b>
                                                                                                                            &lt;input
                                                                                                                            type="hidden"
                                                                                                                            name="item[B][weight]"
                                                                                                                            value="0.1"&gt;
                                                                                                                            <b>
                                                                                                                                &lt;input
                                                                                                                                type="hidden"
                                                                                                                                name="item[B][tax]"
                                                                                                                                value="10"&gt;
                                                                                                                                <b>
                                                                                                                                    <b>
                                                                                                                                        &lt;!--
                                                                                                                                        This
                                                                                                                                        is
                                                                                                                                        the
                                                                                                                                        footer
                                                                                                                                        of
                                                                                                                                        the
                                                                                                                                        invoice
                                                                                                                                        --&gt;
                                                                                                                                        <b>
                                                                                                                                            &lt;input
                                                                                                                                            type="hidden"
                                                                                                                                            name="return"
                                                                                                                                            value="<{$xoops_url}>
                                                                                                                                            /modules/xpayment/return.php"&gt;
                                                                                                                                            <b>
                                                                                                                                                &lt;input
                                                                                                                                                type="hidden"
                                                                                                                                                name="cancel"
                                                                                                                                                value="<{$xoops_url}>
                                                                                                                                                /modules/xpayment/cancel.php"&gt;
                                                                                                                                                <b>
                                                                                                                                                    &lt;input
                                                                                                                                                    type="hidden"
                                                                                                                                                    name="ipn"
                                                                                                                                                    value="<{$xoops_url}>
                                                                                                                                                    /modules/xpayment/ipn.php"&gt;
                                                                                                                                                    <b>
                                                                                                                                                        &lt;input
                                                                                                                                                        type="submit"
                                                                                                                                                        name='submit'
                                                                                                                                                        value="Create
                                                                                                                                                        An
                                                                                                                                                        Invoice"
                                                                                                                                                        /&gt;
                                                                                                                                                        &lt;/form&gt;
</blockquote>

<h2>Click the button to create and invoice</h2>
<form action="<{$php_self}>" method="post">
    <{securityToken}><{*//mb*}>
    <!-- This is the header of the invoice -->
    <input type="hidden" name="op" value="createinvoice">
    <input type="hidden" name="plugin" value="xpayment">
    <input type="hidden" name="drawfor" value="<{$xoops_sitename}>"><b>
        <input type="hidden" name="drawto" value="<{$xoops_sitename}>">
        <input type="hidden" name="drawto_email" value="<{$xoops_siteemail}>">
        <input type="hidden" name="currency" value="AUD">
        <input type="hidden" name="key" value="<{php}> echo md5('example'); <{/php}>">
        <input type="hidden" name="weight_unit" value="kgs">
        <!-- This is the item trail of the invoice -->

        <!-- Example Item A -->
        <input type="hidden" name="item[A][cat]" value="EXPITEMA">
        <input type="hidden" name="item[A][name]" value="Example Item A">
        <input type="hidden" name="item[A][amount]" value="0.02">
        <input type="hidden" name="item[A][quantity]" value="2">
        <input type="hidden" name="item[A][shipping]" value="1.00">
        <input type="hidden" name="item[A][handling]" value="2.00">
        <input type="hidden" name="item[A][weight]" value="0.01">
        <input type="hidden" name="item[A][tax]" value="10">

        <!-- Example Item B -->
        <input type="hidden" name="item[B][cat]" value="EXPITEMB">
        <input type="hidden" name="item[B][name]" value="Example Item B">
        <input type="hidden" name="item[B][amount]" value="0.01">
        <input type="hidden" name="item[B][quantity]" value="1">
        <input type="hidden" name="item[B][shipping]" value="2.00">
        <input type="hidden" name="item[B][handling]" value="1.00">
        <input type="hidden" name="item[B][weight]" value="0.01">
        <input type="hidden" name="item[B][tax]" value="10">

        <!-- This is the footer of the invoice -->
        <input type="hidden" name="return" value="<{$xoops_url}>/modules/xpayment/return.php">
        <input type="hidden" name="cancel" value="<{$xoops_url}>/modules/xpayment/cancel.php">
        <input type="hidden" name="ipn" value="<{$xoops_url}>/modules/xpayment/ipn.php">
        <input type="submit" name='submit' value="Create An Invoice">
</form>
