<div class="ace-wrapper">
    <textarea id="textarea_{$id}" name="{$name}" style="display: none">{$value}</textarea>
    <div id="{$id}" name="{$name}" {raw $html}>{$value}</div>
</div>
{if $.request->getIsAjax()}
<script src="/static/backend/dist/raw/ace/src-min/ace.js"></script>
<script src="/static/backend/dist/raw/ace/src-min/mode-{$field->language}.js"></script>
<script src="/static/backend/dist/raw/ace/src-min/theme-{$field->theme}.js"></script>
{/if}
<script>
    function ace_init(){
        const editor = ace.edit("{$id}");
        const mode = ace.require("ace/mode/{$field->language}").Mode;
        var textarea = $('#textarea_{$id}');

        editor.setTheme("ace/theme/{$field->theme}");
        //editor.getSession().setUseWrapMode(true);
        editor.getSession().setWrapLimit(80);
        editor.session.setMode(new mode());

        editor.getSession().on("change", function () {
            textarea.val(editor.getSession().getValue());
        });
    }
    ace_init();
</script>

<style>
    .ace-wrapper {

    }
    #{$id} {
        position: relative;
        width: 770px;
        min-height: 500px;
    }
</style>