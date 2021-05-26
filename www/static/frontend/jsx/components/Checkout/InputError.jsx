import classnames from "classnames";

export default class InputError extends Component {
    constructor( props ) {
        super( props );
    }

    render( props ) {
        const listClasses = [
            'errors',
            'form-field-error',
            'form-field__error',
            'checkout__error',
            'error_checkout',
            'common-field-error_visible',
            //для предотвращения работы с этим компонентов старой системы валидации
            'react-component',
        ]

        return (
            <div className="common-field-error-wrapper">
                { props.message &&
                <ul id="CheckoutForm_pbc_card_holder_name_errors" className={classnames(listClasses)}>
                    <li className="form-field-error-text">{ props.message }</li>
                </ul>
                }
            </div>
        );
    }
}
