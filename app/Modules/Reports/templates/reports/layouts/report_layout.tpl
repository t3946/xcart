<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    {block 'js-head'}
        <link rel="stylesheet" href="/static/backend/dist/css/main.css">
    {/block}

</head>
<body class="OrderReport">
    <div id="main">
        {block 'main'}
            <div class="main-content">
                {block 'before-content'}

                {/block}

                <div class="report-row">
                    <div class="">
                        {block 'content'}

                        {/block}
                    </div>
                </div>

                {block 'after-content'}

                {/block}
            </div>
        {/block}
    </div>
</body>
</html>
