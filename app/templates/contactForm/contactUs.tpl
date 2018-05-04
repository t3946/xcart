{extends  "catalog/base.tpl"}

{block 'content'}
    <div class="contact-form default-content-page">

        <h1>Contact Us</h1>
        <div class="page-info-text column">The fields marked with <span class="required"></span> are mandatory</div>

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

            <div class="column small-12">
                <div class="tabs-container">
                    <div class="tabs-title-container">
                        <ul class="vertical tabs" data-tabs id="contact-us-tabs">
                            <li class="tabs-title is-active"><a href="#Email" aria-selected="true">Email</a></li>
                            <li class="tabs-title"><a data-tabs-target="Phone" href="#Phone">Phone</a></li>
                            <li class="tabs-title"><a data-tabs-target="Fax" href="#Fax">Fax</a></li>
                            <li class="tabs-title"><a data-tabs-target="Mail" href="#Mail">Mail</a></li>
                        </ul>
                    </div>
                    <div class="tabs-content-container">
                        <div class="tabs-content" data-tabs-content="contact-us-tabs">
                            <div class="tabs-panel is-active" id="Email">
                                <p>Email</p>
                            </div>
                            <div class="tabs-panel" id="Phone">
                                <p>Phone</p>
                            </div>
                            <div class="tabs-panel" id="Fax">
                                <p>Fax</p>
                            </div>
                            <div class="tabs-panel" id="Mail">
                                <p>Mail</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>
{/block}

{block 'after-content'}
    <div class="row">
        <div class="small-12 column slider-viewed">
            {set $link}{url 'catalog:viewed'}{/set}
            {include 'slider/base_product_slider.tpl' title="You recently viewed items" link=$link hide=true hide_link=true}
        </div>
    </div>
{/block}