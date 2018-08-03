import _ from 'lodash';
import {h, render, Component} from "preact";
import CustomSelectOptions from './CustomSelectOptions';
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
        let options = select.getElementsByTagName('option');
        let items = _.map(options, selectOption);

        $(this.win).mmodal({
            'windowClass': 'selector-options',
            'setWidth': false,
            'onBeforeOpen': function(container){
                render(<CustomSelectOptions items={items} />,
                    container, container.firstChild);
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