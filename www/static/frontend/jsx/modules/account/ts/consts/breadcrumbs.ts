const newWindow: any = window;
const routes = newWindow.appData.routes || {};

export const staticRoutes = [
  {
    name: "Add",
    path: routes["account:addresses-add"],
  },
  {
    name: "Edit",
    path: routes["account:addresses-edit"],
  },
  {
    name: "Shipping lists",
    path: routes["account:your-lists"],
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

  {
    name: "Two-Step Verification (2SV) Settings",
    path: routes["account:two-step-verification-settings"],
  },

  {
    name: "Disable",
    path: routes["account:two-step-verification-settings-disable"],
  },

  {
    name: "Add New App",
    path: routes["account:two-step-verification-add-new"],
  },

  {
    name: "Change Preferred Method",
    path: routes["account:two-step-verification-settings-preferred-method"],
  },

  {
    name: "Password Assistance",
    path: routes["account:two-step-verification-recovery-password-assistance"],
  },

  {
    name: "Orders",
    path: routes["account:orders"],
  },

  {
    name: "Open orders",
    path: routes["account:open-orders"],
  },

  {
    name: "Decisions required",
    path: routes["account:order-decisions-required"],
  },
];
