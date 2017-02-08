<select name="{$this->pageSizeKey}" id="pageSize" onchange="window.location=this.value">
    {foreach $this->pageSizes as $pageSize }
        <option value="{$this->urlPageSize($pageSize)}" {if $this->pageSize == $pageSize }selected="selected"{/if}>
            {$pageSize}
        </option>
    {/foreach}
</select>
