import inViewport from '../utils/inViewport';

export default class LazyImageLoad
{
    constructor(elements = '.lazyimg') {

        this.init(elements);
    }

    init(elements) {
        this.attached = [elements];
        this.timer = null;

        this._bind();
        setTimeout(this.each(), 200);
    }

    attach(elements) {
        this.attached.push(elements);
    }

    _bind() {
        $(document).on('scroll', (el)=>{
            clearTimeout(this.timer);

            this.timer = setTimeout(this.each(), 200);
        });
    }

    each() {
        let $items = $(this.attached.join(','));
        if ($items.length)
        {
            $items.each(function (n, target) {
                let $target = $(target);
                if ($target.data('original') && inViewport(target)) {
                    $target.removeClass('lazyimg');
                    $target.attr('src', $target.data('original'));
                }
            });
        }
    }
}
