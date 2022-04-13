import React from "react";
import { NoItemsBlock } from "@modules/account/components/lists/NoItemsBlock";
import { ListProductItem } from "@modules/account/components/lists/ListProductItem";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import { useDispatch } from "react-redux";
import {
  reorderList,
  deleteItem,
} from "@redux/actions/account-actions/ListsActions";
import { reorderMass } from "@modules/account/utils/reorder-mass";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { ListProductIdeaItem } from "@modules/account/components/lists/ListProductIdeaItem";
import { AccountListProductActionEnum } from "@modules/account/ts/types/account-list-product-action";
import { DeleteProductPlaceholder } from "@modules/account/components/lists/DeleteProductPlaceholder";
import { MovedProductPlaceholder } from "@modules/account/components/lists/MovedProductPlaceholder";

export const ListProductItems: React.FC = () => {
  const { listView, loading } = useSelectorAccount((state) => state.lists);
  const userId = useSelectorAccount((e) => e.user.user_id);

  function listIsEdit() {
    if (listView.owner.userId === userId) {
      return UserPrivateVariantsEnum.EDIT;
    }
    if (
      listView?.users.find((user) => user.userId === userId)?.role ===
      UserPrivateVariantsEnum.EDIT
    ) {
      return UserPrivateVariantsEnum.EDIT;
    }
    return UserPrivateVariantsEnum.VIEW;
  }

  const edit = listIsEdit() !== UserPrivateVariantsEnum.VIEW;
  const dispatch = useDispatch();
  const getItemStyle = (isDragging, draggableStyle) => ({
    ...draggableStyle,
    boxShadow: isDragging ? "0px 4px 5px 0px rgba(0, 0, 0, 0.25)" : "",
    height: isDragging ? draggableStyle.height - 1 : "auto",
  });
  function deleteItemHandler(list_item_id) {
    dispatch(deleteItem({ data: { list_item_id } }));
  }
  const onDragEnd = (result: any) => {
    if (!result.destination) {
      return;
    }
    reorderProductList(result.source.index, result.destination.index);
  };
  const reorderProductList = (startIndex: number, endIndex: number) => {
    const reOrder = reorderMass(listView.products, startIndex, endIndex);
    dispatch(reorderList(reOrder, listView?.productListId));
  };

  if (loading) {
    return <span className="ps-4">Loading..</span>;
  }

  return (
    <DragDropContext onDragEnd={onDragEnd}>
      <Droppable droppableId="droppable">
        {(provided) => (
          <div {...provided.droppableProps} ref={provided.innerRef}>
            {listView.products.length ? (
              listView.products.map((listItem, index) => {
                return (
                  <Draggable
                    key={`${index}_${listItem.list_item_id}`}
                    draggableId={`${index}_${listItem.list_item_id}`}
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
                          switch (listItem?.typeAction?.type) {
                            case AccountListProductActionEnum.DELETE: {
                              return (
                                <DeleteProductPlaceholder
                                  name={listItem.typeAction.productName}
                                  listItem={listItem}
                                />
                              );
                            }
                            case AccountListProductActionEnum.MOVE: {
                              return (
                                <MovedProductPlaceholder
                                  label={listItem.typeAction.listName}
                                  cache={listItem.typeAction.toListId}
                                  productName={listItem.typeAction.productName}
                                />
                              );
                            }
                            default:
                              switch (listItem.product_type) {
                                case ListItemTypeEnum.PRODUCT:
                                  return (
                                    <ListProductItem
                                      deleteItem={() =>
                                        deleteItemHandler(listItem.list_item_id)
                                      }
                                      index={index}
                                      drag={{ ...provided.dragHandleProps }}
                                      reorderProductList={reorderProductList}
                                      listId={listView.product_list_id}
                                      listInfo={listView}
                                      edit={edit}
                                      productItem={listItem}
                                    />
                                  );
                                case ListItemTypeEnum.IDEA:
                                  return (
                                    <ListProductIdeaItem
                                      deleteItem={() =>
                                        deleteItemHandler(listItem.list_item_id)
                                      }
                                      index={index}
                                      drag={{ ...provided.dragHandleProps }}
                                      reorderProductList={reorderProductList}
                                      edit={edit}
                                      listItem={listItem}
                                    />
                                  );
                              }
                          }
                        })()}
                      </div>
                    )}
                  </Draggable>
                );
              })
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
