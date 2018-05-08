import 'intersection-observer';

export default class LazyImageLoad
{
    constructor(elements = '.lazy-img, [data-background]') {
        this.attached = [];
        this.timer = null;
        this.intervalLoad = null;
        this.timeoutToFullLoad = null;
        this.observer = null;
        this.inLoad = 0;
        this.maxLoad = 5;
        this.stack = [];

        this.setObserver();
        this.init(elements);
    }

    init(elements) {
        this.attached.push(elements);

        this._bind();
        this.observe();
    }

    setObserver()
    {
        this.observer = new IntersectionObserver(entries => {
                for (let i = 0, len = entries.length; i < len; i++) {
                    if (entries[i].intersectionRatio <= 0) continue;

                    this.load(entries[i].target);
                }
            },
            {
                threshold: .3
            });

        this.observer.POLL_INTERVAL = 50;
    }

    /**
     *
     * @param {Element|Image} target
     */
    load(target)
    {

        if (target.dataset.background) {
            this.stack.push(()=>{
                let background = target.dataset.background;
                target.dataset.background = '';
                target.classList.add('lazy-bg');

                this.onLoad(background,
                    ()=>{ target.style.backgroundImage = 'url('+background+')' },
                    ()=>{
                        target.classList.remove('lazy-img');
                        target.classList.add('lazy-bg-loaded');
                    }
                );
            });

            return;
        }

        if (target.dataset.src) {
            let hasUpdate = !target.src.includes(target.dataset.src);

            if (!hasUpdate) {
                target.classList.add('lazy-loaded');
            }
            else {
                this.stack.push(()=>{
                    let original = target.dataset.src;

                    target.src = '';
                    target.dataset.src = '';

                    this.onLoad(original,
                        ()=>{ target.src = original; },
                        ()=>{
                            target.classList.remove('lazy-img');
                            target.classList.add('lazy-loaded');
                            if (typeof(target.usemap) !== 'undefined' && $.fn.rwdImageMaps) {
                                $(target).rwdImageMaps();
                            }
                        }
                    );
                });
            }
        }

        this.observer.unobserve(target);
        this.trigger('lil.tick');
    }

    onLoad(image, call1, call2)
    {
        let element = new Image();

        element.onload = () => {
            setTimeout(()=>{call1()}, 20);
            setTimeout(()=>{call2()}, 100);

            this.inLoad--;
            element.remove()
        };

        this.inLoad++;
        element.src = image;
    }

    attach(elements) {
        this.attached.push(elements);
    }

    _bind() {
        this.on('resize', ()=>{ this.observe() })
            .on('lil.observe', ()=>{ this.observe(); })
            .on('lil.partial', ()=>{ this.fullLoad(); })
            .on('lil.full', ()=>{ this.fullLoad(true); })
            .on('lil.tick', ()=>{
                this.runTimer();
            });

        this.intervalSearch = setInterval(()=>{
            this.trigger('lil.observe');
        }, 3000);
    }

    runTimer(load_all = false, time_out = 50) {
        if (this.stack.length && !this.intervalLoad) {
            this.intervalLoad = setInterval(()=>{
                this.each(load_all);

                if (!this.stack.length) {
                    clearTimeout(this.intervalLoad);
                    this.intervalLoad = null;
                }
            }, time_out);
        }
    }

    toObserver(items = [])
    {
        if (items.length)
        {
            for (let i = 0, len = items.length; i < len; i++) {
                if (items[i].src) {items[i].src = '';}
                this.observer.observe(items[i]);
            }
        }
    }

    toLoad(items = [], full = false)
    {
        clearTimeout(this.timeoutToFullLoad);

        if ((this.inLoad < this.maxLoad) && items.length)
        {
            for (let i = 0, len = items.length; i < len && (full || i <= this.maxLoad) && this.inLoad < this.maxLoad; i++) {
                this.load((i % 2) ? items.pop() : items.shift());
            }
        }

        if (items.length) {
            this.timeoutToFullLoad = setTimeout(()=>{
                this.trigger('lil.partial');
            }, 1000);
        }
    }

    observe()
    {
        let items = this.search();
        if (items.length)
        {
            this.toObserver(items);

            setTimeout(()=>{
                if (this.inLoad === 0) {
                    this.trigger('lil.partial');
                }
            }, 3000)
        }
    }

    fullLoad(full = false)
    {
        this.toLoad(this.search(), full)
    }

    search()
    {
        let query = this.attached.join(',');
        let items = document.querySelectorAll(query);
        if (items.length) {
            return Array.prototype.slice.call(items);
        }
        return [];
    }

    each(load_all = false)
    {
        while (this.stack.length && (this.maxLoad >= this.inLoad || load_all)) {
            ((this.inLoad % 2) ? this.stack.pop():this.stack.shift())();
        }
    }

    /**
     *
     * @param {string} eventName
     * @param {function} callback
     */
    on(eventName, callback) {
        document.addEventListener(eventName, callback);
        return this;
    }

    /**
     *
     * @param {string} eventName
     * @param {object} data
     */
    trigger(eventName, data = {}) {
        document.dispatchEvent(new CustomEvent(eventName, data));
    }
}
