import _ from "lodash";

export default class Loader
{
    constructor(options = {}) {
        this.loaders = 0;
        this.elements = {};
        this.options = _.extend({
            'timeout': 200
        }, options);

        this.timer = null;
        this.timerDetach = null;

        this.init();
    }

    init(element) {

        this.elements['container'] = $(element || "body");
        this.elements['bg'] = $('<div />').addClass('loader-bg');
        this.elements['wrapper'] = $('<div />').addClass('loader-wrapper');
        this.elements['spinner'] = $('<div />').addClass('loader-spinner');
        this.elements['content'] = $('<div />').addClass('loader-container');

        this.elements['bg'].append(this.elements['wrapper']);
        this.elements['wrapper'].append(this.elements['spinner']);
        this.elements['wrapper'].append(this.elements['content']);

        this._bind();
    }

    _bind() {
        this.elements['bg'].on('click', this.allDetach());
    }

    load(callback = null, max_time = 10000) {
        this.attach(max_time);

        if (callback) {
            if (typeof callback === 'function') {
                $
                    .when(callback())
                    .done((args)=>{ if (args) { this.detach(); } })
                    .fail(()=>{ this.detach(); })
                    .then(()=>{  });
            }
            else {
                $
                    .when(callback)
                    .done((args)=>{  })
                    .fail(()=>{  })
                    .then(()=>{ setTimeout(()=>{ this.detach(); }, this.options.timeout) });
            }
        }
    }

    attach(max_time = 10000) {
        if (!this.loaders) {
            this.timer = setTimeout(_=>{
                this.elements['container'].addClass('loading');
                this.elements['container'].append(this.elements['bg']);

                setTimeout(()=>{
                    this.elements['container'].addClass('loading-active');
                }, 20);

                clearTimeout(this.timerDetach);
                this.timerDetach = setTimeout(()=>{
                    this.detach();
                }, max_time);
            },1000);

            this.loaders++;
        }
    }

    detach() {
        this.loaders--;

        if (this.loaders <= 0) {
            clearTimeout(this.timer);

            this.elements['container'].removeClass('loading-active');

            setTimeout(()=>{
                this.elements['container'].removeClass('loading');
                this.elements['bg'].detach();
            }, this.options.timeout);

            if (this.loaders < 0) {
                this.loaders = 0;
            }
        }
    }

    allDetach() {
        this.loaders = 1;
        this.detach();
    }
}