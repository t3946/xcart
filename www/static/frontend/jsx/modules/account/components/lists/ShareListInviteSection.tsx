import React, { useState } from "react";
import { RadioBtn } from "@client/modules/account/components/shared/RadioBtn";
import { ShowSharedStatusEnum } from "@client/modules/account/ts/types/show-shared-status.enum";

export const ShareListInviteSection = ({ onCopyLinkFunc }) => {
  const [showSharedStatus, setShowSharedStatus] = useState(
    ShowSharedStatusEnum.VIEW
  );
  return (
    <React.Fragment>
      <div className="share-list-label">Invite someone to</div>
      <RadioBtn
        name="radio"
        id={"radio-item-view"}
        viewValue={"View"}
        groupValue={showSharedStatus}
        radioValue={ShowSharedStatusEnum.VIEW}
        onChange={setShowSharedStatus}
        groupClasses={{
          group: "billing-address-item-container",
          checked: "form-radio-checked",
        }}
      />
      <RadioBtn
        name="radio"
        id={"radio-item-edit"}
        viewValue={"Edit"}
        groupValue={showSharedStatus}
        radioValue={ShowSharedStatusEnum.VIEW}
        onChange={setShowSharedStatus}
        groupClasses={{
          group: "billing-address-item-container",
          checked: "form-radio-checked",
        }}
      />
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
  );
};
