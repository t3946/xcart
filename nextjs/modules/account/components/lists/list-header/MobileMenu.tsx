import * as React from "react";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/mobile-menu/MobileMenuForList";
import { useRouter } from "next/router";

interface IProps {
  list: any;
  dialog: any;
}

export const MobileMenu: React.FC<IProps> = function (props) {
  const { list, dialog } = props;
  const router = useRouter();
  const items: MobileMenuForListItem[] = [
    {
      label: "Manage list",
      onClick: () =>
        router.push(
          `/shopping-lists/action-list/manage-list/${list.product_list_id}`
        ),
    },
    {
      label: "Add idea",
      onClick: () =>
        router.push(
          `/shopping-lists/action-list/add-idea/${list.product_list_id}`
        ),
    },
    {
      label: "Share list with others",
      onClick: () =>
        router.push(
          `/shopping-lists/action-list/share-list/${list.product_list_id}`
        ),
    },
  ];

  if (list.default === 0) {
    items.push({
      label: "Delete list",
      onClick: () =>
        router.push(
          `/shopping-lists/action-list/delete-list/${list.product_list_id}`
        ),
    });
  }

  return (
    <MobileMenuForList
      items={items}
      dialogOpen={dialog.open}
      dialogOnClose={dialog.handleClose}
    />
  );
};

export default MobileMenu;
