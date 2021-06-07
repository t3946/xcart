import React from "react";
import { viewPaginateInfo } from "@s3stores-mail/utils";
import { useDispatch, useSelector } from "react-redux";
import { pageSize } from "@s3stores-mail/ts/consts";
import { useHistory, useParams } from "react-router-dom";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailListHeader } from "@s3stores-mail/components/smart/email-list-header/EmailListHeader";
import { EmailListHeaderContext } from "@s3stores-mail/contexts/email-list-header-context/EmailListHeader.context";
import { editFavorites, setViewed } from "@redux/actions";
import {
  isFavoriteItemsTrue,
  isViewedItemsTrue,
} from "@s3stores-mail/utils/edit-fields-on-email";

export const EmailListHeaderContainer: React.FC = () => {
  const history = useHistory();

  const dispatch = useDispatch();

  const { page }: { page?: string } = useParams();

  const thisPage = Number(page);

  const itemsCount: number = useSelector((state: StoreDto) => state.itemsCount);

  const maxPage = Math.ceil(itemsCount / pageSize);

  const checkedItems = useSelector((state: StoreDto) => state.checkedItems);

  const items = useSelector((state: StoreDto) => state.items);

  const getNewPage = (count: number) => {
    history.push(`/admin/forms/email-dashboard/page/${thisPage + count}`);
  };

  const paginate = () => {
    return viewPaginateInfo(thisPage, itemsCount, maxPage, pageSize);
  };
  const editFavorite = () => {
    dispatch(
      editFavorites(checkedItems, isFavoriteItemsTrue(items, checkedItems))
    );
  };

  const editViewed = () => {
    dispatch(setViewed(checkedItems, isViewedItemsTrue(items, checkedItems)));
  };

  const moreFavorites = useSelector((state: StoreDto) => state.moreFavorites);

  const moreViewed = useSelector((state: StoreDto) => state.moreViewed);

  return (
    <EmailListHeaderContext.Provider
      value={{ editFavorite, editViewed, moreFavorites, moreViewed }}
    >
      <EmailListHeader
        paginate={paginate}
        maxPage={maxPage}
        page={thisPage}
        getNewPage={getNewPage}
      />
    </EmailListHeaderContext.Provider>
  );
};
