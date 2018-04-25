<head>
    <meta charset="utf-8">

    {block 'head'}

    {/block}
</head>
<body>
<div id="main">
    {block 'main'}

        <div class="main-content">
            {block 'content'}{/block}
            {block 'after-content'}{/block}
        </div>
    {/block}
</div>

</body>
</html>
