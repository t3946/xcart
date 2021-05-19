import storeApp from "../stores/StoreApp";

const setType = {
  type: "SET",
};

const setMedia = {
  type: "SET_MEDIA",
};

const setMobileSearch = {
  type: "SET_MOBILE_SEARCH",
};

const setCheckOff = {
  type: "CHECK_OFF",
};

// const setTtypeMobile = {
//     type: 'SET_MOBILE',
// };

export function hideAll() {
  storeApp.dispatch({
    ...setType,
    data: {
      frontend: {
        darkness: false,
        header: {
          active: null,
          mobileSearch: false,
        },
      },
    },
  });
}

export function checkOff() {
  storeApp.dispatch({
    ...setCheckOff,
    data: {
      frontend: {
        darkness: false,
        header: {
          active: null,
        },
      },
    },
  });
}

export function action(action) {
  storeApp.dispatch({
    ...setType,
    data: {
      frontend: {
        darkness: action !== null,
        header: {
          active: action,
        },
      },
    },
  });
}

export function actionMobileSearch(checked) {
  storeApp.dispatch({
    ...setMobileSearch,
    data: {
      frontend: {
        header: {
          mobileSearch: checked,
        },
      },
    },
  });
}

export function actionMedia(media) {
  storeApp.dispatch({
    ...setMedia,
    data: {
      frontend: {
        media: media,
      },
    },
  });
}
