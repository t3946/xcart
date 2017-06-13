
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
            }
        };

        this.init();
    }

    init() {
        this.elemets['form'] = $(this.options.classes['form']);

        this._bind();
    }


    _bind() {
        this.elemets['form'].on('submit', (e) => {
            e.preventDefault();
            let $this = $(e.target);
            let fd = dedup(serialize(e.target, { hash: true }));

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

        });

        this.elemets['form'].on('click', 'input[type=checkbox]', (e) => {
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
            }

            if ($linked) {
                $linked.prop('checked', $this.prop('checked'));
            }
        });
    }
}