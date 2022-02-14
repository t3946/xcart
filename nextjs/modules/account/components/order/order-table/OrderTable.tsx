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
  };
}

const OrderTable: React.FC<IProps> = ({
  header,
  theme,
  items,
  rowItemTemplates,
  classes,
  caption,
}) => {
  function rowTemplate(items: React.ReactNode[], name: string) {
    const itemsTemplate = [];

    for (const i in items) {
      const headerItem = items[i];

      itemsTemplate.push(
        <div
          className={cn(classes?.columns && classes.columns[i])}
          key={`${name}-cell-${i}`}
        >
          {headerItem}
        </div>
      );
    }
    return itemsTemplate;
  }

  const itemList = Object.keys(items).map((key) => {
    const itemLine = React.useMemo(
      () => rowItemTemplates(items[key]),
      [items[key]]
    );
    return itemLine;
  });

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
      >
        {rowTemplate(header, "header")}
      </div>

      {itemList.map((item, index) => (
        <div className={cn(Styles.row, classes?.row)} key={`row-${index}`}>
          {rowTemplate(item, "item")}
        </div>
      ))}
    </div>
  );
};

export default OrderTable;
