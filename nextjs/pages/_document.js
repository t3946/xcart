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
    const favicon =
      "/" + this.props.head.initialState.config.site.file_edit_image_favicon;

    function createMarkup() {
      return { __html: `window.__PRELOADED_STATE__ = ${json};` };
    }

    return (
      <Html>
        <Head>
          <script dangerouslySetInnerHTML={createMarkup()} />
          <script
            src="https://www.google.com/recaptcha/api.js?hl=en"
            async
            defer
          />

          <link rel="shortcut icon" href={favicon} type="image/x-icon" />
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
