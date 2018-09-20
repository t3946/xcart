import isMedia from "../utils/isMedia";

class FieldValidation {
    /**
     * Construct form
     * @param name
     */
    constructor(element) {

        this.element = element;
        this.name = this.element.getAttribute('name');
        this.field = this.element.closest('.form-field');
    }

    /**
     * Show input errors
     * @param element
     * @param text
     */
    showError(text){

        if(this.field === null) {
            return;
        }

        let errors = this.field.querySelectorAll('.errors');
        //console.log(errors);
        this.itemAddError(this.element);
        this.itemAddError(this.field);

        for (let i = 0; i < errors.length; ++i) {
            let oneErrorPlace = errors.item(i);
            let oneErrorPlaceText = oneErrorPlace.querySelector('.error-text');

            oneErrorPlaceText.textContent = text;
            oneErrorPlace.classList.add('show');
            if (i === 0 && !isMedia('large')) {
                this.field.scrollIntoView();
                this.element.focus();
            }
        }
    }

    /**
     * Remove input errors
     * @param element
     */
    removeError(element){

        if(this.field === null) {
            return;
        }

        let errors = this.field.querySelectorAll('.errors');
        // for compound field
        element = element || this.element;

        this.itemRemoveError(element);
        this.itemRemoveError(this.field);

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
    clearAllClasses(field, element) {
        field = field || this.field;
        element = element || this.element;

        if(field === null || typeof field === 'undefined') {
            return;
        }

        element.classList.remove('success');
        field.classList.remove('success');

        this.removeError();
    }

    /**
     * If input was successfully
     * @param element
     */
    success(dispatch = true){

        if(this.field === null) {
            return;
        }

        this.itemAddSuccess(this.element);

        //console.log('compound', this.field.classList.contains('compound-field'));
        if(!this.field.classList.contains('compound-field')){

            this.removeError();
            this.itemAddSuccess(this.field);
            if(dispatch){
                this.dispatchSuccess();
            }
            return;
        }

        let inputs = this.field.querySelectorAll("input, textarea, select");

        for (let i = 0; i < inputs.length; ++i) {
            let inputElement = inputs.item(i);
            //required success
            if( ( inputElement.classList.contains('required') && !inputElement.classList.contains('success') )
                || ( !inputElement.classList.contains('required') && inputElement.classList.contains('invalid') )){
                return;
            }
        }

        this.removeError(this.element);
        this.itemAddSuccess(this.field);
        if(dispatch){
            this.dispatchSuccess();
        }

    }

    /**
     * Dispatch event form_validation.success if field is valid
     * @param item
     */
    dispatchSuccess(){
        let detail = {
            'field' : this
        };
        let event = new CustomEvent('form_validation.success', { 'detail': detail });
        this.element.dispatchEvent(event);
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
     * Add success identifier to field
     * @param item
     */
    itemAddSuccess(item){
        item.classList.remove('invalid');
        item.classList.add('success');
    }

    /**
     * Remove error identifier from field
     * @param item
     */
    itemRemoveError(item){
        item.classList.remove('invalid');
    }
}

export default (inputElement) => {
    return new FieldValidation(inputElement);
}