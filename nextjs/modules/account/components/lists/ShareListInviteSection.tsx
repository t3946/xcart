import React, { useState } from "react";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";
import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { CopyToClipboard } from "react-copy-to-clipboard";

interface IProps {
  showSharedStatus: ShowSharedStatusEnum;
  setShowSharedStatus: (status: ShowSharedStatusEnum) => void;
  onCopyLinkFunc: (res: boolean) => void;
  sharedLink: string;
}

export const ShareListInviteSection: React.FC<IProps> = ({
  onCopyLinkFunc,
  sharedLink,
  setShowSharedStatus,
  showSharedStatus,
}) => {
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
          {sharedLink ? (
            <>
              <img
                className="share-variants-logo"
                src="/static/frontend/images/icons/account/paper_clip.svg"
              />
              <CopyToClipboard
                text={sharedLink}
                onCopy={(text, result) => onCopyLinkFunc(result)}
              >
                <div className="share-variants-label share-variants-label-copy">
                  Copy link
                </div>
              </CopyToClipboard>
            </>
          ) : (
            <>
              <img
                className="share-variants-logo"
                src="/static/frontend/images/icons/account/paper_clip.svg"
              />
              <div className="share-variants-label share-variants-label-copy text-black-50">
                Copy link
              </div>
            </>
          )}
        </div>
        <div className="share-variants-logo-container d-none">
          <img
            className="share-variants-logo"
            src="/static/frontend/images/icons/account/email.svg"
          />
          <a href="mailto:" className="share-variants-label">
            Invite by email
          </a>
        </div>
      </div>
    </React.Fragment>
  );
};
