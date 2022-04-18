import React from "react";
import { NoItemsBlock } from "@modules/account/components/lists/NoItemsBlock";
import { ListProductItem } from "@modules/account/components/lists/ListProductItem";
import {
  DragDropContext,
  Droppable,
  Draggable,
  resetServerContext,
} from "react-beautiful-dnd";
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

interface IProps {
  list: any;
}

export const ListProductItems: React.FC<IProps> = (props) => {
  resetServerContext();
  const { list } = props;
  const userId = useSelectorAccount((e) => e.user.user_id);
  const edit = listIsEdit() !== UserPrivateVariantsEnum.VIEW;
  const dispatch = useDispatch();
  const getItemStyle = (isDragging, draggableStyle) => ({
    ...draggableStyle,
    boxShadow: isDragging ? "0px 4px 5px 0px rgba(0, 0, 0, 0.25)" : "",
    height: isDragging ? draggableStyle.height - 1 : "auto",
  });

  function onDragEnd(result: any) {
    if (!result.destination) {
      return;
    }

    reorderProductList(result.source.index, result.destination.index);
  }

  function reorderProductList(startIndex: number, endIndex: number) {
    const reOrder = reorderMass(list.items, startIndex, endIndex);

    dispatch(reorderList(reOrder, list?.product_list_id));
  }

  function listIsEdit() {
    if (list.owner.user_id === userId) {
      return UserPrivateVariantsEnum.EDIT;
    }

    if (
      list?.roles.find((role) => role.user.user_id === userId)?.role ===
      UserPrivateVariantsEnum.EDIT
    ) {
      return UserPrivateVariantsEnum.EDIT;
    }
    return UserPrivateVariantsEnum.VIEW;
  }

  function deleteItemHandler(list_item_id) {
    dispatch(deleteItem({ data: { list_item_id } }));
  }

  return (
    <DragDropContext onDragEnd={onDragEnd}>
      <Droppable droppableId="droppable">
        {(provided, snapshot) => (
          <div
            {...provided.draggableProps}
            {...provided.dragHandleProps}
            ref={provided.innerRef}
            data-rbd-draggable-context-id={list.product_list_id}
          >
            {list.items.length ? (
              list.items.map((listItem, index) => {

                if (listItem === undefined) {
                  console.log("UNDEFINED", list.items);
                }
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
                                      list={list}
                                      deleteItem={() =>
                                        deleteItemHandler(listItem.list_item_id)
                                      }
                                      index={index}
                                      drag={{ ...provided.dragHandleProps }}
                                      reorderProductList={reorderProductList}
                                      listId={list.product_list_id}
                                      listInfo={list}
                                      edit={edit}
                                      listItem={listItem}
                                    />
                                  );
                                case ListItemTypeEnum.IDEA:
                                  return (
                                    <ListProductIdeaItem
                                      list={list}
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
              <NoItemsBlock listInfo={list} />
            )}
            {provided.placeholder}
          </div>
        )}
      </Droppable>
    </DragDropContext>
  );
};
