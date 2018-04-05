<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    {block 'head'}

    {/block}
</head>
<body>
<div class="header_line"></div><div class="header_line_2"></div>
{set $site = $.getSite}
<div class="header">
<img class="store_logo" src="{$site->images[0]}">

<hr class="between">
</div>
<div id="main">
    {block 'main'}
        <div class="main-content">
            {block 'content'}{/block}
            {block 'after-content'}{/block}
        </div>
    {/block}
</div>
<div class="footer">
    <div class="stores_icon">
        <a href="https://www.teachersupplysource.com/"><img src="/static/frontend/production/mini_store_icon/Teacher.png"></a>
        <a href="https://www.artistsupplysource.com/"><img src="/static/frontend/production/mini_store_icon/Artist.png"></a>
        <a href="https://www.7sportinggoods.com/"><img src="/static/frontend/production/mini_store_icon/Sport.png"></a>
        <a href="https://www.petsuppliesplace.com/"><img src="/static/frontend/production/mini_store_icon/Pet.png"></a>
        <a href="https://www.musicalinstrumentshoppe.com/"><img src="/static/frontend/production/mini_store_icon/Music.png"></a>
        <a href="https://www.organiclifesource.com/"><img src="/static/frontend/production/mini_store_icon/Organic.png"></a>
        <a href="https://www.sincerewedding.com/"><img src="/static/frontend/production/mini_store_icon/Wedding.png"></a>
        <a href="https://www.furnishingsmart.com/"><img src="/static/frontend/production/mini_store_icon/Furnishing.png"></a>
    </div>

    <img class="footer_line" src="/static/frontend/production/line.png">

    <div class="info">
        <div>
            <a href="www.s3stores.com">
                <img class="s3_logo" src="/static/frontend/production/logo_S3Stores.svg">
            </a>
        </div>
        <div>
            <p>
                <b>S3 Stores, Inc.</b><br>
                27 Joseph St.<br>
                Chatham, Ontario N7L 3G4<br>
                Canada
            </p>
        </div>
        <div>
            <p>
                <b>Order Online or Call Us</b><br>
                (616) 259-5711<br>
                1-800-929-2431
            </p>
        </div>
        <div class="social">
            <p>
                <b>Find Us on social media</b>
            </p>
            <br>
            <a href="https://www.facebook.com/Artist-Supply-Source-1427776920627480/"><img src="/static/frontend/production/social_icon/facebook.png"></a>
            <a href="https://twitter.com/ArtistSupplySo"><img src="/static/frontend/production/social_icon/twitter.png"></a>
            <a href="https://www.youtube.com/user/artistsupplysource/"><img src="/static/frontend/production/social_icon/youtube.png"></a>
            <a href="https://www.pinterest.com/artistsupplyso/"><img src="/static/frontend/production/social_icon/pinterest.png"></a>
            <a href="https://www.instagram.com/artistsupply/"><img src="/static/frontend/production/social_icon/instagram.png"></a>
            <a href="https://plus.google.com/u/0/b/103033501997786431295/103033501997786431295"><img src="/static/frontend/production/social_icon/google.png"></a>
        </div>
    </div>
</div>

<div class="footer_line"> <div class="footer_line_1"></div><div class="footer_line_2"></div> </div>

</body>
</html>