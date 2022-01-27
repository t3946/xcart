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
import { ListProductInfo } from "@modules/account/ts/types/list.type";
import { ListProductItemProps } from "@modules/account/ts/types/list-product-item-props.type";
import { cartAdd } from "@redux/reducers/appCartReducer";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { CountInput } from "@modules/account/components/shared/CountInput";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";

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

  const [countProductsOnCart, setCountProductsOnCart] = useState(
    product.min_amount
  );

  const changeCount = (value: number, isInputEnter?: boolean) => {
    if (isInputEnter) {
      if (value <= product.minAmount) {
        return;
      }
      if (value > product.avail) {
        setCountProductsOnCart(product.avail);
        return;
      }
      setCountProductsOnCart(value);
      return;
    }
    if (value <= product.minAmount) {
      return;
    }
    if (value > product.avail) {
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

  const { showSnackbar } = useContext(SnackbarContext);

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
      image: productItem.image,
      label: product.product,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/add-comment/product/${listInfo.productListId}/${productItem.productId}`
        ),
    },
    {
      label: "Move",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/move-product/product/${listInfo.productListId}/${productItem.productId}`
        ),
    },
    {
      label: "Delete",
      onClick: () =>
        router.push(
          `/shopping-lists/actions/delete-product/product/${listInfo.productListId}/${productItem.productId}`
        ),
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
          src={productItem.product.image}
        />
        <div className="product-list-item-info">
          <div className="product-list-item-info-container">
            <a
              href={`/product/${productItem.productId}/`}
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
              minAmount={product.minAmount}
              multOrderQuantity={product.multOrderQuantity === "Y"}
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
      <ListProductItemBtns
        btnLabel={"Add to cart"}
        edit={edit}
        // outOfStock={info.product}
        deleteItem={deleteProductDialog.handleClickOpen}
        onMainBtnClick={() =>
          cartAdd(
            data,
            showSnackbar({
              header: "Success",
              message: `${productItem.product.product} added to cart`,
              theme: "success",
            })
          )
        }
        time={productItem.add_date}
        listId={productItem.product_list_id}
        productId={productItem.productId}
        handleDelete={deleteProductDialog.handleClickOpen}
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
          listId={listId}
          productId={productItem.productId}
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
