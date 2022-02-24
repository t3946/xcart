import customSelectField from "./custom_select/CustomSelectField";

class CreateSelectFields {
    constructor(name) {

        let formSelector = '#' + name;

        this.name = name;
        this.form = document.querySelector(formSelector);

        if(this.form === null) {
            return;
        }

        let hideWrapper = document.createElement('div');
        let optionsContainer = document.createElement('div');

        hideWrapper.classList.add('mmodal-hide');
        optionsContainer.classList.add('options-container');

        hideWrapper.appendChild(optionsContainer);
        this.form.appendChild(hideWrapper);
        this.selectsObj = [];

        this._bind(optionsContainer);
    }

    _bind(optionsContainer){

        let selects = this.form.querySelectorAll('.select-visible-button');

        for (let oneSelect of selects) {
            this.selectsObj.push(customSelectField(oneSelect, optionsContainer));
        }
    }



    destructor(){
        for (let oneSelectsObj of this.selectsObj) {
            oneSelectsObj.destructor();
        }

        this.selectsObj = [];
    }
}

export default (name) => {
    return new CreateSelectFields(name);
}
