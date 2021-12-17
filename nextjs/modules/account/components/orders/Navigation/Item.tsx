import React from "react";
import classnames from "classnames";
import Link from "next/link";
import Badge from "@modules/components/Badge/Badge";
import Styles from "@modules/account/components/orders/Navigation/Navigation.module.scss";

interface IProps {
  text: string;
  path: string;
  badge?: string | number;
  classes?: {
    button?: any;
    text?: any;
    badge?: any;
  };
}

const Item: React.FC<IProps> = (props: IProps) => {
  const { text, path, badge } = props;
  const classes = {
    button: [
      Styles.ordersNavigationButton,
      Styles.ordersNavigation__button,
      props.classes?.button,
    ],
    text: [Styles.ordersNavigationText, props.classes?.text],
    badge: [Styles.ordersNavigationText__badge, props.classes?.badge],
  };

  return (
    <Link href={path}>
      <a className={classnames(classes.button)}>
        <span className={classnames(classes.text)}>
          {text}
          {badge && (
            <Badge className={classnames(classes.badge)} text={badge} />
          )}
        </span>
      </a>
    </Link>
  );
};

export default Item;
