import React from "react";
import { ListItemMovableArea } from "@client/modules/account/components/lists/ListItemMovableArea";
import { ProductStarsRating } from "@client/modules/account/components/shared/ProductStarsRating";
import { Tooltip } from "@client/modules/account/components/shared/Tooltip";
import { TooltipRatingContent } from "./TooltipRatingContent";
import { ListProductItemBtns } from "./ListProductItemBtns";
import { ListProductItemComment } from "@client/modules/account/components/lists/ListProductItemComment";
import { EditComment } from "@client/modules/account/components/lists/EditComment";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { MobileMenuForList } from "@client/modules/account/components/lists/MobileMenuForList";
import { MobileMenuForListItem } from "@client/modules/account/ts/types/MobileMenuForListItem";
import { useHistory } from "react-router-dom";

export const ListProductItem = ({
  info,
  drag,
  reorderProductList,
  index,
  listId,
  deleteItem,
  edit,
  onMoveClick,
  listInfo,
}) => {
  const editCommentDialog = useDialog();

  const mobileMenuDialog = useDialog();

  const history = useHistory();

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: "/static/frontend/images/icons/account/idea-logo.svg",
      label: info.product.name,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () => {
        history.push(
          `/account/your-lists/edit-list-product-info/${listInfo.cache_url}/${info.product_id}`
        );
      },
    },
    {
      label: "Move",
      onClick: () => {
        history.push(
          `/account/your-lists/move-product/${info.product_id}/${listInfo.product_list_id}`
        );
      },
    },
    {
      label: "Delete",
      onClick: deleteItem,
    },
  ];

  return (
    <div className="product-list-item-container">
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
          className="product-list-item-image product-image"
          src={info.image}
        />
        <div className="product-list-item-info">
          <div className="product-list-item-info-container">
            <div className="product-list-item-name">{info.product.product}</div>
            <img
              onClick={mobileMenuDialog.handleClickOpen}
              className="edit-idea-ellipsis"
              src={"/static/frontend/dist/images/icons/account/ellipsis.svg"}
            />
          </div>

          <Tooltip
            target={
              <div className="tooltip-rating-stars-target">
                <ProductStarsRating rating={3} />
              </div>
            }
            content={
              <div className="rating-stars-tooltip">
                <TooltipRatingContent />
              </div>
            }
          />
          <div className="product-list-item-price">
            ${info.product.cost_to_us}
          </div>
          {edit &&
            (info.comment ? (
              <ListProductItemComment
                info={info}
                onEditCommentClick={editCommentDialog.handleClickOpen}
              />
            ) : (
              <div
                onClick={editCommentDialog.handleClickOpen}
                className="add-comment-text"
              >
                Add comment, quantity & priority
              </div>
            ))}
        </div>
      </div>
      <ListProductItemBtns
        btnLabel={"Add in cart"}
        edit={edit}
        id={info.product_id}
        deleteItem={deleteItem}
        onMoveClick={onMoveClick}
      />

      <BootstrapDialogHOC
        show={editCommentDialog.open}
        title={"Edit comment, quantity & priority"}
        onClose={editCommentDialog.handleClose}
      >
        <EditComment
          onCloseClick={editCommentDialog.handleClose}
          listId={listId}
          productId={info.product_id}
          info={info}
        />
      </BootstrapDialogHOC>
      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
    </div>
  );
};
