import axios, { AxiosRequestConfig } from "axios";

const getInitialState = async function (req: any) {
  let baseURL = `${process.env.PROTOCOL}://${req?.headers.host}`;

  if (process.env.NODE_ENV === "development") {
    baseURL = "http://nginx";
  }

  const params: AxiosRequestConfig = {
    baseURL,
  };

  if (req.headers.cookie) {
    params.headers = {
      Cookie: req.headers.cookie,
    };
  }

  const instance = axios.create(params);
  let initialState: any;

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
