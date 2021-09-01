import classnames     from 'classnames';
import CatalogContext from '@/components/catalog/CatalogContext';

export default class CatalogViewMode extends Component {
    constructor() {

        super();

        this.LIST_MODE = 'list';
        this.TILE_MODE = 'tile';
    }

    setMode( e, mode ) {
        e.preventDefault();

        //update view mode in state and locale storage
        this.context.onViewModeChange( mode );
    }

    render() {
        const mode = this.context.viewMode || this.TILE_MODE;

        return (
            <div className="action_block view">
                <span className="show-for-large">View as</span>
                <a
                    onClick={ ( e ) => this.setMode( e, this.TILE_MODE ) }
                    href="#"
                    className={ classnames( 'tile-view', { active: mode === this.TILE_MODE } ) }
                    data-value="tile-view"
                />
                <a
                    onClick={ ( e ) => this.setMode( e, this.LIST_MODE ) }
                    href="#"
                    className={ classnames( 'list-view', { active: mode === this.LIST_MODE } ) }
                    data-value="list-view"
                />
            </div>
        );
    }
}

CatalogViewMode.contextType = CatalogContext;
