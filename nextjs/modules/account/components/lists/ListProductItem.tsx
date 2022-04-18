import React, { useState } from "react";
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
import { ListProductInfo } from "@modules/account/ts/types/list.type";
import { ListProductItemProps } from "@modules/account/ts/types/list-product-item-props.type";
import { cartAdd } from "@redux/reducers/appCartReducer";
import useSnackbar from "@modules/account/hooks/useSnackbar";
import { CountGroup } from "@modules/ui/CountGroup";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import cn from "classnames";
import Chevron from "@modules/icon/components/font-awesome/chevron-down/Light";
import moment from "moment";
import OverallRating from "@modules/shared/components/ratings/OverallRating";
import StylesListProductItems from "@modules/account/components/lists/ListProductItems.module.scss";
import Styles from "@modules/account/components/lists/ListProductItem.module.scss";
import {
  getAction as cartGetAction,
  setAction as cartSetAction,
} from "@redux/actions/CartActions";

export const ListProductItem: React.FC<ListProductItemProps> = (props) => {
  const {
    listItem,
    drag,
    reorderProductList,
    index,
    listId,
    deleteItem,
    edit,
    listInfo,
    list,
  } = props;
  const editCommentDialog = useDialog();
  const dispatch = useDispatch();
  const product: ListProductInfo = listItem.product;
  const router = useRouter();
  const deleteProductDialog = useDialog();
  const mobileMenuDialog = useDialog();
  const [disabledAddToCart, setDisabledAddToCart] = React.useState(false);
  const [countProductsOnCart, setCountProductsOnCart] = useState(
    product.min_amount
  );
  const changeCount = (value: number, isInputEnter?: boolean) => {
    if (isInputEnter) {
      setCountProductsOnCart(value);
      return;
    }
    if (value < product.min_amount) {
      return;
    }

    setCountProductsOnCart(value);
  };
  const data = [
    {
      id: listItem.product.productid,
      quantity: countProductsOnCart,
      options: [],
    },
  ];
  const ratings = listItem.product.ratings?.overall;
  const snackbar = useSnackbar();
  const onCountInputBlur = () => {
    if (product.mult_order_quantity) {
      setCountProductsOnCart(
        Math.ceil(countProductsOnCart / product.min_amount) * product.min_amount
      );
    }
  };
  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: listItem.image,
      label: product.product,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/add-comment/product/${listInfo.productListId}/${listItem.list_item_id}`
        ),
    },
    {
      label: "Move",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/move-product/product/${listInfo.productListId}/${listItem.list_item_id}`
        ),
    },
    {
      label: "Delete",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/delete-product/product/${listInfo.productListId}/${listItem.list_item_id}`
        ),
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
            index={index}
            isFirst={listInfo.items.length === 0}
            isLast={index === listInfo.items.length - 1}
            classes={{ container: { "d-none": list.items.length === 1 } }}
            drag={drag}
          />
        ) : (
          <div className="product-list-item-movable-area-placeholder" />
        )}
      </div>

      <div className="product-list-item-info-content">
        <img
          className="product-list-item-image product-image"
          src={listItem.product.image}
        />
        <div className="product-list-item-info">
          <div className="product-list-item-info-container">
            <a
              href={`/product/${listItem.productId}/`}
              className={cn("product-list-item-name", Styles.productInfoName)}
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

          {ratings && (
            <Tooltip
              target={
                <div className="tooltip-rating-stars-target gap-2 d-flex">
                  <RatingStars rating={ratings.total} />
                  <a className="d-none d-md-block">
                    <Chevron />
                  </a>
                  <a className="lh-sm" href={`/product/${listItem.productId}/`}>
                    {ratings.rates.reduce(
                      (pv, cv) => pv + parseInt(cv.totalRates),
                      0
                    )}
                  </a>
                </div>
              }
              content={
                <div className={Styles.rating}>
                  <OverallRating ratings={ratings} />
                  <div className="text-center mt-14">
                    <a href={`/product/${listItem.productId}/`}>
                      See all customer reviews
                    </a>
                  </div>
                </div>
              }
            />
          )}

          <div className="d-flex align-items-center">
            <div className={Styles.productInfoPrice}>0.00</div>
            <div className="multiplication-symbol">X</div>
            <CountGroup
              avail={product.avail}
              onBlur={onCountInputBlur}
              value={countProductsOnCart}
              onChange={changeCount}
              minAmount={product.min_amount}
              multOrderQuantity={product.mult_order_quantity}
              className={Styles.counter}
            />
          </div>
          {edit &&
            (listItem.comment ? (
              <ListProductItemComment
                listItem={listItem}
                list={list}
                onEditCommentClick={editCommentDialog.handleClickOpen}
              />
            ) : (
              <div
                onClick={editCommentDialog.handleClickOpen}
                className={cn("add-comment-text", "d-inline-block")}
              >
                Add comment, quantity & priority
              </div>
            ))}
        </div>
      </div>

      <div>
        <div
          className={cn(
            "text-center",
            "text-md-end",
            Styles.productInfoDate,
            "mb-lg-10",
            "mb-12"
          )}
        >
          Item added {moment(listItem.add_date).utc().format("MMM DD, Y")}
        </div>
        {!product.outOfStock && (
          <ListProductItemBtns
            list={list}
            disabledAddToCart={disabledAddToCart}
            btnLabel={"Add to cart"}
            edit={edit}
            // outOfStock={info.product}
            deleteItem={deleteProductDialog.handleClickOpen}
            onMainBtnClick={() => {
              setDisabledAddToCart(true);

              console.log("add to cart", {});

              cartAdd(data, () => {
                dispatch(
                  cartGetAction({
                    success(res) {
                      dispatch(cartSetAction({ cart: res.data }));
                      snackbar.show(
                        `${listItem.product.product} added to cart`
                      );
                      setDisabledAddToCart(false);
                    },
                  })
                );
              });
            }}
            time={listItem.add_date}
            listId={listItem.product_list_id}
            listItemId={listItem.list_item_id}
            handleDelete={deleteProductDialog.handleClickOpen}
          />
        )}
        {product.outOfStock && "out of stock"}
      </div>
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
          listId={listId}
          list_item_id={listItem.list_item_id}
          info={listItem}
        />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteProductDialog.open}
        title={"Confirm delete"}
        onClose={deleteProductDialog.handleClose}
      >
        <ConfirmDelete
          onCancelClick={deleteProductDialog.handleClose}
          onDeleteClick={() => {
            deleteItem();
            deleteProductDialog.handleClose();
          }}
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
