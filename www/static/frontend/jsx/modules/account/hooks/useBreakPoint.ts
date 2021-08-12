import { setBreakpoint } from "../../../redux/actions/account-actions/MainActions";
import { accountStore } from "../../../redux/stores/StoreAccount";
import { Breakpoint } from "@client/modules/account/ts/types/breakpoint.type";

export function useBreakPoint(): void {
  const isMount = true;

  accountStore.dispatch(setBreakpoint(changeBreakPoints(window.innerWidth)));

  window.onresize = function (event) {
    if (isMount) {
      accountStore.dispatch(
        setBreakpoint(changeBreakPoints(window.innerWidth))
      );
    }
  };
}

function changeBreakPoints(resolution: number): Breakpoint {
  if (resolution > 1366) {
    return {
      is1920: true,
      is1366: false,
      is768: false,
    };
  }
  if (resolution > 768) {
    return {
      is1920: false,
      is1366: true,
      is768: false,
    };
  }
  return {
    is1920: false,
    is1366: false,
    is768: true,
  };
}
