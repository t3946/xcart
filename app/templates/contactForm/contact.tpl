{extends  "catalog/base.tpl"}
{block 'content'}
    <div class="contact-form default-content-page">
        <h1>Contact Us</h1>
        <div class="page-info-text column">The fields marked with <span class="required"></span> are mandatory</div>
        <form data-abide novalidate>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label class="required">Full name</label><br/>
                    <span class="comment">Your first and last name</span>
                </div>
                <div class="column field">
                    <input type="text" placeholder="User Name" required pattern="alpha">
                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label>Your company name</label> <span class="comment">(optional)</span>
                </div>
                <div class="column field">
                    <input type="text" placeholder="Eureka Ink." required pattern="alpha_numeric">
                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label>Your zip/postal code</label> <span class="comment">(optional)</span>
                </div>
                <div class="column field">
                    <input type="text" placeholder="08540" required pattern="integer">
                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label>Your phone number</label> <span class="comment">(optional)</span><br/>
                    <span class="comment">Phone number you can be reached at</span>
                </div>
                <div class="column field">
                    <input type="text" placeholder="(609) 734-8000" required pattern="alpha_numeric">
                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label class="required">Your email address</label><br/>
                    <span class="comment">Valid email address is must</span>
                </div>
                <div class="column field">
                    <input type="text" placeholder="albert.eistein@gmail.com" required pattern="email">
                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label class="required">Department</label><br/>
                    <span class="comment">Your message will be routed to this department</span>
                </div>
                <div class="column field">
                    <label for=""><select id="select" required>
                            <option value=""></option>
                            <option value="Product questions">Product questions</option>
                            <option value="Shipping quote">Shipping quote</option>
                        </select></label>

                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label class="required">Product SKU or your order #</label><br/>
                    <span class="comment">SKU of product you are interested in or your order #</span>
                </div>
                <div class="column field">
                    <input type="text" placeholder="EDR-T-A63127 or AR-54321" required>
                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label class="required">Subject line</label>
                </div>
                <div class="column field">
                    <textarea type="text" placeholder="EDR-T-A63127 or AR-54321 EDR-T-A63127 or AR-54321 EDR-T-A63127 or AR-54321" required></textarea>
                </div>
            </div>
            <div class="row small-up-1 medium-up-2 field-line">
                <div class="column field-title">
                    <label class="required">Your message</label>
                </div>
                <div class="column field">
                    <textarea type="text" required></textarea>
                </div>
            </div>
            <div class="row">
                <div class="column button-row">
                    <button class="button submit-button" type="submit" value="Submit">SUBMIT</button>
                </div>
            </div>

        </form>

        {* Разделитель *}
        <div class="row hr-position">
            <div class="column small-12">
                <div class="hr"></div>
            </div>
        </div>



        {*<div class="row tabs-container">*}
            {*<div class="tabs-title-container column small-2">*}
                {*<ul class="vertical tabs" data-tabs id="contact-tabs">*}
                    {*<li class="tabs-title is-active"><a href="#Email" aria-selected="true">Email</a></li>*}
                    {*<li class="tabs-title"><a href="#Phone">Phone</a></li>*}
                    {*<li class="tabs-title"><a href="#Fax">Fax</a></li>*}
                    {*<li class="tabs-title"><a href="#Mail">Mail</a></li>*}
                {*</ul>*}
            {*</div>*}
            {*<div class="tabs-panel-container column">*}
                {*<div class="tabs-content" data-tabs-content="contact-tabs">*}
                    {*<div class="tabs-panel is-active" id="Email">*}
                        {*<p>helpdesk@s3stores.com</p>*}
                    {*</div>*}
                    {*<div class="tabs-panel" id="Phone">*}
                        {*<p>Phone</p>*}
                    {*</div>*}
                    {*<div class="tabs-panel" id="Fax">*}
                        {*<p>Fax</p>*}
                    {*</div>*}
                    {*<div class="tabs-panel" id="Mail">*}
                        {*<p>Mail</p>*}
                    {*</div>*}
                {*</div>*}
            {*</div>*}
        {*</div>*}

        <div class="row">

            <ul class="tabs" data-tabs id="example-tabs">
                <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Tab 1</a></li>
                <li class="tabs-title"><a data-tabs-target="panel2" href="#panel2">Tab 2</a></li>
            </ul>

            <div class="tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="panel1">
                    <p>Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor. Suspendisse dictum feugiat nisl ut dapibus.</p>
                </div>
                <div class="tabs-panel" id="panel2">
                    <p>Suspendisse dictum feugiat nisl ut dapibus.  Vivamus hendrerit arcu sed erat molestie vehicula. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.  Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor.</p>
                </div>
            </div>


        </div>


    </div>

{/block}



