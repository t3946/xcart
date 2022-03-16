import VideoProvider from "@client/modules/product/Components/PhotoSwipe/ts/enum/VideoProvider";
import {
  VIDEO_WIDTH,
  VIDEO_HEIGHT,
} from "@client/modules/product/Components/PhotoSwipe/ts/const/VideoSize";

//can't be a jsx component because photo swipe viewer do not support react
const Video = function (video: Record<any, any>): string {
  function videoTemplate() {
    switch (video.provider) {
      case VideoProvider.Youtube:
      case VideoProvider.Vimeo:
        return `
          <iframe
            class="pswp__video"
            width=${VIDEO_WIDTH}
            height=${VIDEO_HEIGHT}
            src=${video.video}
            allowFullScreen
            allow="autoplay"
          />`;

      case VideoProvider.Local:
        return `
        <video width=${VIDEO_WIDTH} height=${VIDEO_HEIGHT} controls="controls" poster="video/duel.jpg" autoplay="autoplay">
          <source src="${video.video}" type='video/ogg; codecs="theora, vorbis"'>
          <source src="${video.video}" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
          <source src="${video.video}" type='video/webm; codecs="vp8, vorbis"'>
          Your browse do not support this video.
        </video>`;
    }
  }

  return `<div class="wrapper">
      <div class="video-wrapper">
      ${videoTemplate()}
      </div>
    </div>`;
};

export default Video;
