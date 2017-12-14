{if $categories}
    <select class="big" name="group[category]">
        {foreach $categories as $category}
            <option value="{$category->categoryid}">
                {$category->getPathExploded()}
            </option>
        {/foreach}
    </select>
{/if}