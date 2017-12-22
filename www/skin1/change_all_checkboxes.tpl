{* $Id: change_all_checkboxes.tpl,v 1.2.2.2 2004/10/29 07:43:21 max Exp $ *}
{*
Parametrs: 
	checkboxes 			- array of tag names
	checkboxes_form		- form name with these checkboxes
*}
<SCRIPT>
function change_all(flag, formname, arr) {ldelim}
var x;
	if(!formname)
		formname = checkboxes_form;
	if(!arr)
		arr = checkboxes;
	if(!document.forms[formname] || arr.length == 0)
		return false;
	for(x = 0; x < arr.length; x++)
		if(arr[x] != '' && document.forms[formname].elements[arr[x]])
   			document.forms[formname].elements[arr[x]].checked = flag;
{rdelim}
</SCRIPT>
