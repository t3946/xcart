const app = require("express")();
const isAuthMiddleware = require("../middleware/isAuth");
const axios = require("axios");

app.get("/get/:type/:date", isAuthMiddleware, async (req, res) => {
  console.log("HI!");
  const { type, date } = req.params;
  const userId = req.user.userId;
  const orders = await axios
    .get(process.env.BASE_URL_NGINX + `/api/account/orders/get/${userId}/${type}/${date}`)
    .then((response) => response.data);
  console.log(orders);
  res.json(orders);
});

module.exports = app;
