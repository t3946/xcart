import Document, { Head, Html, Main, NextScript } from "next/document";

class MyDocument extends Document {
  static async getInitialProps(ctx) {
    process.next = {
      url: ctx.req.url,
    };

    const initialProps = await Document.getInitialProps(ctx);

    initialProps.head.initialState = process.initialState;

    return initialProps;
  }

  render() {
    const json = JSON.stringify(this.props.head.initialState);

    function createMarkup() {
      return { __html: `window.__PRELOADED_STATE__ = ${json};` };
    }

    return (
      <Html>
        <Head>
          <script dangerouslySetInnerHTML={createMarkup()} />
        </Head>
        <body>
          <Main />
          <NextScript />
        </body>
      </Html>
    );
  }
}

export default MyDocument;
