import formValidate from 'validate.js/validate';
import fieldValidation from "./FieldValidation";

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
        this.fields = {};
        this._bind();
    }

    /**
     * Bind events to fields
     * @private
     */
    _bind() {
        this.inputs = this.form.querySelectorAll('input, textarea, select');
        this.processChange = this.processChange.bind(this);
        this.processJsChange = this.processJsChange.bind(this);
        this.checkAllForm = this.checkAllForm.bind(this);

        this.form.addEventListener('submit', this.checkAllForm);
        this.form.addEventListener('js.submit.event', this.checkAllForm);

        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            let type = inputElement.getAttribute('type');
            if(type === 'hidden') {
                continue;
            }
            let field = fieldValidation(inputElement);
            let inputElementName = inputElement.getAttribute('name');
            this.fields[inputElementName] = field;

            inputElement.addEventListener('blur', this.processChange);
            inputElement.addEventListener('js.change.event', this.processJsChange);
        }
    }

    /**
     * Process the change event
     * @param event
     */
    processChange(event){

        if(event.explicitOriginalTarget !== null
            && typeof event.explicitOriginalTarget !== 'undefined'
            && typeof event.explicitOriginalTarget.classList !== 'undefined'
            && event.explicitOriginalTarget.classList.contains('clear-input')){

            let clearContainer = event.explicitOriginalTarget.closest('.input-container');
            let eventContainer = event.target.closest('.input-container');

            if(clearContainer === eventContainer){
                return;
            }
        }

        this.checkForm(event.target);
    }

    processJsChange(event){
        this.checkForm(event.detail.element);
    }

    checkForm(inputElement){

        let inputElementName = inputElement.getAttribute('name');
        let currentRules = this.constraints[inputElementName];
        let field = this.fields[inputElementName];

        if(typeof currentRules === 'undefined' || typeof currentRules.presence === 'undefined'){
            if(inputElement.value === '') {
                field.clearAllClasses();
                return;
            }
        }

        let errors = formValidate(this.form, this.constraints) || {};
        let currentError = errors[inputElementName];

        if(typeof currentError === 'undefined' || currentError.length <= 0) {
            field.success();
            return;
        }

        field.showError(currentError[0]);
    }

    checkAllForm(event){

        let errors = formValidate(this.form, this.constraints) || {};
        let hasErrors = false;

        for (let inputElementName in this.fields) {
            //console.log(this.fields);
            let field = this.fields[inputElementName];
            let currentError = errors[inputElementName];
            field.clearAllClasses();

            if(typeof currentError === 'undefined' || currentError.length <= 0) {
                field.success();
                continue;
            }

            field.showError(currentError[0]);
            hasErrors = true;
        }
        //console.log(hasErrors);
        if(hasErrors) {
            event.preventDefault();
            event.stopPropagation();
        }
    }


    /**
     * Destruct form validator
     */
    destructor(){
        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            inputElement.removeEventListener('blur', this.processChange);
            inputElement.removeEventListener('js.change.event', this.processJsChange);
        }

        this.form.removeEventListener('submit', this.checkAllForm);
        this.form.removeEventListener('js.submit.event', this.checkAllForm);

        this.fields = {};
    }
}

export default (name) => {
    return new FormValidation(name);
}
