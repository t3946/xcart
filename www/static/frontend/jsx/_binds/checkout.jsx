import Checkout from '@/components/Checkout/Checkout';
import { render } from 'preact';

export default ( () => {
    const $targets = $( '[data-component=checkout]' );

    $targets.each((i) => {
        const elem =  $targets[i];

        render( <Checkout />, elem );
    });
} )();
