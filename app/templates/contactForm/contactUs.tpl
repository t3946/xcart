{extends  "catalog/base.tpl"}

{block 'content'}

    <div class="contact-form default-content-page">

        <h1>Contact Us</h1>
        <div class="row">
            <div class="desktop-label column small-12 large-6">Web Form (recommended)</div>
            <div class="page-info-text column small-12 large-6">The fields marked with <span class="required"></span> are mandatory</div>
        </div>

        <div class="contact-form">
            <form action="" method="post">
                {raw $form->render()}
                <div class="row">
                    <div class="column button-row">
                        <button class="button submit-button" type="submit" value="Submit">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>


        {* Разделитель *}
        <div class="row hr-position">
            <div class="column small-12">
                <div class="hr"></div>
            </div>
        </div>
        <div class="row">

            <div class="column small-12 tabs-container-line">
                <div class="tabs-container">
                    <div class="tabs-title-container">
                        <ul class="vertical tabs" data-tabs id="contact-us-tabs">
                            <li class="tabs-title is-active"><a href="#Email" aria-selected="true"><span>Email</span></a></li>
                            <li class="tabs-title"><a data-tabs-target="Phone" href="#Phone"><span>Phone</span></a></li>
                            <li class="tabs-title"><a data-tabs-target="Fax" href="#Fax"><span>Fax</span></a></li>
                            <li class="tabs-title"><a data-tabs-target="Mail" href="#Mail"><span>Mail</span></a></li>
                        </ul>
                    </div>
                    <div class="tabs-content-container">
                        <div class="tabs-content" data-tabs-content="contact-us-tabs">
                            <div class="tabs-panel is-active" id="Email">
                                <div class="tab-content">
                                    <span class="title">Email</span><a href="mailto:helpdesk@s3stores.com">helpdesk@s3stores.com</a>
                                </div>
                            </div>
                            <div class="tabs-panel" id="Phone">
                                <div class="tab-content">
                                    <div><span class="title">Local</span><wbr>(616) 259-5711</div>
                                    <div><span class="title">Toll&nbsp;free</span><wbr>1-800-929-2431</div>
                                </div>
                            </div>
                            <div class="tabs-panel" id="Fax">
                                <div class="tab-content">
                                    <span>(813) 944-4516</span>
                                </div>
                            </div>
                            <div class="tabs-panel" id="Mail">
                                <div class="row">
                                    <div class="column small-12 medium-6">
                                        <strong>USA Address</strong><br/>
                                        S3 Stores, Inc.<br/>
                                        2885 Sanford Ave SW #12717<br/>
                                        Grandville, MI 49418<br/>
                                        USA
                                    </div>
                                    <div class="column small-12 medium-6">
                                        <strong>Canadian Address</strong><br/>
                                        S3 Stores, Inc.<br/>
                                        27 Joseph St.<br/>
                                        Chatham, Ontario N7L 3G4<br/>
                                        Canada
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>
{/block}