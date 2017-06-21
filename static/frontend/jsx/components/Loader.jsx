import _ from "lodash";

export default class Loader
{
    constructor(options = {}) {
        this.loaders = 0;
        this.elements = {};
        this.options = _.extend({
            'timeout': 200
        }, options);


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
    }

    load(callback = null) {
        this.attach();

        setTimeout(()=>{
            this.elements['container'].addClass('loading-active');
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
        }, 20);
    }

    attach() {
        if (!this.loaders) {
            this.elements['container'].addClass('loading');
            this.elements['container'].append(this.elements['bg']);

            this.loaders++;
        }
    }

    detach() {
        this.loaders--;

        if (this.loaders === 0) {
            this.elements['container'].removeClass('loading-active');

            setTimeout(()=>{
                this.elements['container'].removeClass('loading');
                this.elements['bg'].detach();
            }, this.options.timeout)
        }
    }
}