import React, { useState } from "react";
import { ListItemMovableArea } from "@modules/account/components/lists/ListItemMovableArea";
import RatingStars from "@modules/shared/components/ratings/RatingStars";
import { Tooltip } from "@modules/account/components/shared/Tooltip";
import { ListProductItemBtns } from "@modules/account/components/lists/ListProductItemBtns";
import { ListProductItemComment } from "@modules/account/components/lists/ListProductItemComment";
import { EditComment } from "@modules/account/components/lists/EditComment";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { useDialog } from "@modules/account/hooks/useDialog";
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
import Styles from "@modules/account/components/lists/item-product/ListProductItem.module.scss";
import {
  getAction as cartGetAction,
  setAction as cartSetAction,
} from "@redux/actions/CartActions";
import getStoreUrl from "@utils/getStoreUrl";
import Price from "@components/common/price/Price";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import MobileMenu from "@modules/account/components/lists/item-product/MobileMenu";

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
  const currency = useSelectorAccount((e) => e.config.site.currency);
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

  function itemAddedTemplate(className) {
    return (
      <div className={cn(Styles.productInfoDate, className)}>
        Item added {moment(listItem.add_date).utc().format("MMM DD, Y")}
      </div>
    );
  }

  function itemPriceGroupTemplate() {
    return (
      <>
        <div className="d-flex align-items-center d-none d-lg-flex">
          <Price
            currency={currency}
            price={product.price}
            classes={{ container: Styles.productInfoPrice }}
          />

          <span className={"mx-2"}>X</span>

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
      </>
    );
  }

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
            isFirst={index === 0}
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
          src={getStoreUrl(listItem.product.images[0].path)}
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

          {itemPriceGroupTemplate()}

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
                className={cn("add-comment-text", "d-none", "d-inline-block")}
              >
                Add comment, quantity & priority
              </div>
            ))}

          <Price
            currency={currency}
            price={product.price}
            classes={{
              container: [Styles.productInfoPrice, "d-block", "d-lg-none"],
            }}
          />

          {itemAddedTemplate(["mt-18", "d-md-none"])}
        </div>
      </div>

      <div>
        {itemAddedTemplate([
          "text-center",
          "text-md-end",
          "mb-lg-10",
          "mb-12",
          "d-none",
          "d-md-block",
        ])}

        {!product.outOfStock && (
          <ListProductItemBtns
            className={["mt-12", "mt-md-0"]}
            list={list}
            disabledAddToCart={disabledAddToCart}
            btnLabel={"Add to cart"}
            edit={edit}
            // outOfStock={info.product}
            deleteItem={deleteProductDialog.handleClickOpen}
            onMainBtnClick={() => {
              setDisabledAddToCart(true);

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

      <MobileMenu item={listItem} list={listInfo} dialog={mobileMenuDialog} />
    </div>
  );
};
