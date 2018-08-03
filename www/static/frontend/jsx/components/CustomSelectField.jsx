import _ from 'lodash';

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
        //this.win
    }

    destructor(){
        this.button.removeEventListener('click', this.processButtonClick, {'passive' : true});
    }
}

export default (button, win) => {
    return new CustomSelectField(button, win);
}