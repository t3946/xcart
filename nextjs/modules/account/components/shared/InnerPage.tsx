import React, { ReactNode } from "react";
import classnames from "classnames";
import Styles from "@modules/account/components/shared/InnerPage.module.scss";

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
          className={classnames("mb-0", Styles.pageHeader, props.headerClasses)}
        >
          {props.header}
        </h1>
      );
    }
  }

  return (
    <div className="account-inner-page">
      {props.beforePage}

      <div className={classnames(Styles.accountPageHat, props.hatClasses)}>
        {headerTemplate()}
        {props.hat}
      </div>

      <div className={classnames(Styles.accountPageBody, props.bodyClasses)}>
        {props.body}
        {props.children}
      </div>

      {props.footer && (
        <div
          className={classnames(Styles.accountPageFooter, props.footerClasses)}
        >
          {props.footer}
        </div>
      )}
    </div>
  );
};

export default InnerPage;
