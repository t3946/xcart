import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/OriginalPurchaseOrder.module.scss";
import AsFile from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/AsFile";
import Card from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/Card";
import Alert from "@modules/account/components/shared/Alert";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useDispatch, useSelector } from "react-redux";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { iSentOriginalPurchaseOrderViaFaxAction } from "@redux/actions/account-actions/DecisionsActions";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const OriginalPurchaseOrder: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  const classes = {
    p: [
      Styles.decision__caption,
      Styles.decisionCaption,
      "estimate-table-caption",
      "estimate-table__caption",
    ],
  };
  const dispatch = useDispatch();
  const alert = useSelector((e: StoreInterface) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  const [submitting, setSubmitting] = React.useState(false);

  const clickSentOriginalPoFax = () => {
    setSubmitting(true);
    dispatch(
      iSentOriginalPurchaseOrderViaFaxAction({
        data: {},
        success(res: DecisionsInterface) {
          onChange(res);
          setShow(true);
          dispatch(
            setAlertAction({
              variant: "success",
              message: `Thank you for sending your original Purchase Order! 
                  We will review it and send the order to you ASAP.`,
            })
          );
          setTimeout(()=>setSubmitting(true),3000);
        },
      })
    );
  };

  return (
    <>
      <h1
        className={cn(
          Styles.decisionTitle,
          "decision-inner-header",
          "decision__inner-header"
        )}
      >
        Send us original PO
      </h1>
      {alert ? (
        <Alert
          show={show}
          variant={alert?.variant}
          message={alert?.message}
          classes={{
            container: "pt-20 pb-5 pt-lg-0",
            alert: "account-inner-page_alert",
          }}
        />
      ) : (
        <>
          <p className={cn([classes.p, "mb-20", "mb-lg-4"])}>
            Thank you for submitting your purchase order online!
          </p>
          <p className={cn(classes.p)}>
            However we also require original Purchase Order (PO) sent to us
          </p>
          <div
            className={cn([
              "align-items-center",
              "mx-lg-4",
              Styles.decision__CardLayout,
              Styles.decisionCardLayout,
            ])}
          >
            <Card>
              <AsFile />
            </Card>
            <div
              className={cn([
                "d-flex",
                "align-items-center",
                "justify-content-center",
                "text-uppercase",
                Styles.decisionCardLayout__or,
                Styles.or,
              ])}
            >
              or
            </div>
            <Card>
              <div
                className={cn([
                  Styles.decisionCardBodyFax,
                  "d-flex",
                  "flex-dir-column",
                  "justify-content-between",
                ])}
              >
                <div className={cn([Styles.cardText])}>
                  <b>Via fax to</b>
                  <br />
                  <b className="text-capitalize">Fax</b> 1-800-929-2835
                </div>
                <button
                  className={cn(["form-button", "w-lg-auto", Styles.button])}
                  type="button"
                  onClick={clickSentOriginalPoFax}
                  disabled={submitting}
                >
                  <span className="d-none d-lg-inline">
                    I sent original PO via fax
                  </span>
                  <span className="d-lg-none">Fax sent</span>
                </button>
              </div>
            </Card>
          </div>

          <p className={cn([classes.p, "mb-5"])}>
            PS. Please also advise your accounting department to make out a
            check payable to <b>S3 Stores Inc.</b> upon delivery of your order.
          </p>
        </>
      )}
    </>
  );
};

export default OriginalPurchaseOrder;
