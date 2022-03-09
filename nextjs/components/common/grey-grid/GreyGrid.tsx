import React from "react";
import Styles from "@components/common/grey-grid/GreyGrid.module.scss";
import cn from "classnames";

interface IProps {
  items?: React.ReactNode[];
  classes?: {
    list?: any;
    item?: any;
  };
}

const GreyGrid: React.FC<IProps> = (props) => {
  const { items } = props;
  const classes = {
    item: [Styles.item, Styles.block__item, props.classes?.item],
    list: [Styles.block, props.classes?.list],
  };

  function itemsTemplateList() {
    const itemList: React.ReactNode[] = [];

    if (items) {
      for (const i in items) {
        itemList.push(
          <div key={`grey-grid-item-${i}`} className={cn(classes.item)}>
            {items[i]}
          </div>
        );
      }
    }

    if (props.children) {
      itemList.push(
        <div key={`grey-grid-children-item`} className={cn(classes.item)}>
          {props.children}
        </div>
      );
    }

    return itemList;
  }

  return <div className={cn(classes.list)}>{itemsTemplateList()}</div>;
};

export default GreyGrid;
