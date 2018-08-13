{set $constraints = 'constraints'~$prefix}
<form class="submit_form frontend_form{if $class} {$class}{/if}"
      action="{if $action}{$action}{/if}"
      method="{if $method}{$method}{else}POST{/if}"
      novalidate=""
      id="{$prefix}"
      data-constraints="{$constraints}"
      {if $enctype}enctype="{$enctype}"{/if} data-validate="true">