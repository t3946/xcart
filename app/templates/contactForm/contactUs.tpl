{extends  "catalog/base.tpl"}

{block 'content'}
    <div class="contact-form default-content-page container">
        <h1>{t 'Contact Us'}</h1>

        <div class="contact-form default-form">
            <div class="row">
                <div class="desktop-label col-12 col-lg-6">{t 'Web Form (recommended)'}</div>
                <div class="page-info-text col-12 col-lg-6">{t 'The fields marked with <span class="required"></span> are mandatory'}</div>
            </div>

            <div class="row">
                <div class="col-12">
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
            <div class="col-12">
                <div class="hr"></div>
            </div>
        </div>
        <div class="row">

            <div class="col-12 tabs-container-line">
                <div class="tabs-container">
                    <div class="tabs-title-container">
                        <ul class="vertical tabs" data-tabs id="contact-us-tabs">
                            <li class="tabs-title is-active"><a href="#Email" aria-selected="true"><span>{t 'Email'}</span></a></li>
                            <li class="tabs-title"><a data-tabs-target="Phone" href="#Phone"><span>{t 'Phone'}</span></a></li>
                            {if !empty($site->fax_number)} <li class="tabs-title"><a data-tabs-target="Fax" href="#Fax"><span>{t 'Fax'}</span></a></li>{/if}
                            <li class="tabs-title"><a data-tabs-target="Mail" href="#Mail"><span>{t 'Mail'}</span></a></li>
                        </ul>
                    </div>
                    {switch $site->country}
                        {case 'RU'}
                            {include "contactForm/contactContentRu.tpl"}
                        {default}
                            {include "contactForm/contactContentUs.tpl"}
                    {/switch}
            </div>
        </div>
    </div>
{/block}