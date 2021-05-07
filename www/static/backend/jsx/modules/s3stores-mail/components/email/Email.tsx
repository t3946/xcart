import React from "react";
import { EmailListHeaderContainer } from "../../containers/email-list-header/EmailListHeader.container";
import { useDispatch, useSelector } from "react-redux";
import { getItemsCount, getPage } from "../../../../redux/actions/emailActions";
import { EmailListTitle } from "../email-list-title/EmailListTitle";
import { EmailList } from "../email-list/EmailList";
import { firstPage } from "../../ts/consts/pagination.const";

export const Email = () => {
  const dispatch = useDispatch();

  useSelector((state: any) => state.searchOptions);

  dispatch(getPage(firstPage));
  dispatch(getItemsCount());

  return (
    <div>
      <EmailListTitle />
      <EmailListHeaderContainer />
      <EmailList />
    </div>
  );
};
