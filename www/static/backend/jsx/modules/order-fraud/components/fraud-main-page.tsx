import React, { Fragment, useContext, useEffect, useState } from "react";
import { Grid, Paper, Typography, CircularProgress } from "@material-ui/core";
import { FraudCheckHat } from "@admin/modules/order-fraud/components/fraud-check-hat/fraud-check-hat";
import { FraudTableQuestion } from "@admin/modules/order-fraud/components/fraud-table-question/fraud-table-question";
import { FraudInfoBasement } from "@admin/modules/order-fraud/components/fraud-info-basement/fraud-info-basement";
import { MatrixQuestion } from "./matrix-question/matrix-question";
import { NotCheckInfo } from "@admin/modules/order-fraud/components/not-check-info/not-check-info";
import { FraudScoreResult } from "@admin/modules/order-fraud/components/fraud-score-result/fraud-score-result";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { FraudPaymentAnswer } from "@admin/modules/order-fraud/components/fraud-payment-answer/fraud-payment-answer";
import { MatrixHistory } from "@admin/modules/order-fraud/components/matrix-history/MatrixHistory";
import { useDispatch, useSelector } from "react-redux";
import {
  changeFraudCheckResult,
  clearAlert,
  fetchForceFraudCheck,
  fetchStartCheckData,
  setFraudCheckOrderId,
  setTemplateView,
} from "@redux/actions/fraudCheckActions";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { MatrixModal } from "@admin/modules/order-fraud/components/matrix-modal/matrix-modal";
import { FAAnswer } from "@admin/modules/order-fraud/ts/types/answer";
import { MatchingAddress } from "@admin/modules/order-fraud/components/matching-address/MatchingAddress";

interface FraudMainPage {
  orderId: number | string;
}

export const FraudMainPage: React.FC<FraudMainPage> = ({ orderId }) => {
  const dispatch = useDispatch();
  const { data, noCheck, alert, loading } = useSelector(
    (state: FraudCheckStore) => state
  );
  const [open, setOpen] = useState(false);
  const [anchorEl, setAnchorEl] = React.useState(null);

  const handleOpenPopper = (
    event: React.MouseEvent<HTMLDivElement>,
    answer: FAAnswer
  ) => {
    dispatch(setTemplateView(answer));
    setAnchorEl(event.currentTarget);
    setOpen(true);
  };

  const { showSnackbar } = useContext(SnackbarContext);
  useEffect(() => {
    dispatch(setFraudCheckOrderId(Number(orderId)));
    dispatch(fetchStartCheckData(Number(orderId)));
  }, []);
  useEffect(() => {
    if (alert.state) {
      showSnackbar(alert.message, alert.status, () => dispatch(clearAlert()));
    }
  }, [alert.state]);

  const onApplyFrauds = () => {
    dispatch(
      changeFraudCheckResult(
        JSON.stringify({ orderId, change: data.resultChange })
      )
    );
  };
  const handleForceCheck = () => {
    dispatch(fetchForceFraudCheck(Number(orderId)));
  };

  if (loading) {
    return (
      <Grid
        container
        justifyContent="center"
        alignItems="center"
        direction="row"
      >
        <CircularProgress />
      </Grid>
    );
  }
  if (noCheck) {
    return (
      <NotCheckInfo
        handleForceCheck={handleForceCheck}
        orderId={Number(orderId)}
      />
    );
  }

  return (
    <Fragment>
      <FraudCheckHat />
      <br />
      <div className="title-wrapper__fraud-check-question">
        <div className="title__fraud-check-question">Fraud check questions</div>
      </div>
      <Paper elevation={3}>
        <Grid
          container
          alignItems="center"
          justifyContent="center"
          direction="column"
        >
          <div className="table-wrapper__fraud-check-question">
            <Typography variant="h6" align="left">
              Full names: Information gathering
            </Typography>
            <MatrixHistory historyColumn={data.legend.full_name} />
            <Typography variant="h6" align="left">
              Full names: Cross check matrix
            </Typography>
            <MatrixQuestion
              columns={data.columns.fullName}
              answerList={data.answer.full_name}
              handleClickAnswer={handleOpenPopper}
            />
          </div>
          <div className="table-wrapper__fraud-check-question">
            <Typography variant="h6" align="left">
              Addresses: Information gathering
            </Typography>
            <MatrixHistory historyColumn={data.legend.address} />
            <Typography variant="h6" align="left">
              Comparison of two addresses: Matching degrees
            </Typography>
            <MatchingAddress />
            <Typography variant="h6" align="left">
              Addresses: Cross check matrix
            </Typography>
            <MatrixQuestion
              columns={data.columns.address}
              answerList={data.answer.address}
              handleClickAnswer={handleOpenPopper}
            />
          </div>
          <div className="table-wrapper__fraud-check-question">
            <FraudTableQuestion
              title="Diagonal checks"
              listAnswer={data.answer.diagonal}
            />
          </div>
          <div className="table-wrapper__fraud-check-question">
            <FraudTableQuestion
              title="Red flags"
              listAnswer={data.answer.red_flags}
            />
          </div>
          <FraudPaymentAnswer answer={data.answer.payment} />
          <div className="table-wrapper__fraud-check-question">
            <FraudScoreResult />
          </div>
          <div className="fraud-check-button-apply">
            <button onClick={onApplyFrauds}>
              Apply changes and update fraud scores
            </button>
          </div>
          <FraudInfoBasement />
        </Grid>
      </Paper>
      {open && (
        <MatrixModal
          handleClose={() => setOpen(false)}
          anchor={anchorEl}
          open={open}
        />
      )}
    </Fragment>
  );
};
