import React, { useContext, useState } from "react";
import { ListItemMovableArea } from "@modules/account/components/lists/ListItemMovableArea";
import RatingStars from "@modules/shared/components/ratings/RatingStars";
import { Tooltip } from "@modules/account/components/shared/Tooltip";
import { ListProductItemBtns } from "./ListProductItemBtns";
import { ListProductItemComment } from "@modules/account/components/lists/ListProductItemComment";
import { EditComment } from "@modules/account/components/lists/EditComment";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { useDialog } from "@modules/account/hooks/useDialog";
import { MobileMenuForList } from "@modules/account/components/lists/MobileMenuForList";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
// import { useHistory } from "react-router-dom";
import { ListProductInfo } from "@modules/account/ts/types/list.type";
import { ListProductItemProps } from "@modules/account/ts/types/list-product-item-props.type";
import { cartAdd } from "@redux/reducers/appCartReducer";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { CountInput } from "@modules/account/components/shared/CountInput";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

export const ListProductItem: React.FC<ListProductItemProps> = ({
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

  let product: ListProductInfo;

  if ("product" in info.product) {
    product = info.product;
  }

  const breakpoint = useBreakpoint();

  const deleteProductDialog = useDialog();

  const mobileMenuDialog = useDialog();

  const [countProductsOnCart, setCountProductsOnCart] = useState(
    product.min_amount
  );

  const changeCount = (value: number, isInputEnter?: boolean) => {
    if (isInputEnter) {
      if (value <= product.min_amount) {
        return;
      }
      if (value > product.avail) {
        setCountProductsOnCart(product.avail);
        return;
      }
      setCountProductsOnCart(value);
      return;
    }
    if (value <= product.min_amount) {
      return;
    }
    if (value > product.avail) {
      return;
    }

    setCountProductsOnCart(value);
  };

  // const history = useHistory();

  const data = [
    {
      id: info.product_id,
      quantity: countProductsOnCart,
      options: [],
    },
  ];

  const { showSnackbar } = useContext(SnackbarContext);

  const deleteProduct = () => {
    breakpoint({
      xs: () =>
        history.push(
          `/account/your-lists/delete-product/product/${listInfo.product_list_id}/${info.product_id}/`
        ),
      md: deleteProductDialog.handleClickOpen,
    });
  };

  const onCountInputBlur = () => {
    if (countProductsOnCart > product.avail) {
      setCountProductsOnCart(product.avail);
    }
    if (countProductsOnCart > 0) {
      return;
    }
  };

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: info.image,
      label: product.product,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () => {
        // history.push(
        //   `/account/your-lists/edit-list-product-info/${listInfo.cache_url}/${info.product_id}`
        // );
      },
    },
    {
      label: "Move",
      onClick: () => {
        // history.push(
        //   `/account/your-lists/move-product/${info.product_id}/${listInfo.product_list_id}`
        // );
      },
    },
    {
      label: "Delete",
      onClick: deleteProduct,
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
            <a
              href={`/product/${info.product_id}/`}
              className="product-list-item-name"
            >
              {product.product}
            </a>
            {edit && (
              <img
                onClick={mobileMenuDialog.handleClickOpen}
                className="edit-idea-ellipsis"
                src={"/static/frontend/dist/images/icons/account/ellipsis.svg"}
              />
            )}
          </div>

          <Tooltip
            target={
              <div className="tooltip-rating-stars-target">
                <RatingStars rating={3} />
              </div>
            }
            content={
              <div className="rating-stars-tooltip">
                {/*<OverallRating ratings={[]} />*/}
              </div>
            }
          />
          <div className="d-flex align-items-center">
            <div className="product-list-item-price">${product?.price}</div>
            <div className="multiplication-symbol">X</div>
            <CountInput
              avail={product.avail}
              onBlur={onCountInputBlur}
              value={countProductsOnCart}
              onChange={changeCount}
              minAmount={product.min_amount}
              multOrderQuantity={product.mult_order_quantity === "Y"}
            />
          </div>
          {edit &&
            (info.comment ? (
              <ListProductItemComment
                info={info}
                listInfo={listInfo}
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
        btnLabel={"Add to cart"}
        edit={edit}
        id={info.product_id}
        outOfStock={info.product.out}
        deleteItem={deleteProduct}
        onMoveClick={onMoveClick}
        onMainBtnClick={() =>
          cartAdd(
            data,
            showSnackbar({
              header: "Success",
              message: `${info.product.product} added to cart`,
              theme: "success",
            })
          )
        }
        time={info.add_date}
        listId={info.product_list_id}
      />

      <BootstrapDialogHOC
        show={editCommentDialog.open}
        title={
          info.comment
            ? "Edit comment, quantity & priority"
            : "Add comment, quantity & priority"
        }
        onClose={editCommentDialog.handleClose}
      >
        <EditComment
          onCloseClick={editCommentDialog.handleClose}
          listId={listId}
          productId={info.product_id}
          info={info}
        />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteProductDialog.open}
        title={"Confirm delete"}
        onClose={deleteProductDialog.handleClose}
      >
        <ConfirmDelete
          onCancelClick={deleteProductDialog.handleClose}
          onDeleteClick={deleteItem}
          deleteType={"product"}
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
