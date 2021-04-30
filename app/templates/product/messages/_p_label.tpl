{set $containerClass = $containerClass ~~ "product-label__$type"}
{set $iconClass = $iconClass ~~ "product-label-icon__$type"}

<div class="product-label{$containerClass}">
    <i class="product-label-icon{$iconClass}"></i>
    <div class="product-label-text">{$text}</div>
</div>
