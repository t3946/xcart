import React, { Fragment, useContext, useEffect, useState } from "react";
import { Grid, Paper, Typography, CircularProgress } from "@material-ui/core";
import { FraudCheckHat } from "@admin/modules/order-fraud/components/fraud-check-hat/fraud-check-hat";
import { ApiService } from "@admin/modules/shared/services/api.service";
import {
  ResponseChangeResultFraudCheck,
  ResponseFraudCheckOrder,
} from "@admin/modules/order-fraud/ts/types/response";
import { SettingsFraudOrder } from "@admin/modules/order-fraud/ts/types/settings";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
import { FraudTableQuestion } from "@admin/modules/order-fraud/components/fraud-table-question/fraud-table-question";
import { FraudInfoBasement } from "@admin/modules/order-fraud/components/fraud-info-basement/fraud-info-basement";
import {
  AnswerFraudOrder,
  FAAnswer,
  MainAnswer,
} from "@admin/modules/order-fraud/ts/types/answer";
import { MatrixQuestion } from "./matrix-question/matrix-question";
import { MatrixModal } from "@admin/modules/order-fraud/components/matrix-modal/matrix-modal";
import { NotCheckInfo } from "@admin/modules/order-fraud/components/not-check-info/not-check-info";
import { FraudScoreResult } from "@admin/modules/order-fraud/components/fraud-score-result/fraud-score-result";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { FraudPaymentAnswer } from "@admin/modules/order-fraud/components/fraud-payment-answer/fraud-payment-answer";

const api = new ApiService();

interface FraudMainPage {
  orderId: number | string;
}

export const FraudMainPage: React.FC<FraudMainPage> = ({ orderId }) => {
  const [settingsFraud, setSettingsFraud] = useState<SettingsFraudOrder>({});
  const [answer, setAnswer] = useState<AnswerFraudOrder>({});
  const [fraudManual, setFraudManual] = useState({});
  const [modalOpen, setModalOpen] = useState(false);
  const [notCheck, setNotCheck] = useState(false);
  const [templateModal, setTemplateModal] = useState<MainAnswer | FAAnswer>({});
  const [loading, setLoading] = useState(true);
  const { showSnackbar } = useContext(SnackbarContext);
  useEffect(() => {
    handlerFraudCheckInfo();
  }, []);

  const handlerFraudCheckInfo = () => {
    api
      .get(`/api/order/fraud-check/settings/${orderId}`)
      .then((res: ResponseFraudCheckOrder) => {
        setLoading(false);
        if (res.status) {
          if (res.settings) {
            setSettingsFraud(res.settings);
          }
          if (res.answer) {
            setAnswer(res.answer);
          }
          if (res.settings.manual_action) {
            setFraudManual(res.settings.manual_action);
          }
        } else {
          setNotCheck(true);
        }
      });
  };
  const onChangeFraudManual = (event) => {
    setFraudManual((prev) => {
      return {
        ...prev,
        ...{
          [event.target.dataset.section]: {
            ...prev[event.target.dataset.section],
            ...{ [event.target.dataset.field]: event.target.value },
          },
        },
      };
    });
  };
  const onApplyFrauds = () => {
    const frm = new FormData();
    frm.append("field", JSON.stringify(fraudManual));
    frm.append("order_id", orderId);
    api
      .post("/api/order/fraud-check/change-result", frm)
      .then((res: ResponseChangeResultFraudCheck) => {
        if (res.status) {
          showSnackbar("You have successfully replaced answers");
          setAnswer((prev) => {
            for (const type in fraudManual) {
              for (const question_code in fraudManual[type]) {
                const changeAnswer = prev[type].find(
                  (ans) => ans.question_code === question_code
                );
                if (
                  changeAnswer.manual_action !==
                  fraudManual[type][question_code]
                ) {
                  switch (fraudManual[type][question_code]) {
                    case "Y":
                      changeAnswer.fraud_result = "positive";
                      changeAnswer.fraud_score = changeAnswer.question_weight;
                      break;
                    case "N":
                      changeAnswer.fraud_result = "negative";
                      changeAnswer.fraud_score = "0.00";
                      break;
                  }
                }
                changeAnswer.manual_action = fraudManual[type][question_code];
              }
            }
            return { ...prev };
          });
          if (res.fraud_result) {
            setSettingsFraud((prev) => {
              return { ...prev, ...res.fraud_result };
            });
          }
        }
      });
  };

  const handlerDialog = () => {
    setModalOpen(!modalOpen);
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

  return (
    <Fragment>
      {!notCheck ? (
        <FraudCheckOrderContext.Provider
          value={{
            orderId,
            settings: settingsFraud,
            setSettings: setSettingsFraud,
            fraudManual,
            setFraudManual: onChangeFraudManual,
            dialog: { get: modalOpen, set: handlerDialog },
            template: { get: templateModal, set: setTemplateModal },
          }}
        >
          <FraudCheckHat />
          <br />
          <div className="title-wrapper__fraud-check-question">
            <div className="title__fraud-check-question">
              Fraud check questions
            </div>
          </div>
          <Paper elevation={3}>
            <Grid
              container
              alignItems="center"
              justifyContent="center"
              direction="column"
            >
              <div className="table-wrapper__fraud-check-question">
                <Typography variant="h6" align="center">
                  Full names: Cross check matrix
                </Typography>
                {settingsFraud.column_fn && (
                  <MatrixQuestion
                    columns={settingsFraud.column_fn}
                    answerList={answer.full_name}
                  />
                )}
              </div>
              <div className="table-wrapper__fraud-check-question">
                <Typography variant="h6" align="center">
                  Addresses: Cross check matrix
                </Typography>
                {settingsFraud.column_fn && (
                  <MatrixQuestion
                    columns={settingsFraud.column_address}
                    answerList={answer.address}
                  />
                )}
              </div>
              <div className="table-wrapper__fraud-check-question">
                <FraudTableQuestion
                  title="Diagonal checks"
                  nameTable="diagonal"
                  listAnswer={answer.diagonal}
                />
              </div>
              <div className="table-wrapper__fraud-check-question">
                <FraudTableQuestion
                  title="Red flags"
                  nameTable="red_flags"
                  listAnswer={answer.red_flags}
                />
              </div>
              <FraudPaymentAnswer answer={answer.payment} />
              <div className="table-wrapper__fraud-check-question">
                <FraudScoreResult />
              </div>
              <div className="fraud-check-button-appply">
                <button onClick={onApplyFrauds}>
                  Apply changes and update fraud scores
                </button>
              </div>
              <FraudInfoBasement />
            </Grid>
          </Paper>
          <MatrixModal />
        </FraudCheckOrderContext.Provider>
      ) : (
        <NotCheckInfo
          orderId={orderId}
          setNotCheck={setNotCheck}
          handlerFraudInfo={handlerFraudCheckInfo}
          setLoading={setLoading}
        />
      )}
    </Fragment>
  );
};
