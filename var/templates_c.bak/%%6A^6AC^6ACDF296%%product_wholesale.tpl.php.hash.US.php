68b2fc273c52fc92c406bbb637ac3765a:15:{s:17:"lbl_add_new_price";s:13:"Add new price";s:14:"lbl_add_update";s:10:"Add/Update";s:7:"lbl_all";s:3:"All";s:19:"lbl_delete_selected";s:15:"Delete selected";s:22:"lbl_generate_discounts";s:18:"Generate discounts";s:14:"lbl_membership";s:10:"Membership";s:8:"lbl_more";s:7:"more...";s:8:"lbl_note";s:4:"Note";s:18:"lbl_price_per_item";s:5:"Price";s:12:"lbl_quantity";s:8:"Quantity";s:24:"lbl_wholesale_admin_note";s:2062:"When defining wholesale prices, please be aware that the list of prices that is displayed to a customer in the Customer area is sorted and filtered in a special way so that a customer can see only the prices actually available to him:<br />
<ul>
<li>Individual prices are arranged in the order of increasing the quantity of items for which the price is defined and decreasing the price amount (For example, 10+ items - $5 per item, 20+ items - $4 per item, 30+ items - $3 per item).</li>
<li>The prices are filtered by customer membership: customers without a membership see only the prices available to "All"; customers with a specific membership see both the prices available to "All" and to their membership.</li>
<li>The final filtering that defines which prices need to be displayed to a specific customer is performed according to the principle of the lowest price: at each level of wholesale pricing, customers are shown only the lowest price available to them.</li>
</ul>
For example, if in the store's administration back-end the prices are defined like this:
<pre>
Base price - $6 per item - Membership: All,
10+ items - $5 per item - Membership: All,
20+ items - $3 per item - Membership: Premium,
30+ items - $4 per item - Membership: All,
40+ items - $2.5 per item - Membership: Premium,
100+ items - $2 per item - Membership: Wholesale</pre>
, in the Customer area, a customer with Premium membership will be shown just the following prices:
<pre>
Base price - $6 per item - Membership: All,
10+ items - $5 per item - Membership: All,
20+ items - $3 per item - Membership: Premium,
40+ items - $2.5 per item - Membership: Premium</pre>
The price "100+ items - $2 per item - Membership: Wholesale" will be excluded from the list as unavailable to the Premium membership. <br />
The price "30+ items - $4 per item - Membership: All" will be excluded as fully replaceable by the price "20+ items - $3 per item - Membership: Premium" being the lowest price available to Premium members buying any number of items from the quantity range of 20-40 items.";s:30:"lbl_wholesale_admin_note_small";s:234:"When defining wholesale prices, please be aware that the list of prices that is displayed to a customer in the Customer area is sorted and filtered in a special way so that a customer can see only the prices actually available to him.";s:20:"lbl_wholesale_prices";s:16:"Wholesale prices";s:22:"txt_edit_product_group";s:77:"Check this checkbox to make the corresponding field common for these products";s:23:"txt_wholesales_top_text";s:350:"Below you can define wholesales pricing for the product. The product will be sold for the wholesale price if a customer from a specified membership group buys a quantity of product items that is greater or equal to the quantity specified in the 'Quantity' field. If the product has product variants, any wholesale prices defined here will be ignored.";}