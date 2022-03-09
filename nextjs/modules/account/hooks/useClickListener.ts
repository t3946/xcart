interface ClickListenerData {
  endListen: () => void;
  startListen: () => void;
}

function useCLickListener(
  callback: (arg: boolean) => void,
  id?: string
): ClickListenerData {
  const handleFunction = (e: any) => {
    if (e.target.id === id) {
      return;
    }

    callback(false);
  };

  const startListen = () => {
    if (process.browser) {
      document.body.addEventListener("click", handleFunction);
    }
  };

  const endListen = () => {
    if (process.browser) {
      document.body.removeEventListener("click", handleFunction);
    }
  };

  return {
    startListen,
    endListen,
  };
}

export default useCLickListener;
