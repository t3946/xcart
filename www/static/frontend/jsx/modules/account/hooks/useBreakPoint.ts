import { setBreakpoint } from "../../../redux/actions/account-actions/MainActions";
import { accountStore } from "../../../redux/stores/StoreAccount";
import { Breakpoint } from "@client/modules/account/ts/types/breakpoint.type";

function Breakpoints(is1920, is1366, is768) {
  this.is1920 = is1920;
  this.is1920 = is1366;
  this.is768 = is768;
}

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
    return new Breakpoints(true, false, false);
  }
  if (resolution > 768) {
    return new Breakpoints(false, true, false);
  }
  return new Breakpoints(false, false, true);
}
