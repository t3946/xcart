import * as React from "react";
import { MobileMenuForList } from "@modules/account/components/lists/mobile-menu/MobileMenuForList";
import { Hat } from "@modules/account/components/lists/mobile-menu/Hat";
import getStoreUrl from "@utils/getStoreUrl";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import Styles from "@modules/account/components/lists/share-list/MobileMenu.module.scss";
import StylesHat from "@modules/account/components/lists/mobile-menu/MenuHat.module.scss";
import IconCheck from "@modules/icon/components/shopping-lists/Check";
import IconCross from "@modules/icon/components/shopping-lists/Cross";

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
    return (
      <div>
        <img src={avatar} className={Styles.avatar} alt={"avatar image"} />
      </div>
    );
  }

  function rightColumnTemplate() {
    return (
      <div className={Styles.hatProductName}>
        <div>{user.name}</div>
        <div className="share-list-people-email">{user.email}</div>
      </div>
    );
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
      label: (
        <div className={"d-flex"}>
          <div className={StylesHat.columnLeft}>
            {role === UserPrivateVariantsEnum.EDIT && (
              <IconCheck className={[Styles.iconRole]} />
            )}
          </div>

          <div className={StylesHat.columnRight}>Edit</div>
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.EDIT, user.user_id);
        dialog.handleClose();
      },
    },
    {
      label: (
        <div className={"d-flex"}>
          <div className={StylesHat.columnLeft}>
            {role === UserPrivateVariantsEnum.VIEW && (
              <IconCheck className={[Styles.iconRole]} />
            )}
          </div>

          <div className={StylesHat.columnRight}>View</div>
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.VIEW, user.user_id);
        dialog.handleClose();
      },
    },
    {
      label: (
        <div className={"d-flex"}>
          <div className={StylesHat.columnLeft}>
            <IconCross className={[Styles.iconDeleteRole]} />
          </div>

          <div className={StylesHat.columnRight}>Remove</div>
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
