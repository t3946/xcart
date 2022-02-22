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
import { CountInput } from "@modules/account/components/shared/CountInput";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import cn from "classnames";
import moment from "moment";
import OverallRating from "@modules/shared/components/ratings/OverallRating";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import StylesListProductItems from "@modules/account/components/lists/ListProductItems.module.scss";
import Styles from "@modules/account/components/lists/ListProductItem.module.scss";
import {
  getAction as cartGetAction,
  setAction as cartSetAction,
} from "@redux/actions/CartActions";

export const ListProductItem: React.FC<ListProductItemProps> = ({
  productItem,
  drag,
  reorderProductList,
  index,
  listId,
  deleteItem,
  edit,
  listInfo,
}) => {
  const editCommentDialog = useDialog();
  const dispatch = useDispatch();
  let product: ListProductInfo;
  if ("product" in productItem.product) {
    product = productItem.product;
  }
  const router = useRouter();
  const breakpoint = useBreakpoint();
  const deleteProductDialog = useDialog();
  const mobileMenuDialog = useDialog();
  const [disabledAddToCart, setDisabledAddToCart] = React.useState(false);

  const [countProductsOnCart, setCountProductsOnCart] = useState(
    product.minAmount
  );
  const changeCount = (value: number, isInputEnter?: boolean) => {
    if (isInputEnter) {
      setCountProductsOnCart(value);
      return;
    }
    if (value < product.minAmount) {
      return;
    }

    setCountProductsOnCart(value);
  };

  const data = [
    {
      id: productItem.productId,
      quantity: countProductsOnCart,
      options: [],
    },
  ];

  const allRatings = useSelectorAccount((e) => e.productsRatings);

  const ratings = allRatings ? allRatings[productItem.productId] : undefined;

  const snackbar = useSnackbar();

  const onCountInputBlur = () => {
    if (product.multOrderQuantity) {
      setCountProductsOnCart(
        Math.ceil(countProductsOnCart / product.minAmount) * product.minAmount
      );
    }
  };

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: productItem.image,
      label: product.product,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/add-comment/product/${listInfo.productListId}/${productItem.list_items_id}`
        ),
    },
    {
      label: "Move",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/move-product/product/${listInfo.productListId}/${productItem.list_items_id}`
        ),
    },
    {
      label: "Delete",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/delete-product/product/${listInfo.productListId}/${productItem.list_items_id}`
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
            length={listInfo.products.length}
            drag={drag}
          />
        ) : (
          <div className="product-list-item-movable-area-placeholder" />
        )}
      </div>

      <div className="product-list-item-info-content">
        <img
          className="product-list-item-image product-image"
          src={productItem.product.image}
        />
        <div className="product-list-item-info">
          <div className="product-list-item-info-container">
            <a
              href={`/product/${productItem.productId}/`}
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
                <div className="tooltip-rating-stars-target">
                  <RatingStars rating={3} />
                </div>
              }
              content={
                <div className="rating-stars-tooltip">
                  <OverallRating ratings={ratings} />
                </div>
              }
            />
          )}

          <div className="d-flex align-items-center">
            <div className={Styles.productInfoPrice}>${product?.price}</div>
            <div className="multiplication-symbol">X</div>
            <CountInput
              avail={product.avail}
              onBlur={onCountInputBlur}
              value={countProductsOnCart}
              onChange={changeCount}
              minAmount={product.minAmount}
              multOrderQuantity={product.multOrderQuantity}
            />
          </div>
          {edit &&
            (productItem.comment ? (
              <ListProductItemComment
                info={productItem}
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
          Item added {moment(productItem.add_date).utc().format("MMM DD, Y")}
        </div>
        {!product.outOfStock && (
          <ListProductItemBtns
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
                        `${productItem.product.product} added to cart`
                      );
                      setDisabledAddToCart(false);
                    },
                  })
                );
              });
            }}
            time={productItem.add_date}
            listId={productItem.product_list_id}
            productId={productItem.list_items_id}
            handleDelete={deleteProductDialog.handleClickOpen}
          />
        )}
        {product.outOfStock && "out of stock"}
      </div>
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
          listId={listId}
          list_items_id={productItem.list_items_id}
          info={productItem}
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
