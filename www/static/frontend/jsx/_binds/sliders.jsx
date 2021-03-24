import { render }         from 'preact';
import SliderProducts     from '@/components/SliderProducts/SliderProducts';
import SliderProductsMini from '@/components/SliderProductsMini/SliderProductsMini';

( () => {
    // init product sliders

    $( '.slider-bestsellers .slider-data' ).each( function( i, elem ) {
        render( <SliderProducts url={ elem.dataset.url }/>, elem );
    } );

    $( '.slider-featured-product .slider-data' ).each( function( i, elem ) {
        render( <SliderProducts url={ elem.dataset.url }/>, elem );
    } );

    $( '.slider-new .slider-data' ).each( function( i, elem ) {
        render( <SliderProducts url={ elem.dataset.url }/>, elem );
    } );

    $( '.slider-related .slider-data' ).each( function( i, elem ) {
        render( <SliderProducts url={ elem.dataset.url }/>, elem );
    } );

    $( '.slider-also_bought .slider-data' ).each( function( i, elem ) {
        render( <SliderProducts url={ elem.dataset.url }/>, elem );
    } );

    $( '.slider-viewed .slider-data' ).each( function( i, elem ) {
        render( <SliderProductsMini url={ elem.dataset.url }/>, elem );
    } );
} )();
