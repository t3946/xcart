import { useState } from "react";
import { useEffect } from "preact/hooks";
import React from "react";
import { useDispatch } from "react-redux";
import { setBreakpoint } from "../../../redux/actions/account-actions/MainActions";
import { accountStore } from "../../../redux/stores/StoreAccount";

export function useBreakPoint() {
  const isMount = true;

  accountStore.dispatch(
    setBreakpoint(changeBreakPoints(window.innerWidth, isMount))
  );

  window.onresize = function (event) {
    if (isMount) {
      accountStore.dispatch(
        setBreakpoint(changeBreakPoints(window.innerWidth, isMount))
      );
    }
  };
}

function changeBreakPoints(resolution: number, isMount: boolean) {
  if (resolution > 1366) {
    return {
      is1920: true,
      is1366: false,
      is768: false,
      isMount,
    };
  }
  if (resolution > 768) {
    return {
      is1920: false,
      is1366: true,
      is768: false,
      isMount,
    };
  }
  return {
    is1920: false,
    is1366: false,
    is768: true,
    isMount,
  };
}
