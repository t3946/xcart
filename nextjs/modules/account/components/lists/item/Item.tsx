import React from "react";
import { ListItemMovableArea } from "@modules/account/components/lists/ListItemMovableArea";
import { ListProductItemBtns } from "@modules/account/components/lists/ListProductItemBtns";
import { ETheme } from "@modules/ui/forms/Button";
import { EditIdea } from "@modules/account/components/lists/EditIdea";
import { Comment } from "@modules/account/components/lists/item/Comment";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { EditComment } from "@modules/account/components/lists/EditComment";
import { useDialog } from "@modules/account/hooks/useDialog";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/mobile-menu/MobileMenuForList";
import { useRouter } from "next/router";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import cn from "classnames";
import StylesListProductItems from "@modules/account/components/lists/ListProductItems.module.scss";
import StylesItem from "@modules/account/components/lists/item/Item.module.scss";
import Image from "@modules/account/components/lists/item/Image";
import AddDate from "@modules/account/components/lists/item/AddDate";

export const Item: React.FC<any> = (props) => {
  const { listItem, drag, reorderProductList, index, deleteItem, edit, list } =
    props;
  const editCommentDialog = useDialog();
  const mobileMenuDialog = useDialog();
  const router = useRouter();
  const breakpoint = useBreakpoint();
  const deleteIdeaDialog = useDialog();

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: "/static/frontend/images/icons/account/idea-logo.svg",
      label: listItem.idea?.name,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () => {
        router.push(
          `/shopping-lists/actions/add-comment/idea/${list.product_list_id}/${listItem.list_item_id}`
        );
      },
    },
    {
      label: "Move",
      onClick: () => {
        router.push(
          `/shopping-lists/actions/move-product/idea/${list.product_list_id}/${listItem.list_item_id}`
        );
      },
    },
    {
      label: "Delete",
      onClick: deleteIdea,
    },
  ];

  function deleteIdea() {
    breakpoint({
      xs: () =>
        router.push(
          `/shopping-lists/actions/delete-product/idea/${list.product_list_id}/${listItem.list_item_id}`
        ),
      md: deleteIdeaDialog.handleClickOpen,
    });
  }

  function movableAreaTemplate() {
    if (!edit) {
      return <div className="product-list-item-movable-area-placeholder" />;
    }

    return (
      <div className="movable-area">
        <ListItemMovableArea
          isFirst={index === 0}
          isLast={index === list.items.length - 1}
          onUpClick={() => reorderProductList(index, index - 1)}
          onDownClick={() => reorderProductList(index, index + 1)}
          drag={drag}
          classes={{ container: { "d-none": list.items.length === 1 } }}
        />
      </div>
    );
  }

  function getSearchLink() {
    const name = listItem.product?.product || listItem.idea.name;

    return `/search?q=${name}`;
  }

  return (
    <div
      className={cn(
        StylesListProductItems.productListItemContainer,
        "product-list-item-container"
      )}
    >
      {movableAreaTemplate()}

      <div className={cn("d-flex", "flex-grow-1")}>
        <Image imgUrl={"/static/frontend/images/icons/account/idea-logo.svg"} />

        <div className="product-list-item-info">
          {listItem.product_type === "idea" && (
            <EditIdea
              openMenuDialog={mobileMenuDialog.handleClickOpen}
              listId={list}
              listItem={listItem}
              edit={edit}
            />
          )}

          {edit &&
            (listItem.comment ? (
              <Comment
                listItem={listItem}
                onEditCommentClick={editCommentDialog.handleClickOpen}
                list={list}
              />
            ) : (
              <div
                onClick={editCommentDialog.handleClickOpen}
                className={cn(StylesItem.editComment, "add-comment-text")}
              >
                Add comment, quantity & priority
              </div>
            ))}

          <AddDate
            date={listItem.add_date}
            className={["mt-18", "d-md-none"]}
          />
        </div>
      </div>

      <div>
        <AddDate
          date={listItem.add_date}
          className={[
            "text-center",
            "text-md-end",
            "mb-lg-10",
            "mb-12",
            "d-none",
            "d-md-block",
          ]}
        />

        <ListProductItemBtns
          className={["mt-12", "mt-md-0"]}
          btnLabel={"search"}
          mainBtnType={ETheme.outlined}
          edit={edit}
          list={list}
          searchLink={getSearchLink()}
          handleDelete={deleteIdea}
          item={listItem}
        />
      </div>

      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
      <BootstrapDialogHOC
        show={editCommentDialog.open}
        title={
          listItem.comment
            ? "Edit comment, quantity & priority"
            : "Add comment, quantity & priority"
        }
        onClose={editCommentDialog.handleClose}
      >
        <EditComment
          onCloseClick={editCommentDialog.handleClose}
          listId={list.product_list_id}
          list_item_id={listItem.list_item_id}
          info={listItem}
        />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteIdeaDialog.open}
        title={"Confirm delete"}
        onClose={deleteIdeaDialog.handleClose}
      >
        <ConfirmDelete
          onCancelClick={deleteIdeaDialog.handleClose}
          onDeleteClick={() => {
            deleteItem();
            deleteIdeaDialog.handleClose();
          }}
          deleteType={"idea"}
        />
      </BootstrapDialogHOC>
    </div>
  );
};

export default Item;
