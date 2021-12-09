import axios from "axios";

const getInitialState = async function (req: any) {
  let initialState: any;

  const matches = req.headers.cookie.match(/xid\d+=.+?;/);

  let cookie = "";

  if (matches) {
    cookie = matches[0];
  }

  const instance = axios.create({
    baseURL: "http://nginx",
    headers: {
      Cookie: cookie,
    },
  });

  await instance.get("/api/account/get-initial-data").then((res) => {
    initialState = res.data;
  });

  return initialState;
};

export default getInitialState;
