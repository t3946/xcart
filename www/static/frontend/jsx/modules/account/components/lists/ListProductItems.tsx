import React, { useEffect, useState } from "react";
import { NoItemsBlock } from "@client/modules/account/components/lists/NoItemsBlock";
import { ListProductItem } from "@client/modules/account/components/lists/ListProductItem";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import { useDispatch } from "react-redux";
import {
  deleteProduct,
  reorderList,
  setLists,
} from "../../../../redux/actions/account-actions/ListsActions";
import { reorderMass } from "@client/modules/account/utils/reorder-mass";
import { AccountListProductActionEnum } from "@client/modules/account/ts/types/account-list-product-action";
import { MovedProductPlaceholder } from "@client/modules/account/components/lists/MovedProductPlaceholder";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { DeleteProductPlaceholder } from "@client/modules/account/components/lists/DeleteProductPlaceholder";

export const ListProductItems = ({ info, path }) => {
  useEffect(() => {
    deleteProductsWithTypeAction();
  }, [path]);

  const dispatch = useDispatch();

  const deleteProductsWithTypeAction = () => {
    dispatch(
      setLists(
        accountStore.getState().lists.lists.map((e) => {
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

  const deleteItem = (id) => {
    dispatch(deleteProduct(info.product_list_id, id));
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
  return (
    <DragDropContext onDragEnd={onDragEnd}>
      <Droppable droppableId="droppable">
        {(provided, snapshot) => (
          <div {...provided.droppableProps} ref={provided.innerRef}>
            {info?.products?.length ? (
              info.products.map((e, index) => (
                <Draggable
                  key={e.product_id}
                  draggableId={e.product_id}
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
                                product_list_id={info.product_list_id}
                                list_items_id={e.product_id}
                                name={e.typeAction.productName}
                              />
                            );
                          }
                          case AccountListProductActionEnum.MOVE: {
                            return (
                              <MovedProductPlaceholder
                                label={e.typeAction.listName}
                                id={e.typeAction.toListId}
                              />
                            );
                          }

                          default: {
                            return (
                              <ListProductItem
                                deleteItem={() => deleteItem(e.product_id)}
                                index={index}
                                drag={{ ...provided.dragHandleProps }}
                                info={e}
                                reorderProductList={reorderProductList}
                                listId={info.product_list_id}
                              />
                            );
                          }
                        }
                      })()}
                    </div>
                  )}
                </Draggable>
              ))
            ) : (
              <NoItemsBlock />
            )}
            {provided.placeholder}
          </div>
        )}
      </Droppable>
    </DragDropContext>
  );
};
