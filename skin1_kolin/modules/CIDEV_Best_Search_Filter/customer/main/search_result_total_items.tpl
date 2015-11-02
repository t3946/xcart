{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

  {if $total_items gte "1"}
    <div class="results-found">
    {$lng.txt_N_results_found|substitute:"items":$total_items}. {if $total_items gt "1"}{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}{/if}
    </div>

  {elseif $total_items eq "0"}
    {$lng.txt_N_results_found|substitute:"items":0}
    <br />
  {/if}

  <br />
