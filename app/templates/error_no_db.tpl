{block 'css_preload'}
    {insert '_parts/_css_preload.tpl'}
{/block}
{block 'content'}
    <section class="page pages error-page ">
        <section class="error-data-500 center-error">
            <section class="error-data">
                <section class="error-code">500</section>
                <section class="error-info">
                    <section class="multiline">
                        <section class="error-title">
                            {block 'error_title'}
                                Internal server error
                            {/block}
                        </section>
                        <section class="error-description">
                            {block 'error_description'}
                                <a href="/">To home &rarr;</a>
                            {/block}
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>
{/block}