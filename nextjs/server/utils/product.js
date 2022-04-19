const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

const CurrencyHelper = {
  convertToCurrency(currency, price) {
    // подробнее о реализации можно узнать
    // в /home/falcon/xcart/app/Modules/Main/Helpers/CurrencyHelper.php
    return price;
  },

  convert(currency, price) {
    // подробнее о реализации можно узнать
    // в /home/falcon/xcart/app/Modules/Main/Helpers/CurrencyHelper.php
    return price;
  },
};

function getPrices(product) {
  const prices = [];
  const currency = "USD";

  for (const pricing of product.pricings) {
    const { quantity } = pricing;
    const price = parseFloat(pricing.price);
    const new_map_price = parseFloat(product.new_map_price);
    const priceValue = CurrencyHelper.convert(
      currency,
      Math.max(price, new_map_price)
    );

    //miss duplicate pricing
    if (prices.find((elem) => elem.quantity === quantity)) {
      continue;
    }

    prices.push({ price: priceValue, quantity });
  }

  return prices;
}

function getPrice(product, quantity = 1) {
  const prices = getPrices(product);
  let price = 0;

  for (const priceQuantity of prices) {
    if (quantity >= parseInt(priceQuantity.quantity)) {
      price = parseFloat(priceQuantity.price);
    } else {
      break;
    }
  }

  return price;
}

async function getImages(product) {
  let images;

  if (product.is_group_root === 1) {
    images = await prisma.xcart_product_images.findMany({
      where: {
        xcart_products_images: {
          some: {
            AND: [
              { product_id: product.productid },
              {
                xcart_products: {
                  group_root: product.productid,
                  forsale: "Y",
                },
              },
              { is_active: 1 },
            ],
          },
        },
      },
    });
  } else {
    images = await prisma.xcart_product_images.findMany({
      where: {
        xcart_products_images: {
          some: {
            AND: [{ product_id: product.productid }, { is_active: 1 }],
          },
        },
      },
    });
  }

  return images;
}

module.exports = {
  getPrice,
  //get product prices and images if available
  async normalize(product) {
    //get prices
    if (typeof product.pricings !== "undefined") {
      product.price = getPrice(product);
      delete product.pricings;
    }

    //get images
    if (
      typeof product.productid !== "undefined" &&
      typeof product.is_group_root !== "undefined"
    ) {
      product.images = await getImages(product);
    }
  },
};
