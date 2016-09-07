<link href="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.css" type="text/css" rel="stylesheet" />
<script src="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
<div style='display:none;'>
    <div id='retail_trust_message' style='padding:10px; background:#fff;'>
        <p><strong>This content comes from a hidden element on this page.</strong></p>

        <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came
            from when it is closed.</p>

        <p><a id="click" href="#" style='padding:5px; background:#ccc;'>Click me, it will be preserved!</a></p>

        <p><strong>If you try to open a new Colorbox while it is already open, it will update itself with the new
                content.</strong></p>

        <p>Updating Content Example:<br/>
            <a class="ajax" href="../content/ajax.html">Click here to load new content</a></p>
    </div>
</div>
<div style='display:none;'>
    <div id='retail_trust_message_after_close' style='padding:10px; background:#fff;'>
        <p><strong>This content comes from a hidden element on this page.</strong></p>

        <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came
            from when it is closed.</p>

    </div>
</div>
<script>
    {literal}
    $(document).ready(function () {
        setTimeout(function(){$.featherlight('#retail_trust_message',{afterClose:function(){
            $.featherlight('#retail_trust_message_after_close',{afterClose:function(){
                alert('Done');
            }});
        }})},1000);
    });
    {/literal}
</script>