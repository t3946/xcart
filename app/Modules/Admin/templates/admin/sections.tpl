{if $sections}
    {set $cnt = count($sections)}
    {set $multiple = false}
    {if $cnt > 1}
        {set $multiple = true}
        {set $split = round($cnt / 2)}
    {/if}
    <div style="border: #000 2px solid; padding:10px 5px; font-weight: bold">
        {$form->getInstance()} sections
        <a style="float:right; text-decoration: none; font-weight: bold" href="#" onclick="$('.section_table').toggle(); return false;">+</a>
    </div>
    <div class="section_table" style="display: none">
        <table cellspacing="0" cellpadding="0" width="100%" class="NavDialogBox" style="BORDER: #FFCC33 1px solid;">
            <tr>
                <td width="100%" valign="top" cellspacing="0" cellpadding="0"
                    style="display: grid; {if $multiple}grid-template-columns: 1fr 1fr;{/if} grid-auto-flow: row dense; grid-gap: 0.5rem;">
                    <div>
                        {foreach $sections as $fN => $fieldset index=$index first=$f1}
                        {if $multiple}
                        {if $index == $split}
                    </div>
                    <div>
                        {/if}
                        <fieldset class="" style="margin-bottom: 0; background:inherit; grid-column-start: {if $cnt/2 > $index}1{else}2{/if};">
                            <legend><b style="font-size: 15px;color: red;">{$fN}</b></legend>
                            {/if}
                            <ul class="ul-main" style="{if $multiple}margin: 0{else}columns:2{/if}">
                                {foreach $fieldset as $key => $item first=$first}
                                    <li>
                                        <a href="" class="VertMenuItems"><img alt="right arrow" src="/skin1_kolin/images/rarrow.gif"></a>
                                        {if $key == $current_section}
                                        <b>
                                            {else}
                                            <a style="color: #330000;" href="{$form->getInstance()->getAdminUrl($key)}">
                                                {/if}
                                                {$item['title']}
                                                {if $key == $current_section}
                                        </b>
                                        {else}
                                        </a>
                                        {/if}
                                        {if $item['required']}
                                            <span style="color: red;font-size:1.8em;line-height: 10px;top: 7px;position: relative;">*</span>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                            {if $multiple}
                        </fieldset>
                        {/if}
                        {/foreach}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <br/>
    <br/>
{/if}