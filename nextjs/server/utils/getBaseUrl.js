function getBaseUrl(req) {
  let baseURL;

  switch (process.env.API_URL) {
    case "dynamic":
      baseURL = `${process.env.PROTOCOL}://${req?.headers.host}`;
      break;
    case "static":
      baseURL = "http://nginx";
      break;
  }

  return baseURL;
}

module.exports = getBaseUrl;
