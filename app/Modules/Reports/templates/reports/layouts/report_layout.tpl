<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    {block 'js-head'}
        <link rel="stylesheet" href="/skin1_kolin/skin1_admin.css">
    {/block}

</head>
<body class="OrderReport">
    <div id="main">
        {block 'main'}
            <div class="main-content">
                {block 'before-content'}

                {/block}

                <div class="row">
                    <div class="column large-12">
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
