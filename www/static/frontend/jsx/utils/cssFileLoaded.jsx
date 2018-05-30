export default function cssFileLoaded(filename, callback)
{
    if (window.app.assets.css[filename].loaded) {
        callback();
    }
    else {
        document.addEventListener('cssLoad', () => {
            if (window.app.assets.css[filename].loaded) {
                callback();
            }
        }, {passive: true});
    }
}