import React, { useEffect, useState } from "react";
import { NoItemsBlock } from "@client/modules/account/components/lists/NoItemsBlock";
import { ListProductItem } from "@client/modules/account/components/lists/ListProductItem";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import { useDispatch } from "react-redux";
import { reorderList } from "../../../../redux/actions/account-actions/ListsActions";
import { reorderMass } from "@client/modules/account/utils/reorder-mass";

export const ListProductItems = ({ info }) => {
  const getItemStyle = (isDragging, draggableStyle) => ({
    ...draggableStyle,
    boxShadow: isDragging ? "0px 4px 5px 0px rgba(0, 0, 0, 0.25)" : "",
    height: draggableStyle.height - 1,
  });

  const dispatch = useDispatch();

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
              info.products.map((e, index) => {
                return (
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
                        <ListProductItem
                          index={index}
                          drag={{ ...provided.dragHandleProps }}
                          info={e}
                          reorderProductList={reorderProductList}
                          listId={info.product_list_id}
                        />
                      </div>
                    )}
                  </Draggable>
                );
              })
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
