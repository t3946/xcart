BigInt.prototype.toJSON = function () {
  return parseInt(this.toString());
};

const express = require("express");
const next = require("next");
const bodyParser = require("body-parser");
const dev = process.env.NODE_ENV !== "production";
const mainApp = express();
const apiApp = express();
const UserApi = require("./server/api/user/User");
const OrdersApi = require("./server/api/Orders");
const ProductApi = require("./server/api/Product");
const ReviewsApi = require("./server/api/Reviews");
const cookieParser = require("cookie-parser");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

function listen() {
  const port = process.env.PORT;

  mainApp.listen(port, (err) => {
    if (err) throw err;
    console.log(`> Ready on http://localhost:${port}`);
  });
}

async function getStorefrontMiddleware(req, res, done) {
  const storefrontDomain =
    "www." + req.get("host").split(".").slice(-2).join(".");

  req.storefront = await prisma.xcart_storefronts.findMany({
    where: {
      domain: storefrontDomain,
    },
  });

  done();
}

mainApp.use(bodyParser.json());
mainApp.use(bodyParser.urlencoded({ extended: true }));
mainApp.use(cookieParser());

apiApp.use("/user", UserApi);
apiApp.use("/orders", OrdersApi);
apiApp.use("/product", ProductApi);
apiApp.use("/review", ReviewsApi);
mainApp.use("/api-client", getStorefrontMiddleware, apiApp);

if (process.env.RUN_BACKEND_ONLY === "true") {
  listen();
} else {
  const nextApp = next({ dev });
  const handle = nextApp.getRequestHandler();

  nextApp
    .prepare()
    .then(() => {
      mainApp.get("*", (req, res) => {
        return handle(req, res);
      });

      listen();
    })
    .catch((ex) => {
      console.error(ex.stack);
      process.exit(1);
    });
}
