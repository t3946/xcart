import { AnyAction } from "redux";
import { photoswipeInitialValue } from "@client/jsx/modules/account/ts/consts/store-initial-value";
import { PhotoSwipeStore } from "@client/modules/account/ts/types/store.type";

const ReviewsReducer = (
  store: PhotoSwipeStore = photoswipeInitialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "PHOTOSWIPE_SET_ITEMS":
      store.items = action.items;
      return { ...store };

    case "PHOTOSWIPE_SET_GALLERY":
      store.gallery = action.gallery;
      return { ...store };

    case "PHOTOSWIPE_CLEAR":
      return {
        items: null,
        gallery: null,
      };

    default:
      return store;
  }
};

export default ReviewsReducer;
