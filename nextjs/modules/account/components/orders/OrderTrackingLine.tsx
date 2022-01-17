import React from "react";
import { getDataToTracking } from "@modules/account/utils/get-data-to-tracking";
import { useSelector, useDispatch } from "react-redux";
import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import { getBreakpointsFlags } from "@modules/account/hooks/useBreakpoint";
import { AccountStore } from "@modules/account/ts/types/store.type";
import cn from "classnames";

import Styles from "@modules/account/components/orders/OrderTrackingLine.module.scss";

interface IProps {
  dc_status: string;
}

const OrderTrackingLine: React.FC<IProps> = ({ dc_status }) => {
  const dispatch = useDispatch();
  const breakpoints = useSelector(
    (store: AccountStore) => store.main.breakpoint
  );
  
  const [trackingViewData, setTrackingViewData] = React.useState({
    items: null,
    lineWidth: null,
  });

  React.useEffect(() => {
    dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
  }, []);

  React.useEffect(() => {
    setTrackingViewData(getDataToTracking(dc_status, !breakpoints?.md));
  }, [breakpoints, dc_status]);

  return (
    <div className={Styles.orderTrackingLine}>
      <div
        style={trackingViewData.lineWidth ?? undefined}
        className={Styles.orderTrackingLineBlue}
      />
      <div className="order-tracking-rounds">
        {trackingViewData.items &&
          trackingViewData.items.map((e, index) => {
            return (
              <div
                className={cn(Styles.orderTrackingLineRoundContainer, {
                  [Styles.orderTrackingLineRoundContainer_current]:
                    e.containerClass.current,
                  [Styles.kek]: index === 0,
                  [Styles.kek]: index === trackingViewData.items.length - 1,
                })}
                style={e.roundStyle}
                key={index}
              >
                <div
                  className={cn(
                    Styles.orderTrackingLineRound,
                    "d-none",
                    "d-md-block",
                    {
                      [Styles.orderTrackingLineRound_current]:
                        e.containerClass.current,
                      [Styles.orderTrackingLineRound_completed]:
                        e.containerClass.completed,
                      [Styles.orderTrackingLineRound_firstContainer]:
                        index === 0,
                      [Styles.orderTrackingLineRound_lastContainer]:
                        index === trackingViewData.items.length - 1,
                    }
                  )}
                />

                <div
                  className={cn(Styles.orderTrackingLineRoundText, {
                    [Styles.orderTrackingLineRoundText_current]:
                      e.containerClass.current,
                    [Styles.orderTrackingLineRoundText_completed]:
                      e.containerClass.completed,
                    [Styles.orderTrackingLineRoundText_firstContainer]:
                      index === 0,
                    [Styles.orderTrackingLineRoundText_lastContainer]:
                      index === trackingViewData.items.length - 1,
                  })}
                >
                  {e.label}
                </div>

                <div
                  className={cn(Styles.orderTrackingLineRound, "d-md-none", {
                    [Styles.orderTrackingLineRound_current]:
                      e.containerClass.current,
                    [Styles.orderTrackingLineRound_completed]:
                      e.containerClass.completed,
                    [Styles.orderTrackingLineRound_firstContainer]: index === 0,
                    [Styles.orderTrackingLineRound_lastContainer]:
                      index === trackingViewData.items.length - 1,
                  })}
                />

                <div
                  className={cn(Styles.orderTrackingLineRoundDate, {
                    [Styles.orderTrackingLineRoundDate_firstContainer]:
                      index === 0,
                    [Styles.orderTrackingLineRoundDate_lastContainer]:
                      index === trackingViewData.items.length - 1,
                  })}
                >
                  {e?.date}
                </div>
              </div>
            );
          })}
      </div>
    </div>
  );
};

export default OrderTrackingLine;
