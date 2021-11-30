interface ClickListenerData {
  endListen: () => void;
  startListen: () => void;
}

function useCLickListener(
  callback: (arg: boolean) => void,
  id?: string
): ClickListenerData {
  const handleFunction = (e) => {
    if (e.target.id === id) {
      return;
    }

    callback(false);
  };

  const startListen = () => {
    document.body.addEventListener("click", handleFunction);
  };

  const endListen = () => {
    document.body.removeEventListener("click", handleFunction);
  };

  return {
    startListen,
    endListen,
  };
}

export default useCLickListener;
