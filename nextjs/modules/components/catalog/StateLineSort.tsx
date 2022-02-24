import classnames from 'classnames';

export default class StateLineSort extends Component {
    constructor() {
        super();
    }

    render() {
        return (
            <div className="state-line-sort">
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
}
