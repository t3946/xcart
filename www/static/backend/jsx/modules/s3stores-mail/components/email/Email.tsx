import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useParams } from "react-router-dom";
import { getPage, setLoading } from "@redux/actions/emailActions";
import { EmailListHeaderContainer } from "@s3stores-mail/containers";
import { EmailListTitle } from "@s3stores-mail/components/email-list-title/EmailListTitle";
import { EmailList } from "@s3stores-mail/components/email-list/EmailList";
import { StoreDto } from "@s3stores-mail/ts/types";
export const Email: React.FC = () => {
  const dispatch = useDispatch();

  useSelector((state: StoreDto) => state.searchOptions);

  const { page }: { page?: string } = useParams();

  useEffect(() => {
    dispatch(setLoading());
    const timeOut = setTimeout(() => {
      dispatch(getPage(Number(page)));
    }, 300);

    return () => {
      clearTimeout(timeOut);
    };
  }, [page]);

  return (
    <div>
      <EmailListTitle />
      <EmailListHeaderContainer />
      <EmailList />
    </div>
  );
};
