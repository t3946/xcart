import {h, Component, render} from 'preact';
import _ from 'lodash';
import CustomSelectOptions from "./CustomSelectOptions";

export default class CustomColorOptions extends CustomSelectOptions {

    renderOneItem(item) {
        if(!item.isDisabled()) {
            let id = 'select' + item.value;
            let checked = item.isActive();
            let style = "background-color:" + item.value + ";";
            return (<div>
                <input id={id} name="custom_select_options" value={item.value} type="radio" checked={checked}/>
                <label className="hover-blue color" for={id} data-value={item.value} onClick={() => { item.setActive(); }}>
                    <span className="color-text">
                         <span className="color-icon" style={style} />
                        {item.text}
                    </span>
                </label>
            </div>);
        } else {
            this.title = item.text;
        }
    }

}