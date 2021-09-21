import { setBreakpoint } from "@client/jsx/redux/actions/account-actions/MainActions";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import breakpoints from "@client/modules/account/ts/consts/breakpoints";

function resizeHandler() {
  accountStore.dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
}

export default function useBreakpoint(): (actions: ActionsInterface) => void {
  window.removeEventListener("resize", resizeHandler);
  window.addEventListener("resize", resizeHandler);

  return executeBreakpoint;
}

interface ActionsInterface {
  xxl?: any;
  xl?: any;
  lg?: any;
  md?: any;
  sm?: any;
  xs?: any;
}

/**
 * From passed breakpoint actions to select the most relevant and return it
 * @param actions actions for breakpoints
 */
function executeBreakpoint(actions: ActionsInterface): any {
  const breakpointsOrder = ["xxl", "xl", "lg", "md", "sm"].reverse();
  const breakpointsFlags = getBreakpointsFlags(window.innerWidth);
  let action;

  for (const breakpointName of breakpointsOrder) {
    if (
      breakpointsFlags[breakpointName] &&
      actions[breakpointName] !== undefined
    ) {
      action = actions[breakpointName];
    }
  }

  if (action === undefined) {
    action = actions["xs"];
  }

  return typeof action === "function" ? action() : action;
}

function getBreakpointsFlags(resolution: number): any {
  const activeBreakpoints = {};

  for (const sizeName in breakpoints) {
    activeBreakpoints[sizeName] = resolution >= breakpoints[sizeName];
  }

  return activeBreakpoints;
}
