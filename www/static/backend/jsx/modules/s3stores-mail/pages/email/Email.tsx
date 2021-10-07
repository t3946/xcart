import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useParams } from "react-router-dom";
import { getPage, setLoading } from "../../../../redux/actions";
import { EmailListHeaderContainer } from "../../containers";
import { EmailListTitle } from "@s3stores-mail/components/ordinary/email-list-title/EmailListTitle";
import { EmailListContainer } from "@s3stores-mail/containers/email-list/EmailList.container";
import { StoreDto } from "../../ts/types";
export const Email: React.FC = () => {
  const dispatch = useDispatch();

  const { page }: { page?: string } = useParams();

  const searchParams = useSelector((e: StoreDto) => e.searchOptions);

  const emailPage = useSelector((e: StoreDto) => e.page);
  const listItems = useSelector((e: StoreDto) => e.items);

  useEffect(() => {
    if (Number(page) === emailPage && listItems.length > 1) {
      return;
    }

    dispatch(setLoading());
    const timeOut = setTimeout(() => {
      dispatch(getPage(Number(page), searchParams));
    }, 300);

    return () => {
      clearTimeout(timeOut);
    };
  }, [page]);
  return (
    <div>
      <EmailListTitle />
      <EmailListHeaderContainer />
      <EmailListContainer />
    </div>
  );
};
