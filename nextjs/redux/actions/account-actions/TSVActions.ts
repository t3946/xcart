import {AxiosError, AxiosResponse} from "axios";

export const confirmDeviceAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_TSV_CONFIRM_DEVICE",
  payload,
});

export const disableAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_TSV_DISABLE",
  payload,
});

export const enableAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_TSV_ENABLE",
  payload,
});

export const requireForAllAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_TSV_REQUIRE_FOR_ALL",
  payload,
});

export const changePreferredMethodAction = (
  payload: Record<any, any>
): any => ({
  type: "ACCOUNT_TSV_CHANGE_PREFERRED_METHOD",
  payload,
});

export const setPreferredMethodAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_TSV_SET_PREFERRED_METHOD",
  payload,
});

interface IPayloadFormSubmit {
  data: Record<string, any> | FormData;
  success: (res: AxiosResponse) => void;
  catch?: (err: AxiosError) => void;
  finally?: () => void;
}

export const accessRecovery = (payload: IPayloadFormSubmit): any => ({
  type: "ACCOUNT_TSV_RECOVERY",
  payload,
});
