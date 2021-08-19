import React, { Fragment, useContext, useEffect, useState } from "react";
import { Button, Grid } from "@material-ui/core";
import { Form, Row, Col, Spinner } from "react-bootstrap";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { SelectFraudStatus } from "@admin/modules/general-settings/components/fraud-check-options/check-settings-form/fields/select-fraud-status";
import { InputFraudField } from "@admin/modules/general-settings/components/fraud-check-options/check-settings-form/fields/input-fraud-field";
import { UsersFraudSelect } from "@admin/modules/general-settings/components/fraud-check-options/check-settings-form/fields/users-fraud-select";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { defaultStateForm } from "@admin/modules/general-settings/ts/consts/fraud-check/default-state";
import {
  FormDataFraud,
  SettingsList,
} from "@admin/modules/general-settings/ts/types/fraud-check/data";
import {
  ResponseFraudGet,
  ResponseFraudSave,
} from "@admin/modules/general-settings/ts/types/fraud-check/response";

const api = new ApiService();
export const CheckSettingsForm: React.FC<any> = () => {
  const [formData, setFormData] = useState<FormDataFraud>(defaultStateForm);
  const [settingsList, setSettingsList] = useState<SettingsList>({
    users: [],
    status: [],
  });
  const [loading, setLoading] = useState(true);
  const { showSnackbar } = useContext(SnackbarContext);

  const onInputChange = (event) => {
    setFormData({
      ...formData,
      ...{ [event.target.name]: event.target.value },
    });
  };
  useEffect(() => {
    api.get("/api/fraud/settings/get").then((data: ResponseFraudGet) => {
      if (data.status) {
        if (data.settings) {
          setSettingsList(data.settings);
        }
        const newDefaultState = {};
        for (const attr in data.data) {
          newDefaultState[attr] = data.data[attr];
        }
        setFormData(newDefaultState);
        setLoading(false);
      }
    });
  }, []);

  const onSaveHandler = () => {
    setLoading(true);
    api
      .post("/api/fraud/settings/save", JSON.stringify(formData))
      .then((data: ResponseFraudSave) => {
        if (data.status) {
          showSnackbar("You have successfully updated the data");
          setLoading(false);
        } else if (data.error) {
          showSnackbar(`error: ${data.error}`, "error");
        }
      });
  };
  return (
    <Grid
      container
      justifyContent="center"
      alignItems="center"
      direction="column"
    >
      {loading ? (
        <Spinner animation="grow" variant="warning" />
      ) : (
        <React.Fragment>
          <Form className="form__check_settings">
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Domains of free email providers
              </Form.Label>
              <InputFraudField
                state={{
                  get: formData.fraud_domains_free_email_provider,
                  set: onInputChange,
                }}
                name="fraud_domains_free_email_provider"
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Risk score threshold for `Clear` status:
              </Form.Label>
              <InputFraudField
                state={{
                  get: formData.Overall_RS_threshold_for_Clear_status,
                  set: onInputChange,
                }}
                name="Overall_RS_threshold_for_Clear_status"
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Below Risk score threshold status:
              </Form.Label>
              <SelectFraudStatus
                name="Risk_Score_Threshold_status"
                state={{
                  get: formData.Risk_Score_Threshold_status,
                  set: onInputChange,
                }}
                list={settingsList.status}
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Under review users:
              </Form.Label>
              <UsersFraudSelect
                userList={settingsList.users}
                state={{ get: formData.Under_review_users, set: onInputChange }}
                name="Under_review_users"
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Effective FC threshold for `Clear` status:
              </Form.Label>
              <InputFraudField
                state={{
                  get: formData.Overall_FC_threshold_for_Clear_status,
                  set: onInputChange,
                }}
                name="Overall_FC_threshold_for_Clear_status"
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Threshold status:
              </Form.Label>
              <SelectFraudStatus
                name="Threshold_status"
                state={{ get: formData.Threshold_status, set: onInputChange }}
                list={settingsList.status}
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Below threshold status:
              </Form.Label>
              <SelectFraudStatus
                name="below_threshold_status"
                state={{
                  get: formData.below_threshold_status,
                  set: onInputChange,
                }}
                list={settingsList.status}
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Google address search exclusions:
              </Form.Label>
              <InputFraudField
                state={{
                  get: formData.fraud_Google_address_search_exclusions,
                  set: onInputChange,
                }}
                name="fraud_Google_address_search_exclusions"
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Google phone search exclusions:
              </Form.Label>
              <InputFraudField
                state={{
                  get: formData.fraud_Google_phone_search_exclusions,
                  set: onInputChange,
                }}
                name="fraud_Google_phone_search_exclusions"
              />
            </Form.Group>
            <Form.Group className="form__group_section" as={Row}>
              <Form.Label column sm={2}>
                Google email search exclusions:
              </Form.Label>
              <InputFraudField
                state={{
                  get: formData.fraud_Google_email_search_exclusions,
                  set: onInputChange,
                }}
                name="fraud_Google_email_search_exclusions"
              />
            </Form.Group>
          </Form>
          <div className="form__submit_button">
            <Button variant="contained" onClick={onSaveHandler}>
              Save
            </Button>
          </div>
        </React.Fragment>
      )}
    </Grid>
  );
};
