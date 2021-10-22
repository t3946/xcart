import PhotoSwipe from "@client/libs/photoswipe/dist/photoswipe";

export const photoSwipeSetItemsAction = (items: Record<any, any>[]): any => ({
  type: "PHOTOSWIPE_SET_ITEMS",
  items,
});

export const photoSwipeSetOptionIndexAction = (index: number): any => ({
  type: "PHOTOSWIPE_SET_OPTION_INDEX",
  index,
});

export const photoSwipeInitAction = (gallery: PhotoSwipe): any => ({
  type: "PHOTOSWIPE_SET_GALLERY",
  gallery,
});

export const photoSwipeSetThumbsInitiatorAction = (
  thumbs: NodeListOf<ChildNode> | HTMLElement
): any => ({
  type: "PHOTOSWIPE_SET_THUMBS_INITIATOR",
  thumbs,
});

export const photoSwipeSetOwnerIdAction = (uuid: string): any => ({
  type: "PHOTOSWIPE_SET_OWNER_UUID",
  uuid,
});

export const photoSwipeClearAction = (): any => ({
  type: "PHOTOSWIPE_CLEAR",
});
