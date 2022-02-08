import axios from "axios";

const getInitialState = async function (req: any) {
  let initialState: any;

  const instance = axios.create({
    baseURL: process.env.BASE_URL_NGINX,
    headers: {
      Cookie: req.headers.cookie,
    },
  });

  await instance.get("/api/account/get-initial-data").then((res) => {
    initialState = res.data;
  });

  await instance
    .get("/api-client/user/info")
    .then((res) => {
      initialState.user = res.data;
    })
    .catch(() => {
      initialState.user = null;
    });

  await instance
    .get("/cart/get/products", {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
    .then((res) => {
      initialState.cart = res.data;
    });

  return initialState;
};

export default getInitialState;
