{* $Id: partner_report_export.tpl,v 1.4 2004/04/26 10:04:25 max Exp $ *}
{section name=ri loop=$report}
{$report[ri].login}{$delimiter}{$report[ri].firstname}{$delimiter}{$report[ri].lastname}{$delimiter}{$report[ri].sum_paid}{$delimiter}{$report[ri].sum_nopaid}{$delimiter}{$report[ri].sum}{$delimiter}{$report[ri].min_paid}
{/section}
