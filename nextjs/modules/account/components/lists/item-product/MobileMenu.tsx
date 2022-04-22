import * as React from "react";
import { MobileMenuForList } from "@modules/account/components/lists/mobile-menu/MobileMenuForList";
import { Hat } from "@modules/account/components/lists/mobile-menu/Hat";
import { useRouter } from "next/router";
import getStoreUrl from "@utils/getStoreUrl";
import Styles from "@modules/account/components/lists/item-product/MobileMenu.module.scss";
import ImageNotAvailable from "@components/common/image-not-available/ImageNotAvailable";

interface IProps {
  dialog: Record<any, any>;
  list: any;
  item: any;
}

export const MobileMenu: React.FC<IProps> = function (props) {
  const { list, item, dialog } = props;
  const router = useRouter();

  function imageTemplate() {
    if (item.product.images.length === 0) {
      return <ImageNotAvailable />;
    }

    return <img src={getStoreUrl(item.product.images[0].path)} alt="" />;
  }

  function leftColumnTemplate() {
    return <div>{imageTemplate()}</div>;
  }

  function rightColumnTemplate() {
    return <div className={Styles.hatProductName}>{item.product.product}</div>;
  }

  function hatTemplate() {
    return (
      <Hat
        columnLeft={leftColumnTemplate()}
        columnRight={rightColumnTemplate()}
      />
    );
  }

  const items = [
    {
      label: "Add comment, quantity & priority",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/add-comment/product/${list.product_list_id}/${item.list_item_id}`
        ),
    },
    {
      label: "Move",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/move-product/product/${list.product_list_id}/${item.list_item_id}`
        ),
    },
    {
      label: "Delete",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/delete-product/product/${list.product_list_id}/${item.list_item_id}`
        ),
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
