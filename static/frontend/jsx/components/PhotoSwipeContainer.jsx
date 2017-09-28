import {h, render} from "preact";
import PhotoSwipe from 'photoswipe/dist/photoswipe'
import PhotoSwipeUI_Default from 'photoswipe/dist/photoswipe-ui-default'

const cont = new class PhotoSwipeContainer
{
    constructor()
    {
        this.container = null;
        this.pswp = null;
        this.images = [ ];
        this.options = {
            index: 0,
            history: false,
            bgOpacity: 0.85,
            showHideOpacity: true
        };
    }

    init()
    {
        this.render();
        this.pswp = new PhotoSwipe(this.container, PhotoSwipeUI_Default, this.images, this.options);


        this.pswp.listen('close', () => {
            let item = this.pswp.currItem;
            if (item.onBlur) {
                item.onBlur(item, this.pswp);
            }

            this.options.index = 0;
            this.images = [];
            this.pswp = null;

            // this.container.remove();
            // this.container = null;
        });

        this.pswp.listen('beforeChange', (d) => {
            if (!!d) {
                let pswp = this.pswp;
                let cIndex = pswp.getCurrentIndex();
                let maxPos = pswp.items.length -1;
                let pIndex = null;

                if (d === -1 && cIndex === maxPos) {
                    pIndex = 0;
                }
                else if (d === 1 && cIndex === maxPos) {
                    pIndex = maxPos -1;
                }
                else if (d === 1 && cIndex === 0) {
                    pIndex = maxPos;
                }
                else if (d === -1) {
                    pIndex = cIndex +1;
                }
                else if (d === 1) {
                    pIndex = cIndex -1;
                }

                let prevItem = pswp.items[pIndex];

                if (prevItem.onBlur) {
                    prevItem.onBlur(prevItem, this.pswp);
                }
            }
        });
        this.pswp.listen('afterChange', () => {
            let item = this.pswp.currItem;
            if (item.onShow) {
                item.onShow(item, this.pswp);
            }
        });

        this.pswp.listen('gettingData', (index, item)  =>{

            if (item.src) {
                if (item.w < 1 || item.h < 1) { // unknown size
                    let img = new Image();

                    img.onload = () => { // will get size after load
                        item.w = img.width; // set image width
                        item.h = img.height; // set image height

                        this.pswp.invalidateCurrItems(); // reinit Items
                        this.pswp.updateSize(true); // reinit Items
                    };

                    img.src = item.src; // let's download image
                }
            }
        });

        // this.pswp.listen('imageLoadComplete', (index, item) => {
        //     let linkEl = item.el.children[0];
        //     let img = item.container.children[0];
        //
        //     if (!linkEl.getAttribute('data-size')) {
        //         linkEl.setAttribute('data-size', img.naturalWidth + 'x' + img.naturalHeight);
        //         item.w = img.naturalWidth;
        //         item.h = img.naturalHeight;
        //
        //         this.pswp.invalidateCurrItems();
        //         this.pswp.updateSize(true);
        //     }
        // });

        this.pswp.init();

        this.pswp.framework.bind( this.pswp.scrollWrap, 'pswpTap',
        (e) => {
            let item = this.pswp.currItem;
            if (item.onTap) {
                item.onTap(item, this.pswp);
            }
        });
    }


    getPhotoSwipe ()
    {
        return this.pswp;
    }

    setImages(images)
    {
        this.images = images;
    }


    render()
    {
        if (!this.container) {
            this.container = render(
                <div className="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                    <div className="pswp__bg"></div>
                    <div className="pswp__scroll-wrap">

                        <div className="pswp__container">
                            <div className="pswp__item"></div>
                            <div className="pswp__item"></div>
                            <div className="pswp__item"></div>
                        </div>

                        <div className="pswp__ui pswp__ui--hidden">
                            <div className="pswp__top-bar">
                                <div className="pswp__counter"></div>
                                <button className="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                <button className="pswp__button pswp__button--share" title="Share"></button>
                                <button className="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                <button className="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                                <div className="pswp__preloader">
                                    <div className="pswp__preloader__icn">
                                      <div className="pswp__preloader__cut">
                                        <div className="pswp__preloader__donut"></div>
                                      </div>
                                    </div>
                                </div>
                            </div>

                            <div className="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                <div className="pswp__share-tooltip"></div>
                            </div>
                            <button className="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                            <button className="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                            <div className="pswp__caption">
                                <div className="pswp__caption__center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                ,
                document.body
            );
        }
    }
};


export default cont;