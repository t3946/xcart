import React, { useEffect } from "react";
import Item from "@client/modules/account/components/orders/DecisionsPreview/Item";
import classnames from "classnames";
import { Link } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import style from "@client/modules/account/components/orders/DecisionsPreview/List.module.scss";
import {
  loadMoreAction,
  addAction as addDecisionsAction,
} from "@client/jsx/redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";

interface PropsInterface {
  decisions: Record<any, any>[];
  className?: any;
  solved: boolean;
  onAllLoaded?: () => void;
}

const List: React.FC<PropsInterface> = function (props: PropsInterface) {
  const { decisions, className, onAllLoaded } = props;
  const items = [];
  const classes = {
    list: [
      className,
      style["decisions-list-items"],
      "pe-lg-3",
      {
        "overflow-hidden": decisions.length <= 3,
      },
    ],
  };
  const [isIntersecting, setIsIntersecting] = React.useState(false);
  const dispatch = useDispatch();
  const theLastItemRef = React.useRef(null);
  const containerRef = React.useRef<HTMLDivElement>(null);
  const [isLoading, setIsLoading] = React.useState(false);
  const [isAllLoaded, setIsAllLoaded] = React.useState(false);

  if (
    !isAllLoaded &&
    (decisions.length === 0 || (isIntersecting && !isLoading))
  ) {
    getMoreDecision();
    setIsIntersecting(false);
  }

  function getMoreDecision() {
    setIsIntersecting(false);

    if (isLoading) {
      return;
    }

    setIsLoading(true);

    dispatch(
      loadMoreAction({
        data: { solved: props.solved, offset: decisions.length },
        success(res) {
          if (res.length === 0) {
            setIsAllLoaded(true);
            onAllLoaded && onAllLoaded();
          }

          let actionData;

          if (props.solved) {
            actionData = {
              solved: res,
              notSolved: [],
            };
          } else {
            actionData = {
              solved: [],
              notSolved: res,
            };
          }

          dispatch(addDecisionsAction(actionData));
          setIsLoading(false);
        },
      })
    );
  }

  for (let i = 0; i < decisions.length; i++) {
    const decision = decisions[i];
    const theLast = i === decisions.length - 1;

    items.push(
      <Link
        to={route("account:order-make-decision", decision.decision_id)}
        className={"text-decoration-none p-0"}
      >
        <Item
          decision={decision}
          ref={theLast && !isLoading ? theLastItemRef : null}
        />
      </Link>
    );
  }

  if (isLoading) {
    const skeletonsNumber = 3;

    for (let i = 1; i <= skeletonsNumber; i++) {
      items.push(
        <div className={"text-decoration-none p-0"}>
          <Item
            decision={{ solved: props.solved }}
            classes={{ container: "skeleton-box" }}
          />
        </div>
      );
    }
  }

  useEffect(function () {
    let reviewLoadedObserver = null;
    const target = theLastItemRef.current?.base;

    if (!target || isLoading || isAllLoaded) {
      return;
    }

    const options = {
      root: containerRef.current,
      rootMargin: "0px",
      threshold: 0.75,
    };

    reviewLoadedObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        setIsIntersecting(!isLoading && entry.isIntersecting);

        if (entry.isIntersecting) {
          observer.unobserve(target);
        }
      });
    }, options);

    reviewLoadedObserver.observe(target);

    return function () {
      if (reviewLoadedObserver && target) {
        reviewLoadedObserver.unobserve(target);
      }
    };
  });

  return (
    <div className={classnames(classes.list)} ref={containerRef}>
      {items}
    </div>
  );
};

export default List;
