import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { useHistory } from "react-router-dom";

export function onCardActionsEnd(handleClose: () => void): void {
  const history = useHistory();
  const breakpoint = useBreakpoint();
  breakpoint({
    xs: () => history.push("/account/payments/wallet"),
    sm: handleClose,
  });
}
