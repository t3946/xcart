{set $brand = $model->brand}
{set $param_list = $model->getParamList()}
{set $files = $model->files->all()}
{set $is_option = $brand || $param_list || $files || $model->upc}
<div class="row">
    {if $is_option}
    <div class="col-12 col-lg-5 block">

        <div class="options">
            <div class="h2 title">{t 'Options'}</div>
            <div class="content">

                {if $brand}
                    {set $lbl}{t 'Brand'}{/set}
                    {include 'product/tabs/__option.tpl'
                        title=$lbl
                        value="<a href='"~ $brand->getAbsoluteUrl() ~"'>" ~ $brand->brand ~ "</a>"
                    }
                {/if}

                {foreach $param_list as $item}
                    {include 'product/tabs/__option.tpl'
                        title=$item.name
                        value=', '|implode:$item.values
                    }
                {/foreach}

                {if $model->upc}
                    {set $lbl}{t 'Barcode'}{/set}
                    {include 'product/tabs/__option.tpl'
                        title=$lbl
                        value=$model->upc
                    }
                {/if}

                {if $files}
                    {foreach $files as $file}
                        {if $file->isFileExists()}
                            {include 'product/tabs/__option.tpl'
                                title=$file->description
                                value="<div class='d-flex flex-row margin-0'><div class='option-file-icon'><img class='icon' src='{$file->getFormatIconUrl()}'></img></div><div class='padding-0 option-file_description'><a href='{$file->getAbsoluteUrl()}'>{$file->getGoodFileName()}<br>({$file->getFileSizeMB()})</a></div></div>"
                            }
                        {/if}
                    {/foreach}
                {/if}

            </div>
        </div>

    </div>
    {/if}
    <div class="col-12 col-lg-{$is_option ? 7 : 12} block">
        <div class="description">
            <div class="h2 title">{t 'Description'}</div>
            <div class="content">
                <article class='description-product-content' style="overflow: hidden">
                {raw $model->getFrontendDescription()}
                </article>
            </div>
        </div>
    </div>
</div>