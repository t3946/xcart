import PageCount       from '@/components/catalog/PageCount';
import classnames      from 'classnames';
import CatalogViewMode from '@/components/catalog/CatalogViewMode';
import {createRef}     from "preact";

export default class StateLine extends Component {
    constructor(props) {
        super(props);

        this.sortingOptions = props.sortingOptions;
        this.currentSortingKey = props.currentSortingKey;
        this.hideSort = props.hideSort;
        this.sortButton = createRef();

        this.state = {
            isOpenSortMenu: false,
        }
    }

    toggleSortList() {
        $(this.sortButton.current).toggleClass('active');

        this.setState({
            isOpenSortMenu: !this.state.isOpenSortMenu
        });
    }

    sortCatalog(e) {
        this.props.onSort(e.target.getAttribute('data-value'));
    }

    sortingOptionsList() {
        if (this.hideSort) {
            return;
        }

        const classes = classnames('action_block', 'sort', {active: this.state.isOpenSortMenu});

        return (
            <div className={classes}>
                <div className="action_button sort">
                    <span className="action">Sort by </span>
                    <span className="active_value show-for-large" onClick={this.toggleSortList.bind(this)}
                          ref={this.sortButton}>
                        {this.sortingOptions[this.currentSortingKey]}
                    </span>
                </div>

                <ul className="options no-bullet">
                    {
                        (() => {
                            const options = [];

                            for (const key in this.sortingOptions) {
                                const option = this.sortingOptions[key];

                                options.push(
                                    <li data-value={key}
                                        className={classnames({active: this.currentSortingKey === key})}
                                        onClick={this.sortCatalog.bind(this)}
                                    >{option}</li>,
                                );
                            }

                            return options;
                        })()
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
                                {this.sortingOptionsList()}
                            </div>

                            <CatalogViewMode/>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
