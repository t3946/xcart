import {h, Component, render} from 'preact';
import _ from 'lodash';
import CustomSelectOptions from "./CustomSelectOptions";

export default class CustomColorOptions extends CustomSelectOptions {

    renderTextWithIcon(item){
        let style = "background-color:" + item.value + ";";
        return (
            <span className="color-info">
                 <span className="color-icon" style={style} />
                 <span className="color-text">{item.text}</span>
            </span>
        );
    }

    renderOneItem(item) {
        if(!item.isDisabled()) {
            let id = 'select' + item.value;
            let checked = item.isActive();
            let content = this.renderTextWithIcon(item);
            return (<div>
                <input id={id} name="custom_select_options" value={item.value} type="radio" checked={checked}/>
                <label className="hover-blue color" for={id} data-value={item.value} onClick={() => { item.setActive(content); }}>
                    {content}
                </label>
            </div>);
        } else {
            this.title = item.text;
        }
    }

}