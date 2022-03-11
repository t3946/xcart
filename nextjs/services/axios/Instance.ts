import axios from "axios";
import { IncomingMessage } from "http";

//get axios SERVER(works only on server) query instance for requests to API-Sever
export const getInstance = function (req?: IncomingMessage) {
  let sessionCookieMatches;

  if (req && req.headers.cookie) {
    sessionCookieMatches = req.headers.cookie.match(/session+=[^;]*/);
  }

  let cookie = "";

  if (sessionCookieMatches) {
    cookie = sessionCookieMatches[0];
  }

  let baseURL = "https://" + req?.headers.host;

  if (process.env.NODE_ENV === "development") {
    baseURL = "http://nginx";
  }

  return axios.create({
    baseURL,
    headers: {
      Cookie: cookie,
    },
  });
};
