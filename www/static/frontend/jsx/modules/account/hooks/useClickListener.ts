function useCLickListener(func: (arg: boolean) => void, id?): any {
  const handleFunction = (e) => {
    if (e.target.id === id) {
      return;
    }
    func(false);
  };

  const startListen = () => {
    window.addEventListener("click", handleFunction);
  };

  const endListen = () => {
    window.removeEventListener("click", handleFunction);
  };

  return {
    startListen,
    endListen,
  };
}

export default useCLickListener;
