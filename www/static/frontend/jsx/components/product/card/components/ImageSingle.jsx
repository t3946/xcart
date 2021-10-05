import classnames from "classnames";
import BookmarkIcon from "@material-ui/icons/Bookmark";
import { BookmarkBorder } from "@material-ui/icons";

export default class ImageSingle extends Component {
  constructor(props) {
    super(props);
  }

  render(props) {
    const containerClasses = ["images-1", props.classes.container];

    return (
      <div className={classnames(containerClasses)}>
        {props.image}
        <meta itemProp="mpn" content={props.mpn} />

        {props.upc && <meta itemProp="gtin" content={props.upc} />}
      </div>
    );
  }
}
