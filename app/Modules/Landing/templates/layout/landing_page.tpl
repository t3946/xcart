<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        {*<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">*}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation-float.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
        <link rel="stylesheet" href="/static/frontend/production/css/land.css?_={time()}">

        {block 'head'}

        {/block}
    </head>
    <body>
    <div class="top-patter pattern-yl"></div>
    <div id="wrapper">
        <header>
            <div class="row">
                <div class="column columns small-12 text-center">
                    <a href="/" class="logo">
                        <img src="/static/frontend/production/images/land/logo.png" alt="Logo: Artist Supply Source">
                    </a>
                </div>
            </div>
        </header>

        <div class="content">
            {block 'content'}{/block}
            {block 'after-content'}{/block}
        </div>

        <footer>
            <div class="row">
                <div class="column small-12 ">
                    <div class="footer-content text-center">
                        S3 Stores Inc.
                    </div>
                </div>
            </div>

        </footer>
    </div>
    </body>
</html>

