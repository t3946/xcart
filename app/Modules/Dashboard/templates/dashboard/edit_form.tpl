{extends 'dashboard/dashboard_form.tpl'}

{block 'heading'}
    <h1 align="center">Filter {if $model->getIsNewRecord()}create{else}"{$model}" edit{/if}.</h1>
{/block}

{block 'content'}
    <div class="row">
        <div class="columns large-12">
            {include 'core/form/errors.tpl' model=$model}
        </div>
    </div>
    <div class="row">
        <div class="columns large-12">
            <form action="{$model->getAdminUrl()}" method="POST">
                {set $class_name = $model->classNameShort()}

                <fieldset class="expanded-force" rel="0">
                    <legend>Filter options</legend>

                    <ul class="ul-main">
                        <li>
                            <div class="row">
                                <div class="columns large-4">
                                    <label for="m_name">{$model->getField('name')->getVerboseName()}:</label>
                                </div>

                                <div class="columns large-6">
                                    <input type="text" name="{$class_name}[name]" id="m_name" value="{$model->name}" class="big">
                                </div>

                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <div class="columns large-4">
                                    <label for="m_position">{$model->getField('position_row')->getVerboseName()}:</label>
                                </div>

                                <div class="columns large-6">
                                    <input type="text" name="{$class_name}[position_row]" id="m_position" value="{$model->position_row}" class="">
                                </div>

                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <div class="columns large-4">
                                    <label for="m_position">{$model->getField('position_column')->getVerboseName()}:</label>
                                </div>

                                <div class="columns large-6">
                                    <input type="text" name="{$class_name}[position_column]" id="m_position" value="{$model->position_column}" class="">
                                </div>

                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <div class="columns large-4">
                                    <label for="m_enabled">Enabled:</label>
                                </div>

                                <div class="columns large-6">
                                    <input type="checkbox" name="{$class_name}[enabled]" id="m_enabled" value="{$model->enabled}" class="">
                                </div>

                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <div class="columns large-4">
                                    <label for="m_bold">Bold:</label>
                                </div>

                                <div class="columns large-6">
                                    <input type="checkbox" name="{$class_name}[bold]" id="m_bold" value="{$model->bold}" class="">
                                </div>

                            </div>
                        </li>

                    </ul>

                </fieldset>


                {include 'dashboard/form_fields.tpl' full_expanded = true}
                {include 'core/form/buttons.tpl'}
            </form>
        </div>
    </div>

{/block}