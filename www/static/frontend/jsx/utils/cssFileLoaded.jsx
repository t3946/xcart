export default function cssFileLoaded(filename, callback)
{
    if (window.app.assets.css[filename].loaded && document.font) {
        callback();
        //console.log('now');
    }
    else {

        let css = window.app.assets.css[filename].loaded;
        let font = document.font;

        let execute = () => {
            if(css && font){

                document.removeEventListener('cssLoad', checkCss);
                document.removeEventListener('font.loaded', checkFont);
                callback();
                //console.log('loaded');
            }
        };

        let checkCss = () => {
            if (window.app.assets.css[filename].loaded) {
                css = true;
                execute();
            }
        };

        let checkFont = () => {
            if (document.font) {
                font = true;
                execute();
            }
        };

        document.addEventListener('cssLoad', checkCss, {passive: true});
        document.addEventListener('font.loaded', checkFont, {passive: true});
    }
}