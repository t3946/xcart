{extends"ajax.tpl"}
{block 'content'}
    <div class="mmodal_notify_stock">
        {*<form action="{url 'catalog:notify_stock'}">*}
            {*{$form->render()}*}
        {*</form>*}

        <h2>Stock Notification</h2>

        <p>Email notification will be sent to your email address when the product is in stock.</p>
        <p>The fields marked with <span style="color: #ff0000;">*</span> are mandatory</p>

        <form action="{url 'catalog:notify_stock'}" method="post">
            {*{$form->render()}*}
            {*{$form->render($form->getTemplateFromType('ul'))}*}
            {$form->render()}
            {*<input type="hidden" name="form[productid]" value="{$form->getField('productid')->getValue()}">*}
            {*<p>Your email<span style="color: #ff0000;">*</span><input type="text" name="form[first_name]" value="" placeholder="Albert"></p>*}
            {*<p>Your first name<span style="color: #ff0000;">*</span><input type="email" name="form[email]" value="" placeholder="albert.einstein@gmail.com"></p>*}
            {*<p><span>Subscribe to our newsletter</span><input type="checkbox" name="form[is_subscribe]"></p>*}
            <button type="submit" class="button submit yellow waves waves-orange waves-effect">Submit</button>
        </form>
    </div>
{/block}