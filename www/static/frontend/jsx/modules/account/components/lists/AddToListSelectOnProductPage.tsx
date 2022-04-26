import React, {useEffect, useState} from "react";
import useCLickListener from "@client/modules/account/hooks/useClickListener";
import cn from "classnames";
import {useDispatch} from "react-redux";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";
import {
  addProduct,
} from "@client/jsx/redux/actions/account-actions/ListsActions";
import {useDialog} from "@client/modules/account/hooks/useDialog";
import {CreateNewListDialog} from "@client/modules/account/components/lists/CreateNewListDialog";
import {AddProductToList} from "@client/modules/account/components/lists/AddProductToList";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import {List} from "@client/modules/account/ts/types/list.type";
import Styles from "@client/modules/account/components/lists/AddToListSelectOnProductPage.module.scss";
import Medium from "@client/modules/icon/components/account/chevron-down/Medium";
import StyleUtils from "@client/style-modules/style-utils.module.scss";
import Plus from "@client/jsx/modules/icon/components/account/plus/Plus";
import ImageNotAvailable from "@client/jsx/components/common/image-not-available/ImageNotAvailable";

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
  const {name, label = ""} = props;
  const user = useSelectorAccount((e) => e.user);
  const site = useSelectorAccount((e) => e.site);
  const {lists} = useSelectorAccount((e) => e.lists);
  const productInfo = site.product_info?.product || {};
  const productId = productInfo?.productid;
  const [open, setOpen] = useState(false);
  const [selectedList, setSelectedList] = useState(null);
  const [isAlreadyInList, setIsAlreadyInList] = useState(false);
  const addProductDialog = useDialog();
  const clickListener = useCLickListener(() => setOpen(false));
  const buttonRef = React.useRef<HTMLDivElement>(null);
  const dispatch = useDispatch();

  function showAddProductContent() {
    addProductDialog.handleClickOpen();
  }

  function createList() {
    createListDialog.handleClickOpen();
  }

  function addProductToList(product_list_id: number) {
    const currentList = lists.find((e) => e.product_list_id === product_list_id);
    const product = currentList.items.find((item) => item.productid === parseInt(productId))

    if (product) {
      setIsAlreadyInList(true);
      setSelectedList(lists.find((e) => e.product_list_id === product_list_id));
      showAddProductContent(product_list_id);
      return;
    }

    setIsAlreadyInList(false);

    dispatch(
      addProduct(product_list_id, productId, null, () => showAddProductContent(product_list_id))
    );

    setSelectedList(lists.find((list) => list.product_list_id === product_list_id));
  }

  function onCreateList(list: List) {
    setSelectedList(list);
    createListDialog.handleClose();

    dispatch(
      addProduct(list.product_list_id, productId, null, () =>
        showAddProductContent(list.product_list_id)
      )
    );
  }

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

  function getStoreUrl(link: string) {
    if (!link) {
      return null;
    }

    return `https://i1.s3stores.com/${link}`;
  }

  function getListImagePreview(list) {
    let image;
    let listHasIdea = false;

    for (const item of list.items) {
      switch (item.product_type) {
        case "product":
          if (item.product.images.length === 0) {
            continue;
          }

          return getStoreUrl(item.product.images[0].image.path);

        case "idea":
          listHasIdea = true;
      }
    }

    if (!image && listHasIdea) {
      image = "/static/frontend/images/icons/account/idea-logo.svg";
    }

    return image;
  }

  function imageTemplate(list) {
    const image = getListImagePreview(list);

    return (
      <div className={cn(Styles.imageContainer, "d-flex", "justify-content-center", "flex-shrink-0")}>
        {
          image ?
          <img className={Styles.image} src={getListImagePreview(list)} alt=""/> :
          <ImageNotAvailable/>
        }
      </div>
    );
  }

  return (
    <div className={cn(classes.container)}>
      {label && (
        <label
          className={cn(["form-input-label", Styles.addToListLabel])}
        >
          {label}
        </label>
      )}

      <div
        onClick={(e) => {
          e.stopPropagation();
          setOpen(!open);
        }}
        className={cn("select-wrapper", props.classes?.input)}
        ref={buttonRef}
      >
        <input
          value={null}
          className="select__input"
          type="hidden"
          name={name}
        />

        <div className={cn(classes.selectHeader)}>
          <div className={cn(classes.label)}>ADD TO LIST</div>

          <div className={cn(classes.arrowButton)}>
            <Medium className={cn(classes.arrowButtonIcon)}/>
          </div>
        </div>

        {open && (
          <ul
            className={cn(
              "form-select-list add-to-list-select-list",
              props.classes?.selectList
            )}
          >
            <div className="add-to-list-select-list-items">
              {lists?.map((list) => (
                <li
                  onClick={() => addProductToList(list.product_list_id)}
                  className="form-select-item add-to-list-select-item"
                >
                  {imageTemplate(list)}

                  <div className="form-select-item-label">{list.name}</div>
                </li>
              ))}
            </div>

            <div
              onClick={createList}
              className={cn(classes.createListButton)}
            >
              <Plus className={Styles.createListButtonIcon}/>

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
