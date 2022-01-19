import axios from "axios";

//get axios SERVER(works only on server) query instance for requests to API-Sever
export const getInstance = function (req: Record<any, any>) {
  let sessionCookieMatches;

  if (req.headers.cookie) {
    sessionCookieMatches = req.headers.cookie.match(/session+=[^;]*/);
  }

  let cookie = "";

  if (sessionCookieMatches) {
    cookie = sessionCookieMatches[0];
  }

  return axios.create({
    baseURL: process.env.BASE_URL_NGINX,
    headers: {
      Cookie: cookie,
    },
  });
};
