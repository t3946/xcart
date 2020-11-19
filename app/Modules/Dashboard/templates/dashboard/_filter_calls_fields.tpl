
<fieldset class="{if $full_expanded}expanded-force{/if} collapsible" rel="1">
    <legend>General</legend>

    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_total">Direction:</label>
                </div>

                <div class="columns large-5">
                    <select multiple="multiple" name="search[call][direction]" id="call_direction_value" class="big">
                        <option value="in">Inbound</option>
                        <option value="out">Outbound</option>
                        <option value="lost">Missed call</option>
                        <option value="vm">Voicemail</option>

                    </select>
                </div>
            </div>
        </li>
    </ul>

</fieldset>