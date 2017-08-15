import 'intersection-observer';

export default class LazyImageLoad
{
    constructor(elements = '.lazy-img') {
        this.attached = [];
        this.timer = null;
        this.interval = null;
        this.observer = null;

        this.setObserver();
        this.init(elements);
    }

    init(elements) {
        this.attached.push(elements);

        this._bind();
        setTimeout(this.each(), 200);
    }

    setObserver()
    {
        this.observer = new IntersectionObserver(entries => {
            for (let i = 0, len = entries.length; i < len; i++) {
                if (entries[i].intersectionRatio <= 0) continue;

                let target = entries[i].target;
                let $target = $(target);

                if ($target.attr('data-background')) {
                    let $tload = $(document.createElement('img'));
                    let background = $target.attr('data-background');

                    $target.attr('data-background', null);
                    $target.addClass('lazy-bg');

                    $tload.on('load', () => {
                        setTimeout(()=>{
                            $target.css({'background-image': 'url('+background+')'});

                            setTimeout(()=>{
                                $target.addClass('lazy-bg-loaded');
                            }, 200);
                        }, 20);

                    });

                    $tload.attr('src', background);
                }

                if ($target.attr('data-original')) {
                    $target.removeClass('lazy-img');

                    let original = $target.attr('data-original');
                    let hasUpdate = ($target.src !== original);

                    if (hasUpdate) {
                        $target.attr('src', '');
                        $target.attr('data-original', null);
                        let $tload = $(document.createElement('img'));

                        $tload.on('load', () => {
                            setTimeout(()=>{
                                $target.attr('src', original);

                                setTimeout(()=>{
                                    $target.addClass('lazy-loaded');

                                    if (typeof($target.attr('usemap')) !== 'undefined' && $.fn.rwdImageMaps) {
                                        $target.rwdImageMaps();
                                    }
                                }, 200);
                            }, 20);
                        });

                        $tload.attr('src', original);
                    }
                }

                this.observer.unobserve(target.element);
            }
        });
    }

    attach(elements) {
        this.attached.push(elements);
    }

    _bind() {
        $([document,window]).on('scroll resize', ()=>{this.runTimer()});
        $(document) .on('lil.empty_inload, lil.tick, lil.recheck', ()=>{this.runTimer()});

        this.interval = setInterval(()=>{ this.runTimer(); }, 2000);
    }

    runTimer(load_all = false, time_out = 200, recheck = false) {
        clearTimeout(this.timer);
        this.timer = setTimeout(()=>{
            this.each(load_all, recheck);
        }, time_out);
    }

    each() {
        let query = this.attached.join(',');
        let items = document.querySelectorAll(query);

        if (items.length)
        {
            for (let i = 0, len = items.length; i < len; i++) {
                this.observer.observe(items[i]);
            }
        }
    }
}
