<textarea
        id="{$id}"
        name="{$name}"
        {raw $html}
        data-base-url="{url route="editor:index"}"
        data-changed-url="{url route="editor:changed"}"
>
    {$value}
</textarea>
