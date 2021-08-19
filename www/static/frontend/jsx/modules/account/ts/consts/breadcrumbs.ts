const newWindow: any = window;
const routes = newWindow.appData.routes || {};

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

  {
    name: "Public Profile",
    path: routes["account:public-profile"],
  },

  {
    name: "Login & security",
    path: routes["account:login-and-security"],
  },

  {
    name: "Change your name",
    path: routes["account:edit-name"],
  },

  {
    name: "Change your email address",
    path: routes["account:edit-email"],
  },

  {
    name: "Change mobile phone number",
    path: routes["account:edit-phone"],
  },

  {
    name: "Change password",
    path: routes["account:edit-password"],
  },
];
