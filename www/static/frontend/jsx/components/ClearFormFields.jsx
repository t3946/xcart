class ClearFormFields {

    /**
     * Construct form
     * @param name
     */
    constructor(name) {

        this.name = name;
        this.constraints = document.formConstraints[name];

        let formSelector = '#' + name;
        this.form = document.querySelector(formSelector);
        this.closeButtons = [];
        this._bind();
    }

    /**
     * Bind events to fields
     * @private
     */
    _bind() {
        this.inputs = this.form.querySelectorAll('.input-container[data-clear]');
        for (let i = 0; i < this.inputs.length; ++i) {
            let inputContainer = this.inputs.item(i);
            let closeButton = document.createElement('a');
            let input = inputContainer.querySelector('input');
            closeButton.classList.add('clear-input');
            //closeButton.setAttribute("pseudo", "-webkit-search-cancel-button");
            inputContainer.append(closeButton);


            this.clearFieldListener = this.clearField.bind(this, input);
            this.processChange = this.processChange.bind(this);
            closeButton.addEventListener('click', this.clearFieldListener, {'passive': true});
            input.addEventListener('keyup', this.processChange, {'passive': true});

            this.closeButtons.push({
                'input': input,
                'button': closeButton
            });
        }
    }

    /**
     * Process the click on clear button event
     * @param event
     */
    clearField(input, event){

        // event.stopPropagation();
        // event.stopImmediatePropagation();
        // event.preventDefault();

        let closeElement = event.target;
         let wrapper = closeElement.closest('.input-container');

         input.value = '';
         input.focus();
         wrapper.classList.remove('hasClose');

         //return false;
    }


    /**
     * Process the input change event
     * @param event
     */
    processChange(event){

        //event.stopPropagation();
        //event.preventDefault();

        let inputElement = event.target;
         let wrapper = inputElement.closest('.input-container');
         if(inputElement.value !== '') {
             wrapper.classList.add('hasClose');
         } else {
             wrapper.classList.remove('hasClose');
         }

        //return false;
    }


    /**
     * Destruct clear form
     */
    destructor(){
        for (let i = 0; i < this.closeButtons.length; ++i) {
            let info = this.closeButtons.item(i);
            info['button'].removeEventListener('click', this.clearFieldListener, {'passive': true});
            info['input'].removeEventListener('keyup', this.processChange, {'passive': true});//, {'passive': false}
            info['button'].remove();
        }

        this.closeButtons = [];
    }
}

export default (name) => {
    new ClearFormFields(name);
}