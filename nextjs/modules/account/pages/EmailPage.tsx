import React, { useEffect, useRef, useState } from "react";
import { EmailHeader } from "@modules/account/components/orders/EmailHeader";
import { EmailBody } from "@modules/account/components/orders/EmailBody";
import { useParams } from "react-router-dom";

interface EmailPageProps {
  orderItem?: any;
}

interface EmailPageUrlParams {
  id: string;
  orderType: string;
  emailId: string;
}

export const EmailPage: React.FC<EmailPageProps> = ({ orderItem }) => {
  const [emailInfo, setEmailInfo] = useState(null);

  const urlParams = useParams<EmailPageUrlParams>();

  const ref = useRef();

  useEffect(() => {
    setEmailInfo(
      orderItem.orderInfo.emails.find((e) => e.id === Number(urlParams.emailId))
    );
  }, [orderItem]);

  return (
    <div>
      {emailInfo && (
        <React.Fragment>
          <EmailHeader contentRef={ref} info={emailInfo} />
          <EmailBody contentRef={ref} emailInfo={emailInfo} />
        </React.Fragment>
      )}
    </div>
  );
};
