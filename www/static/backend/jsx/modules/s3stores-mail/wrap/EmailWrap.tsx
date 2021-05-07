import { bindActionCreators } from "redux";
import { getPage } from "../../../redux/actions/emailActions";
import { connect } from "react-redux";
import { EmailListHeaderContainer } from "../containers/email-list-header/EmailListHeader.container";

function mapStateToProps() {
  return (state) => {
    return {
      items: state.items,
    };
  };
}

function mapDispatchToProps() {
  return (dispatch) => {
    return {
      changeValue_1: bindActionCreators(getPage, dispatch),
    };
  };
}
const EmailWrap = connect(
  mapStateToProps(),
  mapDispatchToProps()
)(EmailListHeaderContainer);

export default EmailWrap;
