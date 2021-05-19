export default class InputError extends Component {
    constructor( props ) {
        super( props );
    }

    render( props ) {
        return (
            <div className="common-field-error-wrapper">
                { props.message &&
                <ul id="CheckoutForm_pbc_card_holder_name_errors" className="errors form-field-error form-field__error checkout__error error_checkout common-field-error_visible">
                    <li className="form-field-error-text">{ props.message }</li>
                </ul>
                }
            </div>
        );
    }
}
