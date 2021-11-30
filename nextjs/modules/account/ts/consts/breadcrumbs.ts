import { route } from "@utils/AppData";

export const staticRoutes = [
  {
    name: "Add",
    path: route("account:addresses-add"),
  },
  {
    name: "Edit",
    path: route("account:addresses-edit"),
  },
  {
    name: "Shipping lists",
    path: route("account:your-lists"),
  },

  { name: "Account", path: route("account:index") },

  {
    name: "Addresses",
    path: route("account:addresses"),
  },

  {
    name: "Wallet",
    path: route("account:wallet"),
  },

  {
    name: "Public Profile",
    path: route("account:public-profile"),
  },

  {
    name: "Login & security",
    path: route("account:login-and-security"),
  },

  {
    name: "Change your name",
    path: route("account:edit-name"),
  },

  {
    name: "Change your email address",
    path: route("account:edit-email"),
  },

  {
    name: "Change mobile phone number",
    path: route("account:edit-phone"),
  },

  {
    name: "Change password",
    path: route("account:edit-password"),
  },

  {
    name: "Two-Step Verification (2SV) Settings",
    path: route("account:two-step-verification-settings"),
  },

  {
    name: "Disable",
    path: route("account:two-step-verification-settings-disable"),
  },

  {
    name: "Add New App",
    path: route("account:two-step-verification-add-new"),
  },

  {
    name: "Change Preferred Method",
    path: route("account:two-step-verification-settings-preferred-method"),
  },

  {
    name: "Password Assistance",
    path: route("account:two-step-verification-recovery-password-assistance"),
  },

  {
    name: "Orders",
    path: route("account:orders"),
  },

  {
    name: "Open orders",
    path: route("account:open-orders"),
  },

  {
    name: "Decisions required",
    path: route("account:order-decisions-required"),
  },
];
