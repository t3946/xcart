class DepartmentMenu
{
    constructor() {
        this.init();
    }

    init() {
        this.timers = {};
        this.elemets = {};
        this.options = {
            hoverDelay: 1500,
            classes: {
                'main-button': '.category-menu',
                'menu-wrapper': '.category-menu-list-wrapper',
                'menu-container': '.category-menu-list-container',
                'menu-item': '.category-menu-item',
            }
        };

        this.hasTouch = 'ontouchstart' in window || (typeof window.ontouchstart !== 'undefined');

        this._bind();
    }

    _bind() {
        this.elemets['button'] = $(this.options.classes['main-button']);
        this.elemets['container'] = $(this.options.classes['menu-container']);
        this.elemets['wrapper'] = $(this.options.classes['menu-wrapper']);
        this.elemets['items'] = this.elemets['container'].find(this.options.classes['menu-item']);

        this.elemets['button'].on('mouseenter touchstart', (e) => {
            clearTimeout(this.timers['_hide']);
            this._show_menu();
        });

        this.elemets['container'].on('mouseenter touchstart', (e) => {
            clearTimeout(this.timers['_hide']);
        });

        this.elemets['button'].on('mouseleave', (e) => {
            this.timers['_hide'] = setTimeout(()=> {
                this._hide();
            }, this.options.hoverDelay)
        });

        this.elemets['container'].on('mouseleave', (e) => {
            this.timers['_hide'] = setTimeout(()=> {
                this._hide();
            }, this.options.hoverDelay)
        });

        this.elemets['items'].on('mouseenter touchstart', (e) => {
            this._hide_items();

            let $target = $(e.target);
            if (!$target.hasClass(this.options.classes['menu-item'])) {
                $target = $target.closest(this.options.classes['menu-item']);
            }

            $('#' + $target.data('hover-toggle')).removeClass('hide');
            this.elemets['container'].addClass('submenu-active');
        });


        // this.elemets['wrapper'].on('click', () => {
        //     clearTimeout(this.timers['_hide']);
        //     this._hide();
        // });

        $(document).on('click:shadow', (e)=>{
            clearTimeout(this.timers['_hide']);
            this._hide();
        });

        // if (this.hasTouch) {
        //     this.elemets['button'].on('click', (e) => { e.preventDefault()});
        //     this.elemets['items'].on('click', (e) => { e.preventDefault()});
        // }

    }

    _show_menu() {
        this.elemets.wrapper.removeClass('hide');
        this.elemets.wrapper.addClass('is-active');
        this.elemets.button.addClass('is-active');

        $(document).trigger('show:dm');
    }

    _hide_items() {
        for (let i = 0; i < this.elemets['items'].length; i++)
        {
            let tid =  $(this.elemets['items'][i]).data('hover-toggle');
            $('#' + tid).addClass('hide');
        }
    }

    _hide() {
        this.elemets.wrapper.addClass('hide');
        this.elemets.wrapper.removeClass('is-active');
        this.elemets.button.removeClass('is-active');
        this.elemets.container.removeClass('submenu-active');
        this._hide_items();

        $(document).trigger('hide:dm');
    }
}

// export default new DepartmentMenu();