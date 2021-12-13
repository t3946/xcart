import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import breakpoints from "@modules/account/ts/consts/breakpoints";
import Store from "@redux/stores/Store";

function getScreenWidth() {
  const backdropScreenSize = 768;
  return process.browser ? window.innerWidth : backdropScreenSize;
}

function resizeHandler() {
  Store.dispatch(setBreakpoint(getBreakpointsFlags(getScreenWidth())));
}

export default function useBreakpoint(): (
  actions: Record<EBreakPoints, any>
) => any {
  if (process.browser) {
    window.removeEventListener("resize", resizeHandler);
    window.addEventListener("resize", resizeHandler);
  }

  return executeBreakpoint;
}

enum EBreakPoints {
  xxl = "xxl",
  xl = "xl",
  lg = "lg",
  md = "md",
  sm = "sm",
  xs = "xs",
}

/**
 * From passed breakpoint actions to select the most relevant and return it
 * @param actions actions for breakpoints
 */
function executeBreakpoint(actions: Record<any, any>): any {
  const breakpointsOrder: EBreakPoints[] = [
    EBreakPoints.xxl,
    EBreakPoints.xl,
    EBreakPoints.lg,
    EBreakPoints.md,
    EBreakPoints.sm,
    EBreakPoints.xs,
  ].reverse();
  const breakpointsFlags = getBreakpointsFlags(getScreenWidth());
  if (process.browser) {
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
  } else {
    const allActions: any[] = [];

    for (const breakpointName of breakpointsOrder) {
      if (actions[breakpointName] !== undefined) {
        allActions.push(actions[breakpointName]);
      }
    }

    return allActions;
  }
}

export function getBreakpointsFlags(resolution: number): any {
  const activeBreakpoints: any = {};

  for (const sizeName in breakpoints) {
    activeBreakpoints[sizeName] = resolution >= breakpoints[sizeName];
  }

  return activeBreakpoints;
}
