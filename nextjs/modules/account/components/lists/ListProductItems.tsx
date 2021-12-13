import React, { useContext, useEffect } from "react";
import { NoItemsBlock } from "@modules/account/components/lists/NoItemsBlock";
import { ListProductItem } from "@modules/account/components/lists/ListProductItem";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import { useDispatch } from "react-redux";
import {
  deleteProduct,
  moveProduct,
  reorderList,
  setLists,
} from "@redux/actions/account-actions/ListsActions";
import { reorderMass } from "@modules/account/utils/reorder-mass";
import { AccountListProductActionEnum } from "@modules/account/ts/types/account-list-product-action";
import { MovedProductPlaceholder } from "@modules/account/components/lists/MovedProductPlaceholder";
import Store from "@redux/stores/Store";
import { DeleteProductPlaceholder } from "@modules/account/components/lists/DeleteProductPlaceholder";
import { ListProductIdeaItem } from "@modules/account/components/lists/ListProductIdeaItem";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";
import { ListItem } from "@modules/account/ts/types/list.type";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { SelectValue } from "@modules/account/ts/types/select-value.type";

export const ListProductItems = ({ info, path, edit }) => {
  useEffect(() => {
    return () => {
      deleteProductsWithTypeAction();
    };
  }, [path]);

  const dispatch = useDispatch();

  const deleteProductsWithTypeAction = () => {
    const lists = Store.getState().lists.lists;

    dispatch(
      setLists(
        lists.map((e) => {
          return {
            ...e,
            products: e.products?.filter((e) => {
              if (!e.typeAction) {
                return e;
              }
            }),
          };
        })
      )
    );
  };

  const getItemStyle = (isDragging, draggableStyle) => ({
    ...draggableStyle,
    boxShadow: isDragging ? "0px 4px 5px 0px rgba(0, 0, 0, 0.25)" : "",
    height: isDragging ? draggableStyle.height - 1 : "auto",
  });

  const { showSnackbar } = useContext(SnackbarContext);

  const deleteItem = (id) => {
    dispatch(deleteProduct(info.product_list_id, id, () => {}));
  };

  const onDragEnd = (result) => {
    if (!result.destination) {
      return;
    }
    reorderProductList(result.source.index, result.destination.index);
  };

  const reorderProductList = (startIndex, endIndex) => {
    dispatch(
      reorderList(
        reorderMass<string>(info.products, startIndex, endIndex),
        info.product_list_id
      )
    );
  };

  const onMoveClick = (
    value: SelectValue<string, string>,
    listId: string,
    product: ListItem
  ) => {
    const toList = Store.getState().lists.lists.find(
      (e) => e.product_list_id === value.value
    );

    const productOnList = toList.products.find(
      (e) => e.product_id === product.product_id
    );
    if (productOnList) {
      showSnackbar({
        header: "Error",
        message: `This item already added to list`,
        theme: "error",
      });
      return;
    }
    setTimeout(() => {
      dispatch(moveProduct(listId, value, product));
    }, 0);
  };
  return (
    <DragDropContext onDragEnd={onDragEnd}>
      <Droppable droppableId="droppable">
        {(provided, snapshot) => (
          <div {...provided.droppableProps} ref={provided.innerRef}>
            {info?.products?.length ? (
              info.products.map((e, index) => (
                <Draggable
                  key={String(e.product_id)}
                  draggableId={String(e.product_id)}
                  index={index}
                >
                  {(provided, snapshot) => (
                    <div
                      ref={provided.innerRef}
                      {...provided.draggableProps}
                      style={getItemStyle(
                        snapshot.isDragging,
                        provided.draggableProps.style
                      )}
                    >
                      {(() => {
                        switch (e?.typeAction?.type) {
                          case AccountListProductActionEnum.DELETE: {
                            return (
                              <DeleteProductPlaceholder
                                productListId={info.product_list_id}
                                listItemId={e.product_id}
                                name={e.typeAction.productName}
                                product={e}
                              />
                            );
                          }
                          case AccountListProductActionEnum.MOVE: {
                            return (
                              <MovedProductPlaceholder
                                label={e.typeAction.listName}
                                id={e.typeAction.toListId}
                                productName={e.typeAction.productName}
                              />
                            );
                          }
                          default: {
                            switch (e.product_type) {
                              case ListItemTypeEnum.PRODUCT:
                                return (
                                  <React.Fragment>
                                    <ListProductItem
                                      deleteItem={() =>
                                        deleteItem(e.product_id)
                                      }
                                      index={index}
                                      drag={{ ...provided.dragHandleProps }}
                                      info={e}
                                      reorderProductList={reorderProductList}
                                      listId={info.product_list_id}
                                      listInfo={info}
                                      edit={edit}
                                      onMoveClick={(value) =>
                                        onMoveClick(
                                          value,
                                          info.product_list_id,
                                          e
                                        )
                                      }
                                    />
                                  </React.Fragment>
                                );
                              case ListItemTypeEnum.IDEA:
                                return (
                                  <React.Fragment>
                                    <ListProductIdeaItem
                                      deleteItem={() =>
                                        deleteItem(e.product_id)
                                      }
                                      index={index}
                                      drag={{ ...provided.dragHandleProps }}
                                      info={e}
                                      reorderProductList={reorderProductList}
                                      listInfo={info}
                                      edit={edit}
                                      onMoveClick={(value) =>
                                        onMoveClick(
                                          value,
                                          info.product_list_id,
                                          e
                                        )
                                      }
                                    />
                                  </React.Fragment>
                                );
                            }
                          }
                        }
                      })()}
                    </div>
                  )}
                </Draggable>
              ))
            ) : (
              <NoItemsBlock listInfo={info} />
            )}
            {provided.placeholder}
          </div>
        )}
      </Droppable>
    </DragDropContext>
  );
};
