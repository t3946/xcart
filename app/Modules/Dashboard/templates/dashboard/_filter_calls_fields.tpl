
<fieldset class="{if $full_expanded}expanded-force{/if} collapsible" rel="1">
    <legend>General</legend>

    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_total">Direction:</label>
                </div>

                <div class="columns large-5">
                    <select multiple="multiple" name="search[call][direction][]" id="call_direction_value" class="big">
                        <option {if 'in'|in_array:$form_data.call.direction}selected{/if} value="in">Inbound</option>
                        <option {if 'out'|in_array:$form_data.call.direction}selected{/if} value="out">Outbound</option>
                        <option {if 'lost'|in_array:$form_data.call.direction}selected{/if} value="lost">Missed call</option>
                        <option {if 'vm'|in_array:$form_data.call.direction}selected{/if} value="vm">Voicemail</option>
                    </select>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_total">Status:</label>
                </div>

                <div class="columns large-5">
                    <select name="search[call][listened]" id="call_listened_value" class="big">
                        <option {if $form_data.call.listened === 'listened'}selected{/if} value="listened">Listened</option>
                        <option {if $form_data.call.listened === 'not_listened'}selected{/if} value="not_listened">Not listened</option>
                    </select>
                </div>
            </div>
        </li>
    </ul>

</fieldset>