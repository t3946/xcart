import React, { useEffect, useState } from "react";
import useCLickListener from "@modules/account/hooks/useClickListener";
import classnames from "classnames";
import { useDispatch } from "react-redux";
import { addProduct } from "@redux/actions/account-actions/ListsActions";
import Store from "@redux/stores/Store";
import { useDialog } from "@modules/account/hooks/useDialog";
import { CreateNewListDialog } from "@modules/account/components/lists/CreateNewListDialog";
import { AddProductToList } from "@modules/account/components/lists/AddProductToList";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { List } from "@modules/account/ts/types/list.type";
import Styles from "@modules/account/components/lists/AddToListSelectOnProductPage.module.scss";
import AppData from "@utils/AppData";
import Medium from "@modules/icon/components/account/chevron-down/Medium";
import Plus from "@modules/icon/components/account/plus/Plus";

interface IProps {
  items: List[];
  value: any;
  name: string;
  label: string;
  classes: any;
  id: string;
  product?: any;
}

export const AddToListSelectOnProductPage: React.FC<IProps> = (
  props: IProps
) => {
  const { name, label = "", product } = props;
  const lists = Store.getState().lists.lists;
  const productInfo = product || Object.keys(AppData?.products)[0];
  const [open, setOpen] = useState(false);
  const [selectedList, setSelectedList] = useState(null);
  const [isAlreadyInList, setIsAlreadyInList] = useState(false);
  const breakpoint = useBreakpoint();
  const addProductDialog = useDialog();
  const clickListener = useCLickListener(() => setOpen(false));
  const buttonRef = React.useRef<HTMLDivElement>(null);

  const dispatch = useDispatch();

  const showAddProductContent = (listId) => {
    breakpoint({
      xs: () =>
        window.location.assign(
          `/account/your-lists/add-product-to-list/${isAlreadyInList}/${listId}/${productInfo.productcode}`
        ),
      sm: addProductDialog.handleClickOpen,
    });
  };

  const createList = () => {
    breakpoint({
      xs: () =>
        window.location.assign(
          `/account/your-lists/add-list/${productInfo.productcode}`
        ),
      sm: createListDialog.handleClickOpen,
    });
  };

  const addProductToList = (listId: string) => {
    if (
      lists
        .find((e) => e.product_list_id === listId)
        ?.products.find((e) => e.product_id === productInfo.productid)
    ) {
      setIsAlreadyInList(true);
      setSelectedList(lists.find((e) => e.product_list_id === listId));
      showAddProductContent(listId);
      return;
    }

    setIsAlreadyInList(false);

    dispatch(
      addProduct(listId, productInfo.productid, null, () =>
        showAddProductContent(listId)
      )
    );

    setSelectedList(lists.find((e) => e.product_list_id === listId));
  };

  const onCreateList = (listInfo): void => {
    setSelectedList(listInfo);
    createListDialog.handleClose();

    dispatch(
      addProduct(listInfo.product_list_id, productInfo.productid, null, () =>
        showAddProductContent(listInfo.product_list_id)
      )
    );
  };

  useEffect(() => {
    clickListener.startListen();

    return () => {
      clickListener.endListen();
    };
  }, []);

  const createListDialog = useDialog();

  const classes = {
    selectHeader: [
      "d-flex",
      "add-to-list-header",
      "p-0",
      "overflow-hidden",
      props.classes?.selectHeader,
      Styles.addToListHeader,
      "cursor-pointer",
    ],

    container: [
      "align-items-center",
      "justify-content-between",
      "select",
      "select-send",
      {
        open: open,
      },
    ],

    label: ["bold", "text-center", Styles.addToListLabel],

    arrowButton: [
      "d-flex",
      "align-items-center",
      "justify-content-center",
      Styles.addToListArrowBlock,
    ],

    arrowButtonIcon: [
      Styles.addToListArrowBlockIcon,
      {
        [Styles.addToListArrowBlockIcon__flip]: open,
      },
    ],

    createListButton: [
      "d-flex",
      "justify-content-center",
      "align-items-center",
      "w-100",
      Styles.createListButton,
      "cursor-pointer",
    ],
  };

  return (
    <div className={classnames(classes.container)}>
      {label && (
        <label
          className={classnames(["form-input-label", Styles.addToListLabel])}
        >
          {label}
        </label>
      )}

      <div
        onClick={(e) => {
          e.stopPropagation();
          setOpen(!open);
        }}
        className={classnames("select-wrapper", props.classes?.input)}
        ref={buttonRef}
      >
        <input value={""} className="select__input" type="hidden" name={name} />

        <div className={classnames(classes.selectHeader)}>
          <div className={classnames(classes.label)}>ADD TO LIST</div>

          <div className={classnames(classes.arrowButton)}>
            <Medium className={classnames(classes.arrowButtonIcon)} />
          </div>
        </div>

        {open && (
          <ul
            className={classnames(
              "form-select-list add-to-list-select-list",
              props.classes?.selectList
            )}
          >
            <div className="add-to-list-select-list-items">
              {lists?.map((item) => {
                return (
                  <li
                    onClick={() => addProductToList(item.product_list_id)}
                    className="form-select-item add-to-list-select-item"
                  >
                    <img
                      className="form-select-item-img"
                      src={
                        item?.products[0]?.image ||
                        "/static/frontend/images/icons/account/idea-logo.svg"
                      }
                    />

                    <div className="form-select-item-label">{item.name}</div>
                  </li>
                );
              })}
            </div>

            <div
              onClick={createList}
              className={classnames(classes.createListButton)}
            >
              <Plus className={Styles.createListButtonIcon} />

              <div className="create-list-label ms-4">create a list</div>
            </div>
          </ul>
        )}
      </div>

      <CreateNewListDialog
        open={createListDialog.open}
        handleClose={createListDialog.handleClose}
        productId={productInfo.productid}
        onProductAdded={onCreateList}
        actionType={"product"}
      />

      <BootstrapDialogHOC
        show={addProductDialog.open}
        title={"Add to list"}
        onClose={addProductDialog.handleClose}
      >
        <AddProductToList
          onCancelClick={addProductDialog.handleClose}
          info={selectedList}
          product={productInfo}
          isAlreadyInList={isAlreadyInList}
        />
      </BootstrapDialogHOC>
    </div>
  );
};
