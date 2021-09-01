<div class="errors-block">
    <div class="row">
        <div class="columns large-12">
            {if $errors}
                <div class="errors">
                    <ul class="fields">
                        <li>Error</li>
                        <ul class="field-errors">
                            {foreach $errors as $error}
                                <li>
                                    {$error}
                                </li>
                            {/foreach}
                        </ul>
                    </ul>
                </div>
            {/if}
        </div>
    </div>
</div>