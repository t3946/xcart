import React, { useContext } from "react";
import { NoItemsBlock } from "@modules/account/components/lists/NoItemsBlock";
import { ListProductItem } from "@modules/account/components/lists/ListProductItem";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import { useDispatch } from "react-redux";
import {
  reorderList,
  deleteProduct,
} from "@redux/actions/account-actions/ListsActions";
import { reorderMass } from "@modules/account/utils/reorder-mass";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";
import { List, ListItem } from "@modules/account/ts/types/list.type";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { ListProductIdeaItem } from "@modules/account/components/lists/ListProductIdeaItem";

export const ListProductItems: React.FC = () => {
  const listView: List = useSelectorAccount((state) => state.lists.listView);
  const edit = listView.role !== UserPrivateVariantsEnum.VIEW;
  const dispatch = useDispatch();

  const getItemStyle = (isDragging, draggableStyle) => ({
    ...draggableStyle,
    boxShadow: isDragging ? "0px 4px 5px 0px rgba(0, 0, 0, 0.25)" : "",
    height: isDragging ? draggableStyle.height - 1 : "auto",
  });

  const { showSnackbar } = useContext(SnackbarContext);

  const deleteItem = (id) => {
    dispatch(deleteProduct(listView.productListId, id));
  };

  const onDragEnd = (result: any) => {
    if (!result.destination) {
      return;
    }
    reorderProductList(result.source.index, result.destination.index);
  };

  const reorderProductList = (startIndex: number, endIndex: number) => {
    const reOrder = reorderMass(listView.products, startIndex, endIndex);
    dispatch(reorderList(reOrder, listView.productListId));
  };
  return (
    <DragDropContext onDragEnd={onDragEnd}>
      <Droppable droppableId="droppable">
        {(provided, snapshot) => (
          <div {...provided.droppableProps} ref={provided.innerRef}>
            {listView.products.length ? (
              listView.products.map((product, index) => (
                <Draggable
                  key={String(product.productId)}
                  draggableId={String(product.productId)}
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
                        switch (product?.typeAction?.type) {
                          // case AccountListProductActionEnum.DELETE: {
                          //   return (
                          //     <DeleteProductPlaceholder
                          //       productListId={info.product_list_id}
                          //       listItemId={e.product_id}
                          //       name={e.typeAction.productName}
                          //       product={e}
                          //     />
                          //   );
                          // }
                          // case AccountListProductActionEnum.MOVE: {
                          //   return (
                          //     <MovedProductPlaceholder
                          //       label={e.typeAction.listName}
                          //       id={e.typeAction.toListId}
                          //       productName={e.typeAction.productName}
                          //     />
                          //   );
                          // }
                          default:
                            switch (product.productType) {
                              case ListItemTypeEnum.PRODUCT:
                                return (
                                  <ListProductItem
                                    deleteItem={() =>
                                      deleteItem(product.productId)
                                    }
                                    index={index}
                                    drag={{ ...provided.dragHandleProps }}
                                    reorderProductList={reorderProductList}
                                    listId={listView.productListId}
                                    listInfo={listView}
                                    edit={edit}
                                    productItem={product}
                                  />
                                );
                              case ListItemTypeEnum.IDEA:
                                return (
                                  <ListProductIdeaItem
                                    deleteItem={() =>
                                      deleteItem(product.productId)
                                    }
                                    index={index}
                                    drag={{ ...provided.dragHandleProps }}
                                    reorderProductList={reorderProductList}
                                    edit={edit}
                                    productItem={product}
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
              <NoItemsBlock listInfo={listView} />
            )}
            {provided.placeholder}
          </div>
        )}
      </Droppable>
    </DragDropContext>
  );
};
