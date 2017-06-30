import inViewport from '../utils/inViewport';

export default class LazyImageLoad
{
    constructor(elements = '.lazy-img') {
        this.attached = [];
        this.timer = null;
        this.interval = null;
        this.inLoad = 0;
        this.maxInLoad = 3;

        this.init(elements);
    }

    init(elements) {
        this.attached.push(elements);

        this._bind();
        setTimeout(this.each(), 200);
    }

    attach(elements) {
        this.attached.push(elements);
    }

    _bind() {
        $([document,window]).on('scroll resize', ()=>{this.runTimer(false)});
        $(document).on('lil.empty_inload', ()=>{this.runTimer(false)});
        $(document).on('lil.tick', ()=>{
            this.interval = setInterval(()=>{
                this.runTimer(true)
            }, 2000);
        });
    }

    runTimer(load_all = false, time_out = 200) {
        clearTimeout(this.timer);
        this.timer = setTimeout(()=>{
            this.each(load_all);
        }, time_out);
    }

    each(all = false) {
        let $items = $(this.attached.join(','));
        if ($items.length)
        {
            $items.each(function (n, target) {
                if (this.inLoad >= this.maxInLoad) {
                    return false;
                }

                let $target = $(target);
                if ($target.data('original') && (all || inViewport(target))) {

                    if (!$target.attr('src')) {
                        let $tload = $(document.createElement('img'));

                        $tload.on('load', () => {
                            $target.removeClass('lazy-img');
                            setTimeout(()=>{
                                $target.attr('src', $target.data('original'));

                                setTimeout(()=>{
                                    $target.addClass('lazy-loaded');
                                }, 200);
                            }, 20);

                            this.inLoad--;
                            if (!this.inLoad) {
                                $(document).trigger('lil.empty_inload');
                            }
                        });

                        $tload.attr('src', $target.data('original'));

                        this.inLoad++;
                    }
                    else {
                        $target.removeClass('lazy-img');
                    }
                }
            });

            if (!this.inLoad && !this.interval) {
                $(document).trigger('lil.tick');
            }
        }
        else {
            clearInterval(this.interval);
        }
    }
}
