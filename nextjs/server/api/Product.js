const axios = require("axios");
const app = require("express")();
const AxiosInstance = axios.create();
const getBaseUrl = require("../utils/getBaseUrl");

app.post("/get", async function (req, res) {
  await AxiosInstance.post(
    getBaseUrl(req) + `/api/goods/get/${req.body.productId}/`
  ).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

module.exports = app;
