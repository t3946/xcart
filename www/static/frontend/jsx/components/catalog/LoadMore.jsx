import classnames from "classnames";

export default class LoadMore extends Component {
  constructor(props) {
    super(props);

    this.onNext = this.onNext.bind(this);
  }

  onNext(e) {
    e.preventDefault();

    if (this.props.isLoading === true) {
      //already loading
      return;
    }

    this.props.onNext();
  }

  render() {
    return (
      <div className={classnames("front-endless-pager", this.props.classes)}>
        {(this.props.next || this.props.isLoading) && (
          <a
            href={this.next}
            className="show-more button yellow-white waves waves-orange"
            itemScope
            itemProp="relatedLink/pagination"
            itemType="http://schema.org/URL"
            onClick={this.onNext}
          >
            <span className="text">Load more</span>
          </a>
        )}
      </div>
    );
  }
}
