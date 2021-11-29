import * as React from "react";
import cn from "classnames";
import Styles from "@modules/acccount/components/Account.module.scss";

export default function Counter() {
  const [count, setCount] = React.useState(0);

  function inc() {
    setCount(count + 1);
  }

  return (
    <div>
      <p className={cn(Styles.counter)}>counter: {count}</p>
      <button onClick={inc}>inc</button>
    </div>
  );
}
