<div class="row">
    <div class="column small-12 large-5 block">

        <div class="options">
            <div class="h2 title">{t 'Options'}</div>
            <div class="content">

                {set $brand = $model->brand}
                {if $brand}
                    {set $lbl}{t 'Brand'}{/set}
                    {include 'product/tabs/__option.tpl'
                        title=$lbl
                        value="<a href='"~ $brand->getAbsoluteUrl() ~"'>" ~ $brand->brand ~ "</a>"
                    }
                {/if}

                {foreach $model->getParamList() as $item}
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

                {set $files = $model->files->all()}
                {if $files}
                    {foreach $files as $file}
                        {if $file->isFileExists()}
                            {include 'product/tabs/__option.tpl'
                                title=$file->description
                                value="<div class='row margin-0'><div class='columns option-file-icon shrink'><img class='icon' src='{$file->getFormatIconUrl()}'></img></div><div class='columns padding-0 option-file_description'><a href='{$file->getAbsoluteUrl()}'>{$file->getGoodFileName()}<br>({$file->getFileSizeMB()})</a></div></div>"
                            }
                        {/if}
                    {/foreach}
                {/if}

            </div>
        </div>

    </div>
    <div class="column small-12 large-7 block">

        <div class="description">
            <div class="h2 title">{t 'Description'}</div>
            <div class="content">
                {raw $model->getFrontendDescription()}
            </div>
        </div>

    </div>
</div>