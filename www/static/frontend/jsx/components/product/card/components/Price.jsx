import { Fragment } from 'preact';

export default function Price( { currency, price } ) {
    return (
        <Fragment>
            { currency.symbol_prefix }
            { !currency.after && currency.currency }
            <span className="price"> { price }</span>
            { currency.after && currency.currency }
        </Fragment>
    );
}