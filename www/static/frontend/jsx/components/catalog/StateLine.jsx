import PageCount       from '@/components/catalog/PageCount';
import classnames      from 'classnames';
import CatalogViewMode from '@/components/catalog/CatalogViewMode';

export default class StateLine extends Component {
    constructor( props ) {
        super(props);

        this.sortingOptions = props.sortingOptions;
        this.currentSortingKey = props.currentSortingKey;
        this.hideSort = props.hideSort;
    }

    sortingOptionsList() {
        if ( this.hideSort ) {
            return;
        }

        return (
            <div className="action_block sort">
                <div className="action_button sort">
                    <span className="action">Sort by </span>
                    <span className="active_value show-for-large">
                        { this.sortingOptions[ this.currentSortingKey ] }
                    </span>
                </div>

                <ul className="options no-bullet">
                    {
                        ( () => {
                            const options = [];

                            for ( const key in this.sortingOptions ) {
                                const option = this.sortingOptions[ key ];

                                options.push(
                                    <li data-value={ key }
                                        className={ classnames( { active: this.currentSortingKey === key } ) }
                                    >{ option }</li>,
                                );
                            }

                            return options;
                        } )()
                    }
                </ul>
            </div>
        );
    }

    render() {
        return (
            <div className="products-state-line pcont">
                <div className="row">
                    <div className="columns large-3 show-for-large">
                        <div className="page_count_wrap">
                            <PageCount/>
                        </div>
                    </div>
                    <div className="columns small-12 large-9">
                        <div className="actions">
                            <div className="action_group">
                                { this.sortingOptionsList() }
                            </div>

                            <CatalogViewMode/>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
