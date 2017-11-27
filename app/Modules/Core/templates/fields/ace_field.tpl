<div class="ace-wrapper">
    <textarea id="textarea_{$id}" name="{$name}" style="display: none">{$value}</textarea>
    <div id="{$id}" name="{$name}" {raw $html}>{$value}</div>
</div>

<script>
    (function(){
        var editor = ace.edit("{$id}");
        var mode = ace.require("ace/mode/{$field->language}").Mode;
        var textarea = $('#textarea_{$id}');

        editor.setTheme("ace/theme/{$field->theme}");
        editor.getSession().setUseWrapMode(true);
        editor.getSession().setWrapLimit(80);
        editor.session.setMode(new mode());

        editor.getSession().on("change", function () {
            textarea.val(editor.getSession().getValue());
        });
    })();
</script>

<style>
    .ace-wrapper {

    }
    #{$id} {
        position: relative;
        width: 770px;
        min-height: 200px;
    }
</style>