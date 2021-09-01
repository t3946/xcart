import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { setIsList } from "@client/jsx/redux/actions/account-actions/MainActions";
import { ListHeader } from "@client/modules/account/components/lists/ListHeader";
import { Button } from "@material-ui/core";
import { ListProductItems } from "@client/modules/account/components/lists/ListProductItems";
import { useParams } from "react-router-dom";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { Sceleton } from "@client/modules/shared/components/sceleton/Sceleton";
import { ListProductItemSkeleton } from "../components/lists/ListProductItemSkeleton";

export const ListsPage = () => {
  const { id }: { id: string } = useParams();

  let list;

  const lists = useSelector((e: AccountStore) => e.lists.lists);

  if (lists && id) {
    [list] = lists.filter((e) => e.product_list_id === id);
  } else if (lists && !id) {
    list = lists[0];
  }

  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(setIsList(true));

    return () => {
      dispatch(setIsList(false));
    };
  }, []);

  return (
    <div>
      {lists ? (
        <React.Fragment>
          <ListHeader listId={id} shippingList={!!id} label={list.name} />
          <ListProductItems info={list} />
        </React.Fragment>
      ) : (
        <React.Fragment>
          <div className="list-header-container">
            <Sceleton height={36} maxWidth={"100%"} />
          </div>

          {Array.from({ length: 3 }, (v, k) => k).map((e) => {
            return <ListProductItemSkeleton />;
          })}
        </React.Fragment>
      )}

      <Button
        onClick={null}
        type={"submit"}
        disabled={!lists}
        className="account-submit-btn account-submit-btn-outline add-idea-btn"
      >
        Add idea to list
      </Button>
    </div>
  );
};
