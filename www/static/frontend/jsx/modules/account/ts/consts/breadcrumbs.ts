const newWindow: any = window;
const routes = newWindow.appData.routes;

export const staticRoutes = [
  {
    name: "Add",
    path: routes["account:addresses-add"],
  },

  { name: "Account", path: routes["account:index"] },

  {
    name: "Addresses",
    path: routes["account:addresses"],
  },

  {
    name: "Wallet",
    path: routes["account:wallet"],
  },
];
