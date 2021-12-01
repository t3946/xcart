export default function Image({ images }) {
  if (images.length > 0) {
    const image = images[0];

    return (
      <img src={image.url} alt={image.alt} className="products-slider-image" />
    );
  } else {
    return (
      <div className="products-slider-no-image product-slider-no-image__mini">
        <span>Image not available</span>
      </div>
    );
  }
}
