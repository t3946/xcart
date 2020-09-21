<script src="/static/frontend/lex/lex-web-ui-loader.js"></script>
{ignore}
    <script>
        var loaderOptions = {
            baseUrl: '/static/frontend/lex/',
            iframeSrcPath: '/static/frontend/lex/index.html#/?lexWebUiEmbed=true'
        };
        var iframeLoader = new ChatBotUiLoader.IframeLoader(loaderOptions);
        var chatbotUiConfig = {
            ui: {
                parentOrigin: window.location.origin,
            },
            iframe: {
                iframeOrigin: window.location.origin,
                shouldLoadIframeMinimized: true,
                iframeSrcPath: "/static/frontend/lex/index.html#/?lexWebUiEmbed=true"
            },
            cognito: {
                poolId: 'us-west-2:7fd52568-2f7d-4518-8e03-0d4bcb866326'
            },
            lex: {
                botName: 'Fast_Freddie',
                botAlias: 'Fast_Freddie',
                initialText: "Hello, this is Fast Freddie, how can I assist you today?",
                initialSpeechInstruction: "Say 'Freddie' to get started.",
                reInitSessionAttributesOnRestart: false,
                region: 'us-west-2'
            },
        };

        iframeLoader.load(chatbotUiConfig)
            .then(function () {
                console.log('iframe loaded');
                setTimeout(()=>{
                    document.getElementById('lex-web-ui-iframe').style.display = "flex";
                }, 1000);
            })
            .catch(function (err) {
                console.error(err);
            });

    </script>
{/ignore}