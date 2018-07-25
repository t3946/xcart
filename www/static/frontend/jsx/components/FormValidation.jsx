import _ from 'lodash';
import formValidate from 'validate.js/validate';

class FormValidation {

    /**
     * Construct form
     * @param name
     */
    constructor(name) {

        this.name = name;
        this.constraints = document.formConstraints[name];

        let formSelector = '#' + name;
        this.form = document.querySelector(formSelector);
        this._bind();
    }

    /**
     * Bind events to fields
     * @private
     */
    _bind() {
        this.inputs = this.form.querySelectorAll('input, textarea, select');
        this.processChange = this.processChange.bind(this);
        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            inputElement.addEventListener('blur', this.processChange);
        }
    }

    /**
     * Process the change event
     * @param event
     */
    processChange(event){

        if(event.explicitOriginalTarget !== null && event.explicitOriginalTarget.classList.contains('clear-input')){
            return;
        }

        let inputElement = event.target;
        let inputElementName = inputElement.getAttribute('name');
        let currentRules = this.constraints[inputElementName];

        if(typeof currentRules === 'undefined' || typeof currentRules.presence === 'undefined'){
            if(inputElement.value === '') {
                this.clearAllClasses(inputElement);
                return;
            }
        }

        let errors = formValidate(this.form, this.constraints) || {};
        let currentError = errors[inputElementName];

        if(typeof currentError === 'undefined' || currentError.length <= 0) {
            this.success(inputElement);
            return;
        }

        this.showError(inputElement, currentError[0]);
    }

    /**
     * Show input errors
     * @param element
     * @param text
     */
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

    /**
     * Remove input errors
     * @param element
     */
    removeError(element, field){
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

    /**
     * Clear all classes
     * @param element
     */
    clearAllClasses(element) {
        let field = element.closest('.form-field');

        element.classList.remove('success');
        field.classList.remove('success');

        this.removeError(element, field);
    }

    /**
     * If input was successfully
     * @param element
     */
    success(element){
        let field = element.closest('.form-field');
        this.itemAddSuccess(element);

        if(!field.classList.contains('compound-field')){
            //console.log(field, field.classList);
            this.removeError(element, field);
            this.itemAddSuccess(field);
            //console.log('!compound-field success');
            return;
        }

        let inputs = field.querySelectorAll("input, textarea, select");

        for (let i = 0; i < inputs.length; ++i) {
            let inputElement = inputs.item(i);
            //required success
            if( ( inputElement.classList.contains('required') && !inputElement.classList.contains('success') )
            || ( !inputElement.classList.contains('required') && inputElement.classList.contains('invalid') )){
                return;
            }
        }

        this.removeError(element, field);
        this.itemAddSuccess(field);
        //console.log('compound-field success');
    }

    /**
     * Add success identifier to field
     * @param item
     */
    itemAddSuccess(item){
        item.classList.remove('invalid');
        item.classList.add('success');
        let detail = {
            'value' : item.value,
            'callback' : this.success
        };
        let event = new CustomEvent('form_validation.success', { 'detail': detail });
        item.dispatchEvent(event);
    }

    /**
     * Add error identifier to field
     * @param item
     */
    itemAddError(item){
        item.classList.remove('success');
        item.classList.add('invalid');
    }

    /**
     * Remove error identifier from field
     * @param item
     */
    itemRemoveError(item){
        item.classList.remove('invalid');
    }

    /**
     * Destruct form validator
     */
    destructor(){
        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            inputElement.removeEventListener('blur', this.processChange);
        }
    }
}

export default (name) => {
    new FormValidation(name);
}
