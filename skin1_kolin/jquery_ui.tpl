{*jQuery UI Components included in jquery-ui.custom.min.js
    jquery.ui.core.min.js
    jquery.ui.widget.min.js
    jquery.ui.mouse.min.js
    jquery.ui.button.min.js
    jquery.ui.resizable.min.js
    jquery.ui.draggable.min.js
    jquery.ui.dialog.min.js
    jquery.ui.tabs.min.js
    jquery.ui.datepicker.min.js
    jquery.ui.position.min.js
*}
{* {load_defer file="lib/jqueryui/jquery-ui.custom.min.js" type="js"} *}
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>

{if $usertype eq 'C'}
{*  {load_defer file="lib/jqueryui/jquery.ui.theme.css" type="css"} *}
<link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.theme.css" />
{else}
{*  {load_defer file="lib/jqueryui/jquery.ui.admin.css" type="css"} *}
{/if}
