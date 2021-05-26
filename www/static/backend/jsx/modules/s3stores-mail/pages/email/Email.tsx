import React, { useEffect } from "react";
import { useDispatch } from "react-redux";
import { useParams } from "react-router-dom";
import { getPage, setLoading } from "../../../../redux/actions/emailActions";
import { EmailListHeaderContainer } from "../../containers";
import { EmailListTitle } from "@s3stores-mail/components/ordinary/email-list-title/EmailListTitle";
import { EmailListContainer } from "@s3stores-mail/containers/email-list/EmailList.container";
export const Email: React.FC = () => {
  const dispatch = useDispatch();

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
      <EmailListContainer />
    </div>
  );
};
