import "@styles/globals.scss";
import { Provider } from "react-redux";
import clientStore, { getServerStore } from "@redux/stores/Store";
import App from "next/app";
import React from "react";
import getInitialState from "@services/axios/Account";
import MainComponent from "@modules/components/MainComponent";
import { SSRProvider } from "react-bootstrap";

function MyApp({ Component, pageProps, state }) {
  let store;

  if (process.browser === false) {
    store = getServerStore(state);
  } else {
    store = clientStore;
  }

  return (
    <Provider store={store}>
      <SSRProvider>
        <MainComponent>
          <Component {...pageProps} />
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
