import React, { useState } from "react";
import { RadioBtn } from "@client/modules/account/components/shared/RadioBtn";
import { ShowSharedStatusEnum } from "@client/modules/account/ts/types/show-shared-status.enum";

export const ShareListInviteSection = ({ onCopyLinkFunc }) => {
  const [showSharedStatus, setShowSharedStatus] = useState(
    ShowSharedStatusEnum.VIEW
  );

  const setNewStatus = (value: ShowSharedStatusEnum) => {
    setShowSharedStatus(value);
  };
  return (
    <React.Fragment>
      <div className="share-list-label">Invite</div>
      <RadioBtn
        name="radio"
        id={"radio-item-view"}
        viewValue={
          <div>
            <div className="share-list-radio-title">Viewer</div>
            <div className="share-list-radio-subtitle">
              Anyone with a link can view your list without making edits
            </div>
          </div>
        }
        groupClasses={{
          group: "share-list-radio",
        }}
        groupValue={showSharedStatus}
        radioValue={ShowSharedStatusEnum.VIEW}
        onChange={setNewStatus}
      />
      <RadioBtn
        name="radio"
        id={"radio-item-edit"}
        viewValue={
          <div>
            <div className="share-list-radio-title">Editor</div>
            <div className="share-list-radio-subtitle">
              Invited people can add or remove items from your list
            </div>
          </div>
        }
        groupClasses={{
          group: "share-list-radio",
        }}
        groupValue={showSharedStatus}
        radioValue={ShowSharedStatusEnum.VIEW_EDIT}
        onChange={setNewStatus}
      />
      <div className="share-variants-container">
        <div className="share-variants-logo-container">
          <img
            className="share-variants-logo"
            src="/static/frontend/images/icons/account/paper_clip.svg"
          />
          <div
            onClick={() => onCopyLinkFunc(showSharedStatus)}
            className="share-variants-label share-variants-label-copy"
          >
            Copy link
          </div>
        </div>
        <div className="share-variants-logo-container">
          <img
            className="share-variants-logo"
            src="/static/frontend/images/icons/account/email.svg"
          />
          <div className="share-variants-label">Invite by email</div>
        </div>
      </div>
    </React.Fragment>
  );
};
