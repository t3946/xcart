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
            activatePageOn: 'click'
        }, args[0].options||{});
    }

    componentDidMount() {

        console.log(this.options);

        this.$refs.wrap = $(this.refs.wrap);
        this.$refs.wrap.sly(this.options);
    }

    render({children}) {
        return <div className={'wrap'} ref={(el) => this.refs.wrap = el }>
            {children}
        </div>;
    }
}