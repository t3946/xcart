const axios = require("axios");
const app = require("express")();

app.post("/get", async function (req, res) {
  await axios
    .post(`http://nginx/api/goods/get/${req.body.productId}/`)
    .then((apiRes) => {
      res.json(apiRes.data);
      res.send();
    });
});

module.exports = app;
