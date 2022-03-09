import React, { ReactNode } from "react";
import classnames from "classnames";

interface IProps {
  beforePage?: React.ReactElement;

  header?: ReactNode;
  headerClasses?: any;

  hat?: ReactNode;
  hatClasses?: any;

  body?: ReactNode;
  bodyClasses?: any;

  footer?: ReactNode;
  footerClasses?: any;

  children?: any;
}

const InnerPage: React.FC<IProps> = function (props: IProps) {
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
      {props.beforePage}

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
