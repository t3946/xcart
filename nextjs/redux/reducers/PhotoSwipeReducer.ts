import { AnyAction } from "redux";
import { photoswipeInitialValue } from "@modules/account/ts/consts/store-initial-value";
import { PhotoSwipeStore } from "@modules/account/ts/types/store.type";

const PhotoSwipeReducer = (
  store: PhotoSwipeStore = photoswipeInitialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "PHOTOSWIPE_SET_ITEMS":
      store.items = action.items;
      return { ...store };

    case "PHOTOSWIPE_SET_OPTION_INDEX":
      store.index = action.index;
      return { ...store };

    case "PHOTOSWIPE_SET_GALLERY":
      store.gallery = action.gallery;
      return { ...store };

    case "PHOTOSWIPE_SET_THUMB_INITIATOR":
      store.thumb = action.thumb;
      return { ...store };

    case "PHOTOSWIPE_SET_THUMBS_INITIATOR":
      store.thumbs = action.thumbs;
      return { ...store };

    case "PHOTOSWIPE_SET_OWNER_UUID":
      store.ownerId = action.uuid;
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

export default PhotoSwipeReducer;
