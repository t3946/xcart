import "@styles/globals.scss";
import "bootstrap/dist/css/bootstrap-grid.min.css";
import App from "next/app";
import getInitialState from "../services/axios/Account";
import { Provider } from "react-redux";
import getStore from "@redux/stores/Store";

function MyApp({ Component, pageProps }) {
  const store = getStore(pageProps.initialState);

  return (
    <Provider store={store}>
      <Component {...pageProps} />
    </Provider>
  );
}

MyApp.getInitialProps = async (appContext) => {
  const appProps = await App.getInitialProps(appContext);

  appProps.pageProps.initialState = await getInitialState();

  return { ...appProps };
};

export default MyApp;
