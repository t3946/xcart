
import serialize from "form-serialize";
import dedup from "../utils/deduplicate"
import objToUri from "../utils/objToUri"

export default class CatalogFilter
{
    constructor() {
        this.elemets = {};
        this.options = {
            classes: {
                'form': '.filter_form',
                'blocks': ' .accordion-item',
                'clear': '.filter_reset .filter_item',
            }
        };

        this.init();
    }

    init() {
        this.elemets['form'] = $(this.options.classes['form']);
        this.elemets['blocks'] = $(this.elemets['form'].find(this.options.classes['blocks']));
        this.elemets['clear'] = $(this.options.classes['clear']);

        this._bind();
    }

    formSend(form) {

        let $this = $(form);
        let fd = dedup(serialize(form, { hash: true }));

        let action = ($this.prop('action') || window.location).split('#');

        let link = action[0];
        if (link.indexOf('?') === -1) {
            link += '?';
        }
        else {
            link += '&';
        }

        link += objToUri(fd);

        if (action.length > 1) {
            link = '#' + action[1];
        }

        window.location = link;
    }


    _bind() {
        this.elemets['form'].on('submit', (e) => {
            e.preventDefault();
            this.formSend(e.target);
        });

        this.elemets['blocks'].on('click', 'input[type=checkbox]', (e) => {
            let $this = $(e.target);
            let $linked = null;

            if ($this.data('group')) {
                $linked = this.elemets['form'].find('.' + $this.data('group'));
            }


            if ($this.hasClass('checked')) {
                $this.removeClass('checked');

                if ($linked) {
                    $linked.removeClass('checked');
                }

                if ($this.data('remove')) {
                    $('.' + $this.data('remove')).remove();
                }

            }

            if ($linked) {
                $linked.prop('checked', $this.prop('checked'));
            }
        });

        this.elemets['clear'].on('click', (e) => {
            let $this = $(e.target);

            $('.' + $this.data('group'))
                .removeClass('checked')
                .prop('checked', false)
                .closest('form').submit();


            $this.remove();
        });

        $(document).on('click', '.filter-block a.show_all, .state_line .action_button.filter a', function(e){
            e.preventDefault();
            e.stopPropagation();

            let $this = $(this);
            $this.mmodal({skin: 'filters ' + ($this.data('modal-class') || 'filter-modal')});
        });
    }
}