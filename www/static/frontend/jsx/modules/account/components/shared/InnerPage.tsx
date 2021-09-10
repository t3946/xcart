import React, { ReactNode } from "react";
import classnames from "classnames";

interface PropsInterface {
  header?: ReactNode;
  hat?: ReactNode;
  body?: ReactNode;
  footer?: ReactNode;
  headerClasses?: any;
  hatClasses?: any;
  bodyClasses?: any;
  footerClasses?: any;
  children?: any;
}

const InnerPage: React.FC<PropsInterface> = function (props: PropsInterface) {
  function headerTemplate() {
    if (props.header) {
      return (
        <h1
          className={classnames(
            "account-page-header mb-0",
            props.headerClasses
          )}
        >
          {props.header}
        </h1>
      );
    }
  }

  return (
    <div className="account-inner-page">
      <div className={classnames("account-page-hat", props.hatClasses)}>
        {headerTemplate()}
        {props.hat}
      </div>

      <div className={classnames("account-page-body", props.bodyClasses)}>
        {props.body}
        {props.children}
      </div>

      {props.footer && (
        <div className={classnames("account-page-footer", props.footerClasses)}>
          {props.footer}
        </div>
      )}
    </div>
  );
};

export default InnerPage;
