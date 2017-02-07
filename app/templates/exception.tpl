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

                <div class="row">
                    <div class="column large-12">
                        {block 'content'}
                            <h1>{$code}</h1>
                            <pre>
                                {$exception}
                            </pre>

                            {*{foreach $trace as $tr}*}
                                {*<div class="block">*}
                                    {*<div class="file">*}
                                        {*{$tr.fileName}*}
                                    {*</div>*}
                                    {*{if $tr.trace.function}*}
                                        {*<div class="function">function: {$tr.trace.function}</div>*}
                                        {*<div class="args">{$tr.trace|print_r}</div>*}
                                    {*{/if}*}

                                    {*<div class="line">line: {$tr.trace.line}</div>*}

                                    {*<pre>{$tr.itemLines|print_r}</pre>*}
                                {*</div>*}
                            {*{/foreach}*}

                        {/block}
                    </div>
                </div>

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