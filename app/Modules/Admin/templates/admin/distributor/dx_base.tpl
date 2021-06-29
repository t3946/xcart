{extends 'base/admin.tpl'}

{block "before-content"}
    {if $form}
        {set $distributorModel = $form->getDx()}
        {set $sections = $form->getSections()}
        {set $selected_section = $form->getSection($section)}
    {/if}
    {if $distributorModel && !$distributorModel->getIsNewRecord()}
        {set $prohibited = $distributorModel->getProhibitedProducts()}
        {set $approval = $distributorModel->getApprovalProducts()}
        {if $prohibited || $approval}
        <div class="enter_on_site align_left">
            <div style="padding: 12px 0 0; ">
                {if $prohibited}
                    <span style="margin-left: 1rem; font-size: 14px;">Dx offers the following products <b>prohibited by PayPal</b></span>
                    <ul>
                        {foreach $prohibited as $prp}
                        <li>{$prp}</li>
                        {/foreach}
                    </ul>
                {/if}
                {if $approval}
                    <span style="margin-left: 1rem; font-size: 14px;">Dx offers the following products requiring <b>approval by PayPal</b></span>
                    <ul>
                        {foreach $approval as $prp}
                            <li>{$prp}</li>
                        {/foreach}
                    </ul>
                {/if}
            </div>
        </div>
        {/if}
        <div id="distributor-reference-target"></div>
        <br />
        <br />
    {/if}
{/block}

{block 'content'}
    <style type="text/css">
        a.admin_link {
            color: blue;
        }

        ,
        a.admin_link:hover {
            text-decoration: none !important;
            color: red;
        }

        .dx_form input, .dx_form textarea {
            width: 100%;
        }

        .admin .required {
            color: inherit;
        }
    </style>
    <h1 class="mb-3.25" style="text-align: center">{$selected_section['title']}</h1>
{/block}