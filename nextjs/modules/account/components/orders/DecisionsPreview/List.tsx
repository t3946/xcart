import React, { useEffect } from "react";
import Item from "@modules/account/components/orders/DecisionsPreview/Item";
import classnames from "classnames";
import Link from "next/link";
import style from "@modules/account/components/orders/DecisionsPreview/List.module.scss";
import {
  getAction,
  addAction as addDecisionsAction,
} from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import ItemSkeleton from "@modules/account/components/orders/DecisionsPreview/ItemSkeleton";

interface IProps {
  items: Record<any, any>[];
  total: number;
  className?: any;
  solved: boolean;
}

const List: React.FC<IProps> = function (props: IProps) {
  const itemsPerPaginationPage = 3;
  const { items: decisions, total: totalDecisions, className } = props;
  const items = [];
  const classes = {
    list: [
      className,
      style.decisionsListItems,
      {
        "overflow-hidden": decisions.length <= itemsPerPaginationPage,
      },
    ],
  };
  const [isIntersected, setIsIntersected] = React.useState(false);
  const dispatch = useDispatch();
  const theLastItemRef = React.useRef(null);
  const containerRef = React.useRef<HTMLDivElement>(null);
  const [isLoading, setIsLoading] = React.useState(false);

  function isAllLoaded() {
    return decisions.length === totalDecisions;
  }

  function loadMoreDecision() {
    if (isLoading) {
      return;
    }

    setIsLoading(true);

    dispatch(
      getAction({
        data: {
          solved: props.solved ? 1 : 0,
          skip: decisions.length,
          take: itemsPerPaginationPage,
        },

        success(res: any) {
          setIsLoading(false);
          dispatch(addDecisionsAction(res.data));
        },
      })
    );
  }

  if (isIntersected && !isAllLoaded()) {
    setIsIntersected(false);
    loadMoreDecision();
  }

  for (let i = 0; i < decisions.length; i++) {
    const decision = decisions[i];
    const theLast = i === decisions.length - 1;

    items.push(
      <Link
        href={"/orders/decision/" + decision.decision_id}
        key={`${i}_${decision.decision_id}`}
      >
        <a className={"text-decoration-none p-0"}>
          <div
            style={{ height: "70px" }}
            ref={theLast && !isLoading ? theLastItemRef : null}
          >
            <Item
              decision={decision}
              ref={theLast && !isLoading ? theLastItemRef : null}
            />
          </div>
        </a>
      </Link>
    );
  }

  if (isLoading) {
    const skeletonsNumber = Math.min(
      totalDecisions - decisions.length,
      itemsPerPaginationPage
    );

    for (let i = 1; i <= skeletonsNumber; i++) {
      items.push(<ItemSkeleton key={`decision-skeleton-item-${i}`} />);
    }
  }

  useEffect(function () {
    const target = theLastItemRef.current;
    let reviewLoadedObserver = null;

    if (!target || isLoading) {
      return;
    }

    const options = {
      root: containerRef.current,
      rootMargin: "0px",
      threshold: 0.75,
    };

    reviewLoadedObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        setIsIntersected(!isLoading && entry.isIntersecting);

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
