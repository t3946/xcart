<head>
    <meta charset="utf-8">

    {block 'head'}

    {/block}
</head>
<body>
<div id="main">
    {block 'main'}
            {block 'content'}{/block}
            {block 'after-content'}{/block}
        </div>
    {/block}
</body>
</html>
