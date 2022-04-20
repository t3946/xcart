import * as React from "react";
import { MobileMenuForList } from "@modules/account/components/lists/mobile-menu/MobileMenuForList";
import { Hat } from "@modules/account/components/lists/mobile-menu/Hat";
import getStoreUrl from "@utils/getStoreUrl";
import Styles from "@modules/account/components/lists/item-product/MobileMenu.module.scss";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";

interface IProps {
  dialog: Record<any, any>;
  user: any;
  onClick: any;
  role: string;
  userDelete: any;
}

export const MobileMenu: React.FC<IProps> = function (props) {
  const { role, user, dialog, onClick, userDelete } = props;

  function leftColumnTemplate() {
    return <div></div>;
  }

  function rightColumnTemplate() {
    return <div className={Styles.hatProductName}></div>;
  }

  function hatTemplate() {
    return (
      <Hat
        columnLeft={leftColumnTemplate()}
        columnRight={rightColumnTemplate()}
      />
    );
  }

  const avatar = user.avatar_image
    ? getStoreUrl(user.avatar_image)
    : "/static/frontend/images/pages/account/default-avatar.svg";

  const items = [
    {
      component: (
        <div className="d-flex align-items-center share-list-people-left-side-container">
          <img
            src={avatar}
            className="page-invitation-user-profile-avatar 1"
            alt={"avatar image"}
          />
          <div>
            <div>{user.name}</div>
            <div className="share-list-people-email">{user.email}</div>
          </div>
        </div>
      ),
    },
    {
      label: (
        <div
          className={`share-list-mobile-menu-item ${
            role === UserPrivateVariantsEnum.EDIT &&
            "share-list-mobile-menu-item-selected"
          }`}
        >
          Edit
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.EDIT, user.user_id);
        dialog.handleClose();
      },
    },
    {
      label: (
        <div
          className={`share-list-mobile-menu-item ${
            role === UserPrivateVariantsEnum.VIEW &&
            "share-list-mobile-menu-item-selected"
          }`}
        >
          View
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.VIEW, user.user_id);
        dialog.handleClose();
      },
    },
    {
      label: (
        <div className="share-list-mobile-menu-item share-list-mobile-menu-item-remove">
          Remove
        </div>
      ),
      onClick: () => {
        userDelete(user.user_id);
        dialog.handleClose();
      },
    },
  ];

  return (
    <MobileMenuForList
      hat={hatTemplate}
      items={items}
      dialogOpen={dialog.open}
      dialogOnClose={dialog.handleClose}
    />
  );
};

export default MobileMenu;
