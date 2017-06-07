<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    {*<link rel="stylesheet" href="/static/frontend/dist/css/main.css?v={frontend_css_version}">*}
    {*<script src="/static/frontend/dist/js/main.js?v={frontend_js_version}"></script>*}
</head>
<body>
<div id="wrapper">

    <div id="main">
        {block 'main'}
            <div class="main-content">
                {block 'before-content'}

                {/block}

                <section class="error-page">
                    <section class="error-data">
                        <section class="error-code">
                            {block 'error_code'}
                                404
                            {/block}
                        </section>
                        <section class="error-info">
                            <section class="multiline">
                                <section class="error-title">
                                    {block 'error_title'}
                                        Page not found
                                    {/block}
                                </section>
                                <section class="error-description">
                                    {block 'error_description'}
                                        <a href="/">To home &rarr;</a>
                                    {/block}
                                </section>
                            </section>
                        </section>
                        <p class="clear"></p>
                    </section>
                </section>

                {block 'after-content'}

                {/block}
            </div>
        {/block}
    </div>

    <div id="push"></div>
</div>


{block 'js'}

{/block}
</body>
</html>