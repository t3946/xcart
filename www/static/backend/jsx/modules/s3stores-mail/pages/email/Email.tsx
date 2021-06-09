import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useParams } from "react-router-dom";
import { getPage, setEmailInfo, setLoading } from "../../../../redux/actions";
import { EmailListHeaderContainer } from "../../containers";
import { EmailListTitle } from "@s3stores-mail/components/ordinary/email-list-title/EmailListTitle";
import { EmailListContainer } from "@s3stores-mail/containers/email-list/EmailList.container";
import { StoreDto } from "../../ts/types";
import { initialValues } from "../../ts/consts";
export const Email: React.FC = () => {
  const dispatch = useDispatch();

  const { page }: { page?: string } = useParams();

  const searchParams = useSelector((e: StoreDto) => e.searchOptions);

  const emailPage = useSelector((e: StoreDto) => e.page);

  useEffect(() => {
    dispatch(setEmailInfo(initialValues.emailInfo));
    if (Number(page) === emailPage) {
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
