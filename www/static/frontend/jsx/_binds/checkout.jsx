import Checkout from '@/components/Checkout/Checkout';
import { render } from 'preact';

export default ( () => {
    const $targets = $( '[data-component=checkout]' );

    if ($targets.length === 0) {
        return;
    }

    $targets.each((i) => {
        const elem =  $targets[i];

        render( <Checkout />, elem );
    });
} )();
