import classnames from "classnames";

export default class LoadMore extends Component {
  constructor(props) {
    super(props);

    this.onNext = this.onNext.bind(this);
  }

  onNext(e) {
    e.preventDefault();

    this.props.onNext();
  }

  render() {
    return (
      <div className={classnames("front-endless-pager", this.props.classes)}>
        <a
          href={this.nextPageUrl}
          className="show-more button yellow-white waves waves-orange"
          itemScope
          itemProp="relatedLink/pagination"
          itemType="http://schema.org/URL"
          onClick={this.onNext}
        >
          <span className="text">Load more</span>
        </a>
      </div>
    );
  }
}
