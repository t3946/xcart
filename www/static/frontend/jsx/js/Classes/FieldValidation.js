class FieldValidation {
    /**
     * Construct form
     * @param name
     */
    constructor( element ) {

        this.element = element;
        this.name = this.element.getAttribute( 'name' );
        this.field = this.element.closest( '.form-field' );
    }

    /**
     * Show input errors
     * @param element
     * @param text
     */
    showError( text ) {
        if ( this.field === null ) {
            return;
        }

        let errors = this.field.querySelectorAll( '.errors' );
        this.itemAddError( this.element );
        this.itemAddError( this.field );

        for ( let i = 0; i < errors.length; ++i ) {
            let oneErrorPlace = errors.item( i );
            let oneErrorPlaceText = oneErrorPlace.querySelector( '.form-field-error-text' );

            if ( $(text.parentNode).hasClass('react-component') ) {
                continue;
            }

            oneErrorPlaceText.textContent = text;
            oneErrorPlace.classList.add( 'common-field-error_visible' );

        }
    }

    /**
     * Remove input errors
     * @param element
     */
    removeError( element ) {
        if ( this.field === null ) {
            return;
        }

        let errors = this.field.querySelectorAll( '.errors' );
        // for compound field
        element = element || this.element;

        this.itemRemoveError( element );
        this.itemRemoveError( this.field );

        for ( let i = 0; i < errors.length; ++i ) {
            let oneErrorPlace = errors.item( i );
            let oneErrorPlaceText = oneErrorPlace.querySelector( '.form-field-error-text' );

            if (!oneErrorPlaceText) {
                continue;
            }

            if ( $(oneErrorPlaceText.parentNode).hasClass('react-component') ) {
                continue;
            }

            oneErrorPlaceText.textContent = '';
            oneErrorPlace.classList.remove( 'common-field-error_visible' );
        }
    }

    /**
     * Clear all classes
     * @param element
     */
    clearAllClasses( field, element ) {
        field = field || this.field;
        element = element || this.element;

        if ( field === null || typeof field === 'undefined' ) {
            return;
        }

        element.classList.remove( 'success' );
        field.classList.remove( 'success' );

        this.removeError();
    }

    /**
     * If input was successfully
     * @param element
     */
    success( dispatch = true ) {
        if ( this.field === null ) {
            return;
        }

        this.itemAddSuccess( this.element );

        if ( !this.field.classList.contains( 'compound-field' ) ) {
            this.removeError();
            this.itemAddSuccess( this.field );

            if ( dispatch ) {
                this.dispatchSuccess();
            }
            return;
        }

        let inputs = this.field.querySelectorAll( "input, textarea, select" );

        for ( let i = 0; i < inputs.length; ++i ) {
            let inputElement = inputs.item( i );
            //required success
            if (
                inputElement.classList.contains( 'required' ) && !inputElement.classList.contains( inputElement.dataset.correct || 'success' )
                || !inputElement.classList.contains( 'required' ) && inputElement.classList.contains( inputElement.dataset.wrong || 'invalid' )
            ) {
                return;
            }
        }

        this.removeError( this.element );
        this.itemAddSuccess( this.field );
        if ( dispatch ) {
            this.dispatchSuccess();
        }

    }

    /**
     * Dispatch event form_validation.success if field is valid
     * @param item
     */
    dispatchSuccess() {
        let detail = {
            'field': this
        };
        let event = new CustomEvent( 'form_validation.success', { 'detail': detail } );
        this.element.dispatchEvent( event );
    }

    /**
     * Add error identifier to field
     * @param item
     */
    itemAddError( item ) {
        //toggle input classes
        item.classList.remove( item.dataset.correct || 'success' );
        item.classList.add( item.dataset.wrong || 'invalid' );

        //toggle field classes if need custom styling
        if (item.dataset.correct) {
            $(item).parents('.checkout-field-row').find('.checkout-field-title').removeClass( 'field__correct' );
            $(item).parents('.checkout-field-row').find('.checkout-field-title').addClass( 'field__has-error' );
            $(item).parents('.field').removeClass( 'field__correct' );
            $(item).parents('.field').addClass( 'field__has-error' );
        }
    }

    /**
     * Add success identifier to field
     * @param item
     */
    itemAddSuccess( item ) {
        //toggle input classes
        item.classList.remove( item.dataset.wrong || 'invalid' );
        item.classList.add( item.dataset.correct || 'success' );

        //toggle field classes if need custom styling
        if (item.dataset.correct) {
            $(item).parents('.checkout-field-row').find('.checkout-field-title').removeClass( 'field__has-error' );
            $(item).parents('.checkout-field-row').find('.checkout-field-title').addClass( 'field__correct' );
            $(item).parents('.field').removeClass( 'field__has-error' );
            $(item).parents('.field').addClass( 'field__correct' );
        }
    }

    /**
     * Remove error identifier from field
     * @param item
     */
    itemRemoveError( item ) {
        item.classList.remove( item.dataset.wrong || 'invalid' );
        $(item).parents('.checkout-field-row').find('.checkout-field-title').removeClass('field__has-error');
        $(item).parents('.field').removeClass('field__has-error');
    }
}

export default ( inputElement ) => {
    return new FieldValidation( inputElement );
}