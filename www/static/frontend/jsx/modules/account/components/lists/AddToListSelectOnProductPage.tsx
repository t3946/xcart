import React, { useEffect, useState } from "react";
import useCLickListener from "@client/modules/account/hooks/useClickListener";
import classnames from "classnames";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";
import {
  addProduct,
  getLists,
} from "@client/jsx/redux/actions/account-actions/ListsActions";
import Store from "@client/jsx/redux/stores/Store";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { CreateNewListDialog } from "@client/modules/account/components/lists/CreateNewListDialog";
import { AddProductToList } from "@client/modules/account/components/lists/AddProductToList";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { List } from "@client/modules/account/ts/types/list.type";
import Styles from "@client/modules/account/components/lists/AddToListSelectOnProductPage.module.scss";
import AppData from "@client/jsx/utils/AppData";
import Medium from "@client/modules/icon/components/account/chevron-down/Medium";
import StyleUtils from "@client/style-modules/style-utils.module.scss";
import Plus from "@client/jsx/modules/icon/components/account/plus/Plus";

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
  const user = useSelectorAccount((e) => e.user);
  const lists = Store.getState().lists.lists;
  const productId = Object.keys(AppData?.products)[0];
  const productInfo = product || AppData?.products[productId];
  const [open, setOpen] = useState(false);
  const [selectedList, setSelectedList] = useState(null);
  const [isAlreadyInList, setIsAlreadyInList] = useState(false);
  const addProductDialog = useDialog();
  const clickListener = useCLickListener(() => setOpen(false));
  const buttonRef = React.useRef<HTMLDivElement>(null);

  const dispatch = useDispatch();

  const showAddProductContent = (listId) => {
    addProductDialog.handleClickOpen();
  };
  const createList = () => {
    createListDialog.handleClickOpen();
  };
  const addProductToList = (listId: number) => {
    if (
      lists
        .find((e) => e.productListId === listId)
        ?.products.find((e) => e.productId === parseInt(productId))
    ) {
      setIsAlreadyInList(true);
      setSelectedList(lists.find((e) => e.productListId === listId));
      showAddProductContent(listId);
      return;
    }

    setIsAlreadyInList(false);

    dispatch(
      addProduct(listId, productId, null, () => showAddProductContent(listId))
    );

    setSelectedList(lists.find((e) => e.productListId === listId));
  };

  const onCreateList = (listInfo: List): void => {
    setSelectedList(listInfo);
    createListDialog.handleClose();
    dispatch(
      addProduct(listInfo.productListId, productId, null, () =>
        showAddProductContent(listInfo.productListId)
      )
    );
  };

  useEffect(() => {
    clickListener.startListen();
    dispatch(getLists());
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
      StyleUtils.cursorPointer,
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
      StyleUtils.cursorPointer,
    ],
  };

  if (!user) return null;

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
        <input
          value={null}
          className="select__input"
          type="hidden"
          name={name}
        />

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
              {lists?.map((item) => (
                <li
                  onClick={() => addProductToList(item.productListId)}
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
              ))}
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
        productId={productId}
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
