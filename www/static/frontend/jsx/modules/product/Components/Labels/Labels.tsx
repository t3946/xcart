import React from "react";
import AppData from "@client/jsx/utils/AppData";
import Label, {
  EType,
  IProps as ILabelProps,
} from "@client/jsx/modules/product/Components/Labels/Label";
import Styles from "@client/jsx/modules/product/Components/Labels/Labels.module.scss";

function formatDate(time: number): string {
  const date = new Date(time);
  const day = date.getDate();
  const month = date.toLocaleDateString("en-US", { month: "short" });
  const year = date.getFullYear();

  return [day, month, year].join(" ");
}

/**
 * parse product info and get list of product labels
 */
function getLabelsData(): ILabelProps[] {
  const { product, distributor, brand, flags } = AppData.product_info;
  const fill = true;
  const labelListData: ILabelProps[] = [];

  if (flags.isGroupRoot) {
    return;
  }

  if (flags.isOutOfStockFrontend) {
    if (fill) {
      if (
        product.eta_date_mm_dd_yyyy !== 0 &&
        product.eta_date_mm_dd_yyyy > AppData.server.time
      ) {
        const date = formatDate(product.eta_date_mm_dd_yyyy * 1000);
        const text = `Expected availability: ${date}`;

        labelListData.push({
          text: text,
          type: EType.outOfStock,
        });
      } else {
        const text = "Out of stock";

        labelListData.push({
          text: text,
          type: EType.outOfStock,
        });
      }
    } else {
      const text = "Out of stock";

      labelListData.push({
        text: text,
        type: EType.outOfStock,
      });

      if (
        product.eta_date_mm_dd_yyyy !== 0 &&
        product.eta_date_mm_dd_yyyy > AppData.server.time
      ) {
        let text;

        if (distributor.dx_eta_date) {
          text = "Warehouse is closed until";
        } else {
          const date = formatDate(product.eta_date_mm_dd_yyyy);

          text = `Expected availability: ${date}`;
        }

        labelListData.push({
          text: text,
          type: EType.outOfStock,
        });
      }
    }
  } else {
    if (flags.isFreeShipping) {
      labelListData.push({
        text: "Free Shipping within contiguous U.S.",
        type: EType.freeShipping,
      });
    }

    if (flags.isFlatRate) {
      labelListData.push({
        text: "$8.99 flat rate shipping within contiguous U.S.",
        type: EType.freeShipping,
      });
    }

    if (product.lead_time_message) {
      labelListData.push({
        text: product.lead_time_message,
        type: EType.leadTime,
      });
    } else if (brand.leadtime_from) {
      if (!brand.leadtime_to || brand.leadtime_from === brand.leadtime_to) {
        labelListData.push({
          text: `Lead time for this product is ${brand.leadtime_from} business day(s)`,
          type: EType.leadTime,
        });
      } else {
        const text1 = "Lead time for this product is";
        const text2 = "business days";
        const text = `${text1} ${brand.leadtime_from}-${brand.leadtime_to} ${text2}`;

        labelListData.push({
          text: text,
          type: EType.leadTime,
        });
      }
    } else if (distributor.dx_leadtime) {
      if (
        distributor.dx_leadtime === distributor.dx_leadtime_to ||
        !distributor.dx_leadtime_to
      ) {
        const text = `Lead time for this product is ${distributor.dx_leadtime} business day(s)`;

        labelListData.push({
          text: text,
          type: EType.leadTime,
        });
      } else {
        const text1 = "Lead time for this product is";
        const text2 = "business days";
        const text = `${text1} ${distributor.dx_leadtime}-${distributor.dx_leadtime_to} ${text2}`;

        labelListData.push({
          text: text,
          type: EType.leadTime,
        });
      }
    }

    if (product.min_amount > 1) {
      if (product.mult_order_quantity === "Y") {
        labelListData.push({
          text: `Order in multiples of ${product.min_amount} item(s)`,
          type: EType.multiplyQuantity,
        });
      } else {
        labelListData.push({
          text: `Order at least ${product.min_amount} item(s)`,
          type: EType.multiplyQuantity,
        });
      }
    }

    if (
      product.eta_date_mm_dd_yyyy !== 0 &&
      product.eta_date_mm_dd_yyyy > AppData.server.time
    ) {
      let text;

      if (distributor.dx_eta_date) {
        text = "Warehouse is closed until";
      } else {
        const date = formatDate(product.eta_date_mm_dd_yyyy * 1000);

        text = `Expected availability: ${date}`;
      }

      labelListData.push({
        text: text,
        type: EType.outOfStock,
      });
    }
  }

  if (product.manufacturerid === flags.isEarlyChildhoodResources) {
    labelListData.push({
      text: "All sales are final. No returns or exchanges are allowed.",
      type: EType.outOfStock,
    });
  }

  return [...labelListData, ...labelListData];
}

const Labels: React.FC = function () {
  const labelsData = getLabelsData();
  const labelsTemplates = labelsData.map((props: ILabelProps) => (
    <Label {...props} containerClass={Styles.list__label} />
  ));

  if (labelsTemplates.length === 0) {
    return;
  }

  return <div className={"mb-3"}>{labelsTemplates}</div>;
};

export default Labels;
