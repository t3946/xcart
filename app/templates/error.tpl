<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    {*<link rel="stylesheet" href="/static/frontend/dist/css/main.css?v={frontend_css_version}">*}
    {*<script src="/static/frontend/dist/js/main.js?v={frontend_js_version}"></script>*}

    <script>
        // window.location = '/';
    </script>
</head>
<body>
<div id="wrapper">

    <div id="main">
        {block 'content'}
            <section class="page pages error-page">
                <div class="row w1280">
                    <div class="columns small-12">
                        <section class="error-data">
                            <section class="error-code">
                                {block 'error_code'}
                                    {$data.code}
                                {/block}
                            </section>
                            <section class="error-info">
                                <section class="multiline">
                                    <section class="error-title">
                                        {block 'error_title'}
                                            {if $data.code == 404}
                                                Page not found
                                            {elseif $data.message}
                                                {$data.message}
                                            {else}
                                                Internal server error
                                            {/if}
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
                    </div>
                </div>


            </section>
        {/block}
    </div>

    <div id="push"></div>
</div>


{block 'js'}

{/block}
</body>
</html>