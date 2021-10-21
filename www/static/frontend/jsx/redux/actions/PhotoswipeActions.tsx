import PhotoSwipe from "@client/libs/photoswipe/dist/photoswipe";

export const photoSwipeSetItemsAction = (items: Record<any, any>[]): any => ({
  type: "PHOTOSWIPE_SET_ITEMS",
  items,
});

export const photoSwipeInitAction = (gallery: PhotoSwipe): any => ({
  type: "PHOTOSWIPE_SET_GALLERY",
  gallery,
});

export const photoSwipeClearAction = (): any => ({
  type: "PHOTOSWIPE_CLEAR",
});
