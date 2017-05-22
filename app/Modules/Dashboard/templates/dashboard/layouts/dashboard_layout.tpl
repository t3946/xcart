{extends 'base/admin.tpl'}

{block 'js'}
<script type="text/javascript">
    (function(){
        $('.admin select[data-ajax-from]').on("select2:select",  function(e) {
            $(this).append($('option[selected]', { value: e.params.data.id, text: e.params.data.text }));
        });

        $('.admin select:not([data-ajax-from])').not('.page-size select, .not-select2').select2({ allowClear: true,
            closeOnSelect: false,
            placeholder: 'Select options'
        });

        $('.admin select[data-ajax-from]').select2({
            allowClear: true,
            placeholder: 'Start typing for hint',
            tags: true,
            closeOnSelect: false,
            minimumInputLength: 3,
            createTag : function (params) {
                if (!this.$element.data('combobox')) {
                    return null;
                }

                var term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: '{$manual_string}' + term,
                    text: '{raw $manual_string}' + term
                }
            },
            ajax: {
                cache: true,
                dataType: 'json',
                delay: 500,
                url : function(params)
                {
                    var combobox = 0;
                    if ($(this).data('combobox')) {
                        combobox = 1;
                    }
                    return '{url 'dashboard:search_suggestion'}' + '?from=' + $(this).data('ajax-from') + '&combobox=' + combobox;
                },
                processResults: function (data) {
                    if (data) {
                        return {
                            results: data
                        };
                    }
                    return { results: { } };
                }
            }
        });
    })();
</script>
{/block}