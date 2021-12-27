const express = require("express");
const next = require("next");
const bodyParser = require("body-parser");
const dev = process.env.NODE_ENV !== "production";
const mainApp = express();
const apiApp = express();
const UserApi = require("./server/api/User");
//set true if you need debug server only
const cookieParser = require("cookie-parser");

function listen() {
  const port = process.env.PORT;

  mainApp.listen(port, (err) => {
    if (err) throw err;
    console.log(`> Ready on http://localhost:${port}`);
  });
}

mainApp.use(bodyParser.json());
mainApp.use(bodyParser.urlencoded({ extended: true }));
mainApp.use(cookieParser());

apiApp.use("/user", UserApi);
mainApp.use("/api-client", apiApp);

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
