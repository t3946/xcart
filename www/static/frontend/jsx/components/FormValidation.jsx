import _ from 'lodash';
import formValidate from 'validate.js/validate';

class FormValidation {

    constructor(name) {

        this.name = name;
        this.constraints = document.formConstraints[name];

        let formSelector = '#' + name;
        this.form = document.querySelector(formSelector);
        this._bind();
    }

    _bind() {
        this.inputs = this.form.querySelectorAll('input, textarea, select');
        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            inputElement.addEventListener('change', this.processChange.bind(this));
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

        this.itemAddError(element);
        this.itemAddError(field);

        for (let i = 0; i < errors.length; ++i) {
            let oneErrorPlace = errors.item(i);
            let oneErrorPlaceText = oneErrorPlace.querySelector('.error-text');
            oneErrorPlaceText.textContent = text;
            oneErrorPlace.classList.add('show');
        }
    }

    removeError(element){
        let field = element.closest('.form-field');
        let errors = field.querySelectorAll('.errors');

        this.itemRemoveError(element);
        this.itemRemoveError(field);

        for (let i = 0; i < errors.length; ++i) {
            let oneErrorPlace = errors.item(i);
            let oneErrorPlaceText = oneErrorPlace.querySelector('.error-text');
            oneErrorPlaceText.textContent = '';
            oneErrorPlace.classList.remove('show');
        }
    }

    success(element){
        let field = element.closest('.form-field');
        this.itemAddSuccess(element);

        if(!field.classList.contains('compound-field')){
            console.log(field, field.classList);
            this.removeError(element);
            this.itemAddSuccess(field);
            console.log('!compound-field success');
            return;
        }

        let inputs = field.querySelectorAll("input, textarea, select");
        for (let i = 0; i < inputs.length; ++i) {
            let inputElement = inputs.item(i);
            if(!inputElement.classList.contains('success')){
                return;
            }
        }

        this.removeError(element);
        this.itemAddSuccess(field);
        console.log('compound-field success');
    }

    itemAddSuccess(item){
        item.classList.remove('hasError');
        item.classList.add('success');
    }

    itemAddError(item){
        item.classList.remove('success');
        item.classList.add('hasError');
    }

    itemRemoveError(item){
        item.classList.remove('hasError');
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