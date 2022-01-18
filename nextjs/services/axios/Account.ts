import axios from "axios";

const getInitialState = async function (req: any) {
  let initialState: any;
  let sessionCookieMatches;

  if (req.headers.cookie) {
    sessionCookieMatches = req.headers.cookie.match(/session=[^;]+/);
  }

  let cookie = "";

  if (sessionCookieMatches) {
    cookie = sessionCookieMatches[0];
  }

  const instance = axios.create({
    baseURL: process.env.BASE_URL_NGINX,
    headers: {
      Cookie: cookie,
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

  return initialState;
};

export default getInitialState;
