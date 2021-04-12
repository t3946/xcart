import ImageComplex from './ImageComplex';
import ImageNo      from './ImageNo';
import ImageSingle  from './ImageSingle';
import classnames   from 'classnames';

export default class Image extends Component {
    constructor( props ) {
        super( props );
    }

    render( { images, mpn, upc, url, name, classes, isNew, isSale } ) {
        let containerClasses = [ 'products-slider__image-container', 'products-slider-image-container', 'container' ];
        let linkClasses = ['products-slider-image-link'];

        //extend default classes
        if ( classes ) {
            containerClasses.push( classes.container );
            linkClasses.push( this.props.classes.link );
        }

        return (
            <div className={ classnames( containerClasses ) }>
                <a href={ url } title={ name } className={classnames(linkClasses)}>
                    { ( () => {
                        if ( images.length === 0 ) {
                            return ( <ImageNo { ...{ upc, mpn } } /> );
                        }
                        else if ( images.length === 1 ) {
                            return ( <ImageSingle { ...{ image: images[ 0 ], upc, mpn } } /> );
                        }
                        else {
                            return ( <ImageComplex { ...{ images, upc, mpn } }/> );
                        }
                    } )() }

                    { isNew &&
                    <span className="splash image-splash image-splash__new show-for-large image_splash">New</span>
                    }

                    { isSale &&
                    <span className="splash image-splash image-splash__sale show-for-large image_splash">Sale</span>
                    }
                </a>
            </div>
        );
    }
}