import React from "react";
import uuid from "react-uuid";
import { Swiper } from "swiper/react";
import SliderItems from "@client/jsx/components/product/ImagesSlider/SliderItems";
import SliderThumbs from "@client/jsx/components/product/ImagesSlider/SliderThumbs";

interface PropsInterface {
  items: [];
}

const Slider: React.FC<PropsInterface> = function (props: PropsInterface) {
  const [componentId] = React.useState(uuid());
  const [isVideo, setIsVideo] = React.useState(false);
  const [index, setIndex] = React.useState(0);
  const { items } = props;
  const [productImagesSlider, setProductImagesSlider] = React.useState(null);

  /**
   * обновляет индекс, нужно для синхронизации слайдера с картинками и слайдера с тумбами
   */
  function slideTo(index: number): void {
    setIndex(index);

    if (productImagesSlider && index !== productImagesSlider.realIndex) {
      productImagesSlider.slideToLoop(index);
      setIsVideo(false);
    }
  }

  return (
    <div className="images-slider">
      <SliderThumbs items={items} index={index} slideTo={slideTo} />

      <Swiper
        spaceBetween={50}
        style={{
          marginBottom: 10,
        }}
        longSwipesRatio={0.05}
        slidesPerView={1}
        loop={true}
        effect={"coverflow"}
        className={"product-images-slider swiper-container"}
        onSwiper={setProductImagesSlider}
        onSlideChange={(swiper) => slideTo(swiper.realIndex)}
      >
        {SliderItems({
          items,
          isVideo,
        })}
      </Swiper>
    </div>
  );
};

export default Slider;
