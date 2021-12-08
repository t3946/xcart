import "@styles/globals.scss";
import "bootstrap/dist/css/bootstrap-grid.min.css";
import { Provider } from "react-redux";
import clientStore, { getServerStore } from "@redux/stores/Store";
import App from "next/app";
import getInitialState from "@services/axios/Account";

function MyApp({ Component, pageProps, state }) {
  let store;

  if (process.browser === false) {
    store = getServerStore(state);
  } else {
    store = clientStore;
  }

  return (
    <Provider store={store}>
      <Component {...pageProps} />
    </Provider>
  );
}

MyApp.getInitialProps = async function (ctx) {
  const initialProps = App.getInitialProps(ctx);

  if (process.browser === false) {
    console.log("MyApp.getInitialProps");
    initialProps.state = await getInitialState();
    process.initialState = initialProps.state;
  }

  return { ...initialProps };
};

export default MyApp;
