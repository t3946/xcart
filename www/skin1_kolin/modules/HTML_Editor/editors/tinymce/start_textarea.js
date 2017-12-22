

function enableEditor(id, name) {
    if (!$("#" + id))
        return false;

    tinyMCE.execCommand("mceAddEditor", false, id);

    $("#" + id + "Enb, #" + id + "DisB").hide();
    $("#" + id + "EnbB, #" + id + "Dis").show();

    setCookie(id + 'EditorEnabled', 'Y');
}

function disableEditor(id, name) {
    if (!$("#" + id))
        return false;

    tinymce.EditorManager.execCommand("mceRemoveEditor", false, id);

    $("#" + id + "EnbB, #" + id + "Dis").hide();
    $("#" + id + "Enb, #" + id + "DisB").show();

    deleteCookie(id + 'EditorEnabled');
}

function editor_get_xhtml_body(name) {
    tinymce.EditorManager.execCommand("mceRemoveEditor", false, name);
    result = $("#" + name).val();
    tinymce.EditorManager.execCommand("mceAddEditor", false, name);
    return result;
}

function editor_puthtml(name, value) {
    $("#" + name).val(value);
}

function get_html_editor(name) {
    return $("#" + name);
}

