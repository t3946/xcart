import _ from 'lodash';
import {h, render, Component} from "preact";
import CustomSelectOptions from './CustomSelectOptions';
import CustomColorOptions from "./CustomColorOptions";
import selectOption from './selectOption';

class CustomSelectField {
    constructor(button, win) {
        this.win = win;
        this.button = button;
        this.processButtonClick = this.processButtonClick.bind(this);
        this.button.addEventListener('click', this.processButtonClick, {'passive' : true});
    }

    processButtonClick(event){
        if(!this.win.classList.contains('opened')) {
            this.openOptions();
        }
    }

    openOptions(){

        let selectId = this.button.dataset.select;
        let select = document.getElementById(selectId);
        let isColor = select.classList.contains('color');
        let options = select.getElementsByTagName('option');
        let items = _.map(options, (el) => selectOption(this.button, el));

        $(this.win).mmodal({
            'windowClass': 'selector-options',
            'setWidth': false,
            'onBeforeOpen': function(container) {

                if(isColor) {
                    render(<CustomColorOptions items={items} callback={this.close.bind(this)} />, container,
                        container.firstChild);
                    return;
                }

                render(<CustomSelectOptions items={items} callback={this.close.bind(this)} />, container,
                    container.firstChild);
            }
        });
    }

    destructor(){
        this.button.removeEventListener('click', this.processButtonClick, {'passive' : true});
    }
}

export default (button, win) => {
    return new CustomSelectField(button, win);
}