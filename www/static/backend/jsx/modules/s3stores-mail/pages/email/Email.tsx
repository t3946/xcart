import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useHistory, useParams } from "react-router-dom";
import { getPage, setLoading } from "../../../../redux/actions/emailActions";
import { EmailListHeaderContainer } from "../../containers";
import { EmailListTitle } from "@s3stores-mail/components/ordinary/email-list-title/EmailListTitle";
import { EmailListContainer } from "@s3stores-mail/containers/email-list/EmailList.container";
import { StoreDto } from "@s3stores-mail/ts/types";
export const Email: React.FC = () => {
  const dispatch = useDispatch();

  const { page }: { page?: string } = useParams();

  const searchParams = useSelector((e: StoreDto) => e.searchOptions);

  useEffect(() => {
    // if (thisPage === Number(page)) {
    //   return;
    // }
    dispatch(setLoading());
    const timeOut = setTimeout(() => {
      dispatch(getPage(Number(page), searchParams));
    }, 300);

    return () => {
      clearTimeout(timeOut);
    };
  }, [page, searchParams]);

  return (
    <div>
      <EmailListTitle />
      <EmailListHeaderContainer />
      <EmailListContainer />
    </div>
  );
};
