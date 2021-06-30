import React, { useContext } from "react";
import EmailListSearch from "@s3stores-mail/components/smart/email-list-search/EmailListSearch";
import { useDispatch, useSelector } from "react-redux";
import { useHistory, withRouter } from "react-router-dom";
import { editSearchOptions, getPage, setLoading } from "@redux/actions";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailRouterContext } from "../../contexts/email-router-context/EmailRouter.context";

const EmailSearchContainer: React.FC = () => {
  const history = useHistory();

  const dispatch = useDispatch();

  const searchOptions = useSelector((state: StoreDto) => state.searchOptions);

  const routers = useContext(EmailRouterContext);

  const editSearchSubject = (value) => {
    dispatch(
      editSearchOptions({
        ...searchOptions,
        subject: value,
      })
    );
    history.push(`${routers.listRouter}${1}`);
    dispatch(setLoading());
    dispatch(
      getPage(Number(1), {
        ...searchOptions,
        subject: value,
      })
    );
  };
  return (
    <EmailListSearch
      editSearchSubject={editSearchSubject}
      subject={searchOptions.subject}
    />
  );
};

export default withRouter(EmailSearchContainer);
