import {h, Component, render} from 'preact';
import _ from 'lodash';

export default class CustomSelectOptions extends Component {
    constructor(props) {
        super(props);
        if (props.items === null) {
            return;
        }
    }


    renderOneItem(item) {
        if(!item.isDisabled()) {
            let id = 'select' + item.value;
            let checked = item.isActive();
            return (<div>
                <input id={id} name="custom_select_options" value={item.value} type="radio" checked={checked}/>
                <label className="hover-blue" for={id} data-value={item.value} onClick={() => { item.setActive();}}>{item.text}</label>
            </div>);
        } else {
            this.title = item.text;
        }
    }

    render(props, state) {
        let self = this;
        let options = _.map(props.items, this.renderOneItem.bind(self));
        return (<div>
            <div className="title">{this.title}</div>
            <div className="selector-options-items">{options}</div>
        </div>);
    }

}