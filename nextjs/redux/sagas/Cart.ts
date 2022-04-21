import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import axios from "axios";

function* add(action: Record<any, any>): Generator {
  const { data, callback } = action.payload;
  let newCart;

  yield axios.post("/cart/add/one-product", data).then((res) => {
    newCart = res.data;
  });

  yield put({
    type: "CART_SET",
    payload: { cart: newCart },
  });

  callback && callback();
}

function* setQuantity(action: Record<any, any>): Generator {
  const { data, success } = action.payload;

  yield axios
    .post("/cart/set-quantity", data, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
    .then(success);
}

function* del(action: Record<any, any>): Generator {
  const { data, success } = action.payload;

  yield axios
    .post("/cart/del/products", data, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
    .then(success);
}

function* get(action: Record<any, any>): Generator {
  const { success } = action.payload;

  yield axios
    .get("/cart/get/products", {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
    .then(success);
}

export default function* reviewsActionWatcher(): SagaIterator {
  yield takeLatest("CART_ADD", add);
  yield takeLatest("CART_SET_QUANTITY", setQuantity);
  yield takeLatest("CART_DEL", del);
  yield takeLatest("CART_GET", get);
}
