function getBaseUrl(req) {
  let baseURL = `https://${req.headers.host}`;

  if (process.env.NODE_ENV === "development") {
    baseURL = "http://nginx";
  }

  return baseURL;
}

module.exports = getBaseUrl;
