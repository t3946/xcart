import React from "react";
import EmailListSearch from "@s3stores-mail/components/smart/email-list-search/EmailListSearch";
import { useDispatch, useSelector } from "react-redux";
import { useHistory, withRouter } from "react-router-dom";
import { editSearchOptions } from "@redux/actions";
import { StoreDto } from "@s3stores-mail/ts/types";

const EmailSearchContainer: React.FC = () => {
  const history = useHistory();

  const dispatch = useDispatch();

  const searchOptions = useSelector((state: StoreDto) => state.searchOptions);

  const editSearchSubject = (value) => {
    dispatch(
      editSearchOptions({
        ...searchOptions,
        subject: value,
      })
    );
    history.push(`/admin/forms/email-dashboard/page/${1}`);
  };
  return (
    <EmailListSearch
      editSearchSubject={editSearchSubject}
      subject={searchOptions.subject}
    />
  );
};

export default withRouter(EmailSearchContainer);
