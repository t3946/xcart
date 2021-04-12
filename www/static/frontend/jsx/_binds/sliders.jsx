import { render }         from 'preact';
import SliderProducts     from '@/components/Sliders/SliderProducts/SliderProducts';
import SliderProductsMini from '@/components/Sliders/SliderProductsMini/SliderProductsMini';
import SliderPromo        from '@/components/Sliders/SliderPromo';
import SliderBreadcrumbs  from '@/components/Sliders/SliderBreadcrumbs';

( () => {
    // init sliders
    $( '#promo-slider' ).each( function( i, elem ) {
        const { uri, slides } = elem.dataset;

        if ( slides ) {
            render( <SliderPromo slides={ JSON.parse( slides ) } uri={ uri }/>, elem );
        }
    } );

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
        render( <SliderProducts url={ elem.dataset.url }/>, elem );
    } );

    $( '.breadcrumbs-container' ).each( ( i, elem ) => {
        const breadcrumbsData = JSON.parse( elem.dataset.breadcrumbs );

        if ( breadcrumbsData ) {
            return render( <SliderBreadcrumbs breadcrumbsData={ breadcrumbsData }/>, elem );
        }
    } );
} )();
