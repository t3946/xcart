<?php

require "./auth.php";
x_load('category');

//all cats
$cats = func_query("SELECT categoryid FROM xcart_categories");
func_recalc_product_count($cats,20);

func_recalc_subcat_count($cats,20);

echo "<br />Products and Subcategories are recalculated";

