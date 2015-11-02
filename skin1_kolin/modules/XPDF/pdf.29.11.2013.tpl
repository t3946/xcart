{*
$Id: pdf.tpl 188 2011-06-04 16:36:19Z max $
vim: set ts=2 sw=2 sts=2 et:
*}

<?xml version="1.0" encoding="{$default_charset|default:"iso-8859-1"}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{config_load file="$skin_config"}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <link rel="stylesheet" type="text/css" href="{$SkinDir}/{#CSSFile#}" />
  <link href="{$SkinDir}/modules/XPDF/pdf.css" type="text/css" rel="stylesheet" />
</head>
<body>
  {include file=$pdf_template this_is_printable_version="Y"}
</body>
</html>
