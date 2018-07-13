import _ from 'lodash';
import formValidate from "validate.js/validate";

class FormValidation {

    constructor(name) {

        this.name = name;
        this.constraints = document.formConstraints[name];

        let formSelector = "#" + name;
        this.form = document.querySelector(formSelector);
        this._bind();
    }

    _bind() {
        this.inputs = this.form.querySelectorAll("input, textarea, select");
        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            inputElement.addEventListener("change", this.processChange.bind(this));
        }
    }

    processChange(event){

        console.log(event);

        let inputElement = event.target;
        let inputElementName = inputElement.getAttribute('name');
        let errors = formValidate(this.form, this.constraints) || {};
        let currentError = errors[inputElementName];

        if(typeof currentError === 'undefined' || currentError.length <= 0) {
            this.success(inputElement);
            return;
        }

        this.showError(inputElement, currentError[0]);
    }

    showError(element, text){
        let field = element.closest('.form-field');
        let errors = field.querySelectorAll('.errors');

        element.classList.add('hasError');
        field.classList.add('hasError');

        for (let i = 0; i < errors.length; ++i) {
            let oneErrorPlace = errors.item(i);
            let oneErrorPlaceText = oneErrorPlace.querySelector('.error-text');
            oneErrorPlaceText.textContent = text;
            oneErrorPlace.classList.add('visible');
        }
    }

    success(element){
        let field = element.closest('.form-field');
        element.classList.add('success');

        if(!field.classList.contains('compound-field')){
            field.classList.add('success');
            return;
        }

        let inputs = field.querySelectorAll("input, textarea, select");
        for (let i = 0; i < inputs.length; ++i) {
            let inputElement = inputs.item(i);
            if(!inputElement.classList.contains('success')){
                return;
            }
        }

        field.classList.add('success');
    }

    itemAddSuccess(){
        field.classList.add('success');
    }

    destructor(){
        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            inputElement.removeEventListener('change', this.processChange.bind(this));
        }
    }
}

export default (name) => {
    new FormValidation(name);
}