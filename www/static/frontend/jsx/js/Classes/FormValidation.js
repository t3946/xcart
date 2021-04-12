import formValidate from 'validate.js/validate';
import fieldValidation from "@/js/Classes/FieldValidation";

export default class FormValidation {
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
        this.errors = [];
        this._bind();
        this.hasErrors = false;

        for ( let i = 0; i < this.form.length; i++ ) {
            const input = this.form[i];

            if ( input.type === 'text' && input.value.length ) {
                this.checkForm(input);
            }
        }
    }

    /**
     * Bind events to fields
     * @private
     */
    _bind() {
        this.inputs = this.form.querySelectorAll('input, textarea, select');
        this.processChange = this.processChange.bind(this);
        this.processJsChange = this.processJsChange.bind(this);
        this.validateOnSubmit = this.validateOnSubmit.bind(this);

        this.form.addEventListener('submit', this.validateOnSubmit);

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

            inputElement.addEventListener( 'change', this.processChange );

            inputElement.addEventListener('js.change.event', this.processJsChange);
        }
    }

    /**
     * Process the change event
     * @param event
     */
    processChange(event){
        if (
            event.explicitOriginalTarget !== null
            && typeof event.explicitOriginalTarget !== 'undefined'
            && typeof event.explicitOriginalTarget.classList !== 'undefined'
            && event.explicitOriginalTarget.classList.contains('clear-input')
        ){
            let clearContainer = event.explicitOriginalTarget.closest('.input-container');
            let eventContainer = event.target.closest('.input-container');

            if(clearContainer === eventContainer){
                return;
            }
        }

        if (
            event.target.id === 'CheckoutForm_s_address'
        ) {
            this.checkForm( CheckoutForm_s_country );
            this.checkForm( CheckoutForm_s_zipcode );
            this.checkForm( CheckoutForm_s_state );
            this.checkForm( CheckoutForm_s_city );
        }

        if (
            event.target.id === 'CheckoutForm_b_address'
        ) {
            this.checkForm( CheckoutForm_b_country );
            this.checkForm( CheckoutForm_b_zipcode );
            this.checkForm( CheckoutForm_b_state );
            this.checkForm( CheckoutForm_b_city );
        }

        this.checkForm(event.target);
    }

    processJsChange(event){
        this.checkForm(event.detail.element);
    }

    /**
     * validate input
     * @return boolean return true if field is valid else false
     */
    checkForm(inputElement){
        if(this.form.getAttribute('data-validate') !== 'true'){
            return true;
        }

        let inputElementName = inputElement.getAttribute('name');
        let currentRules = this.constraints[inputElementName];
        let field = this.fields[inputElementName];

        if(typeof currentRules === 'undefined' || typeof currentRules.presence === 'undefined'){
            if(inputElement.value === '') {
                field.clearAllClasses();
                return true;
            }
        }

        let errors = formValidate(this.form, this.constraints) || {};
        let currentError = errors[inputElementName];

        if(typeof currentError === 'undefined' || currentError.length <= 0) {
            field.success();
            return true;
        }

        field.showError(currentError[0]);

        return false;
    }

    scrollToFirstError() {
        console.log('FORM SUBMIT VALIDATION ERRORS: ', this.errors);

        if (this.errors.length) {
            let field = this.errors.shift();

            window.scrollTo( {
                top: field.field.offsetTop,
                behavior: "smooth"
            } );

            field.element.focus( { preventScroll: true } );
        }
    }

    validateOnSubmit(event){
        event.preventDefault();
        event.stopPropagation();
        this.hasErrors = false;
        this.checkAllForm();

        if(typeof this.hasErrors !== 'undefined' && this.hasErrors) {
            event.preventDefault();
            event.stopPropagation();
            this.scrollToFirstError();
        }
    }

    /**
     * get field that must be have validate
     */
    getValidatingFields() {
        return this.fields;
    }

    checkAllForm(){
        //need validate
        if(this.form.getAttribute('data-validate') !== 'true'){
            return;
        }

        let errors = formValidate(this.form, this.constraints) || {};
        this.hasErrors = false;
        this.errors = [];

        const fields = this.getValidatingFields();

        //fields circle
        for (let inputElementName in fields) {
            let field = this.fields[inputElementName];
            let currentError = errors[inputElementName];
            let currentRules = this.constraints[inputElementName];

            if(typeof currentRules === 'undefined' || typeof currentRules.presence === 'undefined'){
                if(field.element.value === '') {
                    if(!field.element.classList.contains('compound-field')) {
                        field.clearAllClasses();
                        continue;
                    }

                    let hasNoValue = true;

                    this.inputs = this.form.querySelectorAll('input, textarea, select');

                    for (let i = 0; i < this.inputs.length; ++i) {
                        let inputElement = this.inputs.item(i);

                        if(inputElement.value !== '') {
                            hasNoValue = false;
                        }
                    }

                    if(
                        hasNoValue && !(field.field.classList.contains('invalid')
                        || field.field.classList.contains('success'))
                    ) {
                        field.clearAllClasses();
                        continue;
                    }
                }
            }

            field.clearAllClasses();

            if(typeof currentError === 'undefined' || currentError.length <= 0) {
                field.success(false);
                continue;
            }

            field.showError( currentError[ 0 ] );
            this.errors.push(field);
            this.hasErrors = true;
        }
    }

    /**
     * Destruct form validator
     */
    destructor(){
        const self = this;

        for (let i = 0; i < this.inputs.length; ++i) {
            let inputElement = this.inputs.item(i);
            inputElement.removeEventListener('blur', this.processChange);
            inputElement.removeEventListener( 'change', this.processChange );
            inputElement.removeEventListener('js.change.event', this.processJsChange);
        }

        this.form.removeEventListener('submit', this.validateOnSubmit);
        this.fields = {};
    }
}
