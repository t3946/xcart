import React from "react";
import { EmailListHeader } from "../../components/email-list-header/EmailListHeader";
import { viewPaginateInfo } from "../../utils/viewPaginateInfo";
import { useDispatch, useSelector } from "react-redux";
import { getPage } from "../../../../redux/actions/emailActions";
import { pageSize } from "../../ts/consts/pagination.const";

export const EmailListHeaderContainer = () => {
  const dispatch = useDispatch();

  const pageValue = useSelector((state: any) => state.page);

  const itemsCount = useSelector((state: any) => state.itemsCount);

  const maxPage = Math.ceil(itemsCount / pageSize);

  const getNewPage = (count: number) => {
    dispatch(getPage(pageValue + count));
  };

  const paginate = () => {
    return viewPaginateInfo(pageValue, itemsCount, maxPage, pageSize);
  };

  return (
    <div>
      <EmailListHeader
        itemsCount={itemsCount}
        paginate={paginate}
        maxPage={maxPage}
        page={pageValue}
        getNewPage={getNewPage}
      />
    </div>
  );
};
