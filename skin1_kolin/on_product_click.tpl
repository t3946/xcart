{if 
	$ga_page_name ne "" &&
	$N_key ne "" &&
	(
	    (
		$products[product].productid ne "" && 
		$products[product].product ne "" && 
		$products[product].category ne "" && 
		$products[product].brand ne "" &&
		$products[product].price ne ""
	    ) 
	    ||
	    (
                $product.productid ne "" &&
                $product.product ne "" &&
                $product.category ne "" &&
                $product.brand ne "" &&
                $product.price ne ""
	    )
	)
}

{assign var="ga__productid" value=$products[product].productid|default:$product.productid}
{assign var="ga__product" value=$products[product].product|default:$product.product}
{assign var="ga__category" value=$products[product].category|default:$product.category}
{assign var="ga__brand" value=$products[product].brand|default:$product.brand}
{assign var="ga__price" value=$products[product].price|default:$product.price}

onclick="onProductClick('{$ga__productid}','{$ga__product|escape}','{$ga__category|escape}','{$ga__brand|escape}','{$N_key}','{$ga_page_name}','{$ga__price}'); return !ga.loaded;"
{/if}
