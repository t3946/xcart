import React from "react";
import { Button } from "@material-ui/core";

interface ShareListBlockProps {
  labelButton: string;
  onButtonClick: () => void;
  subtitle: string;
  showBottomBlock: boolean;
  onCopyLinkFunc: () => void;
}

export const ShareListBlock: React.FC<ShareListBlockProps> = ({
  labelButton,
  onButtonClick,
  subtitle,
  showBottomBlock,
  onCopyLinkFunc,
}) => {
  return (
    <div>
      <Button
        onClick={onButtonClick}
        className="account-submit-btn account-submit-btn-outline auto-width-button share-list-btn"
      >
        {labelButton}
      </Button>
      <div>
        <p>{subtitle}</p>
      </div>
      {showBottomBlock && (
        <React.Fragment>
          <div className="change-list-privacy-label">
            Your List privacy will be changed to "Shared"
          </div>
          <div className="share-variants-container">
            <div
              onClick={onCopyLinkFunc}
              className="share-variants-label share-variants-label-copy"
            >
              Copy link
            </div>
            <div className="share-variants-label">Invite by email</div>
          </div>
        </React.Fragment>
      )}
    </div>
  );
};
