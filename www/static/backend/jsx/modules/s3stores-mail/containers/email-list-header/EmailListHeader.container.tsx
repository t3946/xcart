import React from "react";
import { viewPaginateInfo } from "@s3stores-mail/utils";
import { useSelector } from "react-redux";
import { pageSize } from "@s3stores-mail/ts/consts";
import { useHistory, useParams } from "react-router-dom";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailListHeader } from "@s3stores-mail/components/smart/email-list-header/EmailListHeader";

export const EmailListHeaderContainer: React.FC = () => {
  const history = useHistory();

  const { page }: { page?: string } = useParams();

  const thisPage = Number(page);

  const itemsCount: number = useSelector((state: StoreDto) => state.itemsCount);

  const maxPage = Math.ceil(itemsCount / pageSize);

  const getNewPage = (count: number) => {
    history.push(`/admin/forms/email-dashboard/page/${thisPage + count}`);
  };

  const paginate = () => {
    return viewPaginateInfo(thisPage, itemsCount, maxPage, pageSize);
  };

  return (
    <div>
      <EmailListHeader
        paginate={paginate}
        maxPage={maxPage}
        page={thisPage}
        getNewPage={getNewPage}
      />
    </div>
  );
};
