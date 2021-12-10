import ArrowBackIcon from "@client/modules/icon/components/account/arrows/ArrowBackIcon";
import React from "react";
import { useHistory } from "react-router-dom";

interface MobileMenuBackBtnProps {
  redirectUrl: string;
  label: string;
}

export const MobileMenuBackBtn: React.FC<MobileMenuBackBtnProps> = ({
  redirectUrl,
  label,
}) => {
  const history = useHistory();

  const onBtnClick = () => {
    history.push(redirectUrl);
  };
  return (
    <div className={"mobile-menu-back-btn-container"}>
      <button onClick={onBtnClick} className="form-button__outline ">
        <div className="back-account-btn-inner mobile-menu-back-btn">
          <ArrowBackIcon className="mobile-menu-back-btn-icon" />
          <div>{label}</div>
        </div>
      </button>
    </div>
  );
};
