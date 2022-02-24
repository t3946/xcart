const axios = require("axios");
const app = require("express")();
const AxiosInstance = axios.create({
  baseURL: process.env.BASE_URL_NGINX,
});

app.post("/get", async function (req, res) {
  await AxiosInstance.post(`/api/goods/get/${req.body.productId}/`).then(
    (apiRes) => {
      res.json(apiRes.data);
      res.send();
    }
  );
});

module.exports = app;
