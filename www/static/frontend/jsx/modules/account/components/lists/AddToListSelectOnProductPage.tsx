import React, { useEffect, useState } from "react";
import useCLickListener from "@client/modules/account/hooks/useClickListener";
import { Grid } from "@material-ui/core";
import classnames from "classnames";
import { useDispatch } from "react-redux";
import {
  addProduct,
  getLists,
} from "@client/jsx/redux/actions/account-actions/ListsActions";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { CreateNewListDialog } from "@client/modules/account/components/lists/CreateNewListDialog";
import { AddProductToList } from "@client/modules/account/components/lists/AddProductToList";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { List } from "@client/modules/account/ts/types/list.type";

interface AddToListSelectOnProductPageProps {
  items: List[];
  onClick: () => void;
  value: any;
  name: string;
  label: string;
  classes: any;
  id: string;
}

export const AddToListSelectOnProductPage: React.FC<AddToListSelectOnProductPageProps> =
  ({ onClick, name, label = "", classes = undefined }) => {
    const lists = accountStore.getState().lists.lists;

    const id = "1";

    const [open, setOpen] = useState(false);

    const [selectedList, setSelectedList] = useState(null);

    const [isAlreadyInList, setIsAlreadyInList] = useState(false);

    const breakpoint = useBreakpoint();

    const addProductDialog = useDialog();

    const clickListener = useCLickListener(setOpen, id);

    const dispatch = useDispatch();

    const showAddProductContent = (listId) => {
      breakpoint({
        xs: () =>
          window.location.assign(
            `/account/your-lists/add-product-to-list/${isAlreadyInList}/${listId}/${window.appData.product_info.product.productcode}`
          ),
        sm: addProductDialog.handleClickOpen,
      });
    };

    const createList = () => {
      breakpoint({
        xs: () =>
          window.location.assign(
            `/account/your-lists/add-list/${window.appData.product_info.product.productcode}`
          ),
        sm: createListDialog.handleClickOpen,
      });
    };

    const addProductToList = (listId: string) => {
      if (
        lists
          .find((e) => e.product_list_id === listId)
          ?.products.find(
            (e) =>
              e.product_id === window.appData.product_info.product.productid
          )
      ) {
        setIsAlreadyInList(true);
        setSelectedList(lists.find((e) => e.product_list_id === listId));
        showAddProductContent(listId);
        return;
      }
      setIsAlreadyInList(false);
      dispatch(
        addProduct(
          listId,
          window.appData.product_info.product.productid,
          null,
          () => showAddProductContent(listId)
        )
      );
      setSelectedList(lists.find((e) => e.product_list_id === listId));
    };

    const onCreateList = (listInfo) => {
      setSelectedList(listInfo);
      createListDialog.handleClose();
      dispatch(
        addProduct(
          listInfo.product_list_id,
          window.appData.product_info.product.productid,
          null,
          () => showAddProductContent(listInfo.product_list_id)
        )
      );
    };

    useEffect(() => {
      clickListener.startListen();
      if (!lists) {
        dispatch(getLists());
      }

      return () => {
        clickListener.endListen();
      };
    }, []);

    const value = {
      value: undefined,
      viewValue: "ADD TO LIST",
    };

    const createListDialog = useDialog();

    return (
      <Grid
        className={classnames(
          `select select-send  ${open && "open"}`,
          classes?.group
        )}
        container
        alignItems="center"
        justifyContent="space-between"
      >
        {label && (
          <label className="form-input-label add-to-list-label">{label}</label>
        )}
        <div
          onClick={() => {
            setOpen(!open);
          }}
          id={id}
          className={classnames("select-wrapper", classes?.input)}
        >
          <input
            value={value.value}
            className="select__input"
            type="hidden"
            name={name}
          />
          <div
            id={id}
            className={classnames(
              classes?.selectHeader,
              "form-select-head",
              "add-to-list-header"
            )}
          >
            <div id={id} className="add-to-list-label">
              {value?.viewValue}
            </div>
            <div id={id} className="add-to-list-arrow-block" />
          </div>
          {open && (
            <ul
              className={classnames(
                "form-select-list add-to-list-select-list",
                classes?.selectList
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
                className="create-list-btn-container add-to-list-create-list"
              >
                <div className="sidebar-list-cross add-to-list-create-list-cross">
                  <img src="/static/frontend/images/icons/account/plus.svg" />
                </div>
                <div className="create-list-label">create a list</div>
              </div>
            </ul>
          )}
        </div>
        <CreateNewListDialog
          open={createListDialog.open}
          handleClose={createListDialog.handleClose}
          productId={window.appData.product_info.product.productid}
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
            isAlreadyInList={isAlreadyInList}
          />
        </BootstrapDialogHOC>
      </Grid>
    );
  };
