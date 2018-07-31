
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

        let errors = this.field.querySelectorAll('.errors');
        this.itemAddError(this.element);
        this.itemAddError(this.field);

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
    removeError(element){
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
    clearAllClasses() {
        this.element.classList.remove('success');
        this.field.classList.remove('success');
        this.removeError();
    }

    /**
     * If input was successfully
     * @param element
     */
    success(){

        this.itemAddSuccess(this.element);

        if(!this.field.classList.contains('compound-field')){

            this.removeError();
            this.itemAddSuccess(this.field);
            this.dispatchSuccess();
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

        this.removeError(element);
        this.itemAddSuccess();
        this.dispatchSuccess();
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