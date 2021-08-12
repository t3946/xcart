interface ClickListenerData {
  endListen: () => void;
  startListen: () => void;
}

function useCLickListener(
  func: (arg: boolean) => void,
  id?: string
): ClickListenerData {
  const handleFunction = (e) => {
    if (e.target.id === id) {
      return;
    }
    func(false);
  };

  const startListen = () => {
    console.log('startListen');
    document.body.addEventListener("click", handleFunction);
  };

  const endListen = () => {
    console.log('endListen');
    document.body.removeEventListener("click", handleFunction);
  };

  return {
    startListen,
    endListen,
  };
}

export default useCLickListener;
