<div class="errors-block">
    <div class="row">
        <div class="columns large-12">

            {if $model->getErrors()}
                <div class="errors">
                    <span>
                        Please fix the following errors:
                    </span>
                    <ul class="fields">
                        {foreach $model->getErrors() as $field => $errors}
                            <li>
                                Field "{$field}":
                                <ul class="field-errors">
                                    {foreach $errors as $error}
                                        <li>
                                            {$error}
                                        </li>
                                    {/foreach}
                                </ul>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            {/if}

        </div>
    </div>
</div>
