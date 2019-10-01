{extends  "catalog/base.tpl"}

{block 'content'}

    <div class="contact-form default-content-page">

        <h1>{t 'Contact Us'}</h1>


        <div class="contact-form default-form">
            <div class="row">
                <div class="desktop-label column small-12 large-6">{t 'Web Form (recommended)'}</div>
                <div class="page-info-text column small-12 large-6">{t 'The fields marked with <span class="required"></span> are mandatory'}</div>
            </div>
            <div class="row">
                <div class="column small-12">
                    {raw $form->renderBegin()}
                        {raw $form->render()}
                        <div style="text-indent: -9999px;">
                            <input name="ContactUsForm[company_name_full]" type="text" />
                        </div>
                        <div class="row">
                            <div class="column button-row">
                                <button class="button submit-button" type="submit" value="Submit">{t 'Submit'}</button>
                            </div>
                        </div>
                    {raw $form->renderEnd()}
                </div>
            </div>
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
                            <li class="tabs-title is-active"><a href="#Email" aria-selected="true"><span>{t 'Email'}</span></a></li>
                            <li class="tabs-title"><a data-tabs-target="Phone" href="#Phone"><span>{t 'Phone'}</span></a></li>
                            <li class="tabs-title"><a data-tabs-target="Fax" href="#Fax"><span>{t 'Fax'}</span></a></li>
                            <li class="tabs-title"><a data-tabs-target="Mail" href="#Mail"><span>{t 'Mail'}</span></a></li>
                        </ul>
                    </div>
                    <div class="tabs-content-container">
                        <div class="tabs-content" data-tabs-content="contact-us-tabs">
                            <div class="tabs-panel is-active" id="Email">
                                <div class="tab-content">
                                    <span class="title">{t 'Email'}</span><a href="mailto:{$config.newsletter_email}">{$config.newsletter_email}</a>
                                </div>
                            </div>
                            <div class="tabs-panel" id="Phone">
                                <div class="tab-content">
                                    <div><span class="title">Local</span><wbr>(616) 259-5711</div>
                                    <div><span class="title">Toll&nbsp;free</span><wbr>{$config.cidev_top_header_code}</div>
                                </div>
                            </div>
                            <div class="tabs-panel" id="Fax">
                                <div class="tab-content">
                                    <span>1-800-929-2835</span>
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