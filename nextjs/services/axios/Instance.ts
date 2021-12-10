import axios from "axios";

//get axios SERVER(works only on server) query instance for requests to API-Sever
export const getInstance = function (req: Record<any, any>) {
  let xidCookieMatches;

  if (req.headers.cookie) {
    xidCookieMatches = req.headers.cookie.match(/xid\d+=.+?;/);
  }

  let cookie = "";

  if (xidCookieMatches) {
    cookie = xidCookieMatches[0];
  }

  return axios.create({
    baseURL: "http://nginx",
    headers: {
      Cookie: cookie,
    },
  });
};
