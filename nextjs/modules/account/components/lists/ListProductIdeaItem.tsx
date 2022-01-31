import React from "react";
import { ListItemMovableArea } from "@modules/account/components/lists/ListItemMovableArea";
import { ListProductItemBtns } from "@modules/account/components/lists/ListProductItemBtns";
import { EditIdea } from "@modules/account/components/lists/EditIdea";
import { ListProductItemComment } from "@modules/account/components/lists/ListProductItemComment";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { EditComment } from "@modules/account/components/lists/EditComment";
import { useDialog } from "../../hooks/useDialog";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/MobileMenuForList";
import { useRouter } from "next/router";
import { ListProductItemProps } from "@modules/account/ts/types/list-product-item-props.type";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { List } from "@modules/account/ts/types/list.type";
import cn from "classnames";

import StylesListProductItems from "@modules/account/components/lists/ListProductItems.module.scss";
import Styles from "@modules/account/components/lists/ListProductIdeaItem.module.scss";

export const ListProductIdeaItem: React.FC<ListProductItemProps> = ({
  productItem,
  drag,
  reorderProductList,
  index,
  deleteItem,
  edit,
}) => {
  const editCommentDialog = useDialog();
  const mobileMenuDialog = useDialog();
  const listInfo: List = useSelectorAccount((state) => state.lists.listView);
  const router = useRouter();
  const breakpoint = useBreakpoint();
  const deleteIdeaDialog = useDialog();
  const listView = useSelectorAccount((state) => state.lists.listView);
  const deleteIdea = () => {
    breakpoint({
      xs: () =>
        router.push(
          `/shopping-lists/actions/delete-product/idea/${listInfo.productListId}/${productItem.productId}/`
        ),
      md: deleteIdeaDialog.handleClickOpen,
    });
  };

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: "/static/frontend/images/icons/account/idea-logo.svg",
      label: productItem.product.name,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () => {
        router.push(
          `/shopping-lists/actions/add-comment/idea/${listView.productListId}/${productItem.productId}`
        );
      },
    },
    {
      label: "Move",
      onClick: () => {
        router.push(
          `/shopping-lists/actions/move-product/idea/${listView.productListId}/${productItem.productId}`
        );
      },
    },
    {
      label: "Delete",
      onClick: deleteIdea,
    },
  ];

  return (
    <div
      className={cn(
        StylesListProductItems.productListItemContainer,
        "product-list-item-container"
      )}
    >
      <div className="movable-area">
        {edit ? (
          <ListItemMovableArea
            onUpClick={() => reorderProductList(index, index - 1)}
            onDownClick={() => reorderProductList(index, index + 1)}
            drag={drag}
          />
        ) : (
          <div className="product-list-item-movable-area-placeholder" />
        )}
      </div>

      <div className="product-list-item-info-content">
        <img
          className="product-image product-list-item-image"
          src={"/static/frontend/images/icons/account/idea-logo.svg"}
        />
        <div className="product-list-item-info">
          <EditIdea
            openMenuDialog={mobileMenuDialog.handleClickOpen}
            listId={listInfo}
            info={productItem}
            edit={edit}
          />

          {edit &&
            (productItem.comment ? (
              <ListProductItemComment
                info={productItem}
                onEditCommentClick={editCommentDialog.handleClickOpen}
                listInfo={listInfo}
              />
            ) : (
              <div
                onClick={editCommentDialog.handleClickOpen}
                className={cn(Styles.editComment, "add-comment-text")}
              >
                Add comment, quantity & priority
              </div>
            ))}
        </div>
      </div>
      <ListProductItemBtns
        btnLabel={"search"}
        mainBtnClasses={"account-submit-btn-outline"}
        edit={edit}
        time={productItem.add_date}
        listId={listView.productListId}
        onMainBtnClick={() =>
          window.location.assign(`/search?q=${productItem.product.name}`)
        }
        handleDelete={deleteIdea}
        productId={productItem.productId}
      />
      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
      <BootstrapDialogHOC
        show={editCommentDialog.open}
        title={
          productItem.comment
            ? "Edit comment, quantity & priority"
            : "Add comment, quantity & priority"
        }
        onClose={editCommentDialog.handleClose}
      >
        <EditComment
          onCloseClick={editCommentDialog.handleClose}
          listId={listView.productListId}
          productId={productItem.productId}
          info={productItem}
        />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteIdeaDialog.open}
        title={"Confirm delete"}
        onClose={deleteIdeaDialog.handleClose}
      >
        <ConfirmDelete
          onCancelClick={deleteIdeaDialog.handleClose}
          onDeleteClick={deleteItem}
          deleteType={"idea"}
        />
      </BootstrapDialogHOC>
    </div>
  );
};
