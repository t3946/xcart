import React from "react";
import classnames from "classnames";
import { NavLink } from "react-router-dom";
import Badge from "@client/modules/account/components/orders/Navigation/Badge";

interface PropsInterface {
  text: string;
  path: string;
  badge?: string | number;
  classes?: {
    button?: any;
    text?: any;
    badge?: any;
  };
}

const Item: React.FC<PropsInterface> = (props: PropsInterface) => {
  const { text, path, badge } = props;
  const classes = {
    button: [
      "orders-navigation-button",
      "orders-navigation__button",
      props.classes?.button,
    ],
    text: ["orders-navigation-text", props.classes?.text],
    badge: ["orders-navigation-text__badge", props.classes?.badge],
  };

  return (
    <NavLink className={classnames(classes.button)} to={path} exact={true}>
      <span className={classnames(classes.text)}>
        {text}
        {badge && <Badge className={classnames(classes.badge)} text={badge} />}
      </span>
    </NavLink>
  );
};

export default Item;
