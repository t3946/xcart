<div class="buttons-block">
    <div class="row">
        <div class="columns large-12" >

            <section class="button-group">
                <input type="submit" class="button" name="save_continue" value="Save">
                <input type="submit" class="button" name="save" value="Save and Close" >
            </section>


            {if !$model->getIsNewRecord() && method_exists($model, 'getAbsoluteUrl')}
                <section class="button-group">
                    <a href="{$model->getAbsoluteUrl()}" class="button green" target="_blank">Preview results</a>
                </section>
            {/if}

            {if !$model->getIsNewRecord()}
                <section class="button-group float-right">
                    <input type="submit" class="button darkred" name="delete" value="Delete">
                </section>
            {/if}

        </div>
    </div>
</div>
