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

  return axios.create({
    baseURL: process.env.BASE_URL_NGINX,
    headers: {
      Cookie: cookie,
    },
  });
};
