import { h, Component, render } from 'preact';
import _ from 'lodash';

export default class PreactSlySlide extends Component
{
    constructor(...args) {
        super(...args);

        this.refs = {};
        this['$refs'] = {};

        this.options = _.extend({
            horizontal: 1,
            itemNav: 'basic',
            speed: 300,
            mouseDragging: 1,
            touchDragging: 1,
            activatePageOn: 'click',
            onSlideActive: null
        }, args[0].options || {});
    }

    componentDidMount() {
        this.$refs.wrap = $(this.refs.wrap);
        this.$refs.wrap.sly(this.options);
        if(this.options.onSlideActive) {
            this.$refs.wrap.sly('on', 'active', _.throttle(this.options.onSlideActive, 200));
        }


        window.addEventListener('resize', this.onResize.bind(this));
    }

    componentWillReceiveProps(props, prev)
    {
        if (this.$refs.wrap) {
            this.$refs.wrap.sly('activate', props.pos);
            // this.$refs.wrap.sly('slideTo', this.$refs.wrap.sly.items[props.pos].center);
            // this.$refs.wrap.sly.slideTo(props.pos);
        }
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.onResize)
    }

    slyReload() {
        this.$refs.wrap.sly('reload');
    }

    onResize() {
        _.throttle(this.slyReload, 200);
    }

    render({children}) {
        return <div className={'wrap'} ref={el => this.refs.wrap = el }>
            {children}
        </div>;
    }
}