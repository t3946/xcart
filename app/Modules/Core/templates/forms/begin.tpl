<form class="submit_form{if $class} {$class}{/if}"
      action="{if $action}{$action}{/if}"
      method="{if $method}{$method}{else}POST{/if}"
      {if $enctype}enctype="{$enctype}"{/if}>