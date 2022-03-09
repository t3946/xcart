import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/order/order-table/OrderTable.module.scss";

interface IProps {
  header: React.ReactNode[];
  theme?: string;
  items: any[];
  rowItemTemplates: (item: any) => React.ReactNode[];
  caption?: string;

  classes?: {
    table?: any;
    columns?: any[];
    row?: any;
    rowHat?: any;
    rowItem?: any;
  };
  rowFooterTemplate?: (item: any) => any;
}

const OrderTable: React.FC<IProps> = (props) => {
  const {
    header,
    theme,
    items,
    rowItemTemplates,
    classes,
    caption,
    rowFooterTemplate,
  } = props;
  function rowTemplate(items: React.ReactNode[], name: string) {
    const itemsTemplate = [];

    for (const i in items) {
      const item = items[i];

      itemsTemplate.push(
        <div
          className={cn(classes?.columns && classes.columns[i])}
          key={`${name}-cell-${i}`}
        >
          {item}
        </div>
      );
    }
    return itemsTemplate;
  }

  const itemList = Object.keys(items).map((key) =>
    React.useMemo(() => rowItemTemplates(items[key]), [items[key]])
  );

  return (
    <div className={cn(Styles.table, classes?.table)}>
      {caption && (
        <p className={cn([Styles.caption, Styles.table__caption])}>{caption}</p>
      )}

      <div
        className={cn(
          Styles.row,
          Styles.row_hat,
          Styles.header_theme_grey,
          classes?.row,
          classes?.rowHat,
          {
            [Styles[`header_theme_${theme}`]]: theme,
          }
        )}
        key={"hat-row"}
      >
        <div className={cn(Styles.rowItems, "h-100")}>
          {rowTemplate(header, "header")}
        </div>
      </div>

      {itemList.map((item, index) => (
        <div className={cn(Styles.row)} key={`row-${index}`}>
          <div
            className={cn(
              Styles.rowItems,
              Styles.rowItems_data,
              classes?.row,
              classes?.rowItem
            )}
          >
            {rowTemplate(item, "item")}
          </div>

          {rowFooterTemplate && rowFooterTemplate(items[index], index)}
        </div>
      ))}
    </div>
  );
};

export default OrderTable;
