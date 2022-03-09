import "@styles/globals.scss";
import { Provider } from "react-redux";
import clientStore, { getServerStore } from "@redux/stores/Store";
import App from "next/app";
import React from "react";
import getInitialState from "@services/axios/Account";
import MainComponent from "@modules/components/MainComponent";
import { SSRProvider } from "react-bootstrap";
import TagManager from "react-gtm-module";
import loadStripe from "../utils/loadStripe";
const stripePromise = loadStripe();
import { Elements } from "@stripe/react-stripe-js";

function MyApp({ Component, pageProps, state }) {
  if (state && state.config.site.account_enabled === false) {
    if (process.browser) {
      document.location.href = "/";
    }
    return null;
  }

  let store;

  if (process.browser === false) {
    store = getServerStore(state);
  } else {
    store = clientStore;

    const tagManagerArgs = {
      gtmId: "GTM-TCNTJMM",
    };

    TagManager.initialize(tagManagerArgs);
  }

  return (
    <Provider store={store}>
      <SSRProvider>
        <MainComponent>
          <Elements stripe={stripePromise}>
            <Component {...pageProps} />
          </Elements>
        </MainComponent>
      </SSRProvider>
    </Provider>
  );
}

MyApp.getInitialProps = async function (ctx) {
  const initialProps = App.getInitialProps(ctx);

  if (process.browser === false) {
    initialProps.state = await getInitialState(ctx.ctx.req);
    process.initialState = initialProps.state;
  }

  return { ...initialProps };
};

export default MyApp;
