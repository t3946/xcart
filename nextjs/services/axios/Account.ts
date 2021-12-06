import instance from "@services/axios/Instance";

const getInitialState = async function () {
  let initialState: any;

  await instance.get("/api/account/get-initial-data").then((res) => {
    initialState = res.data;
  });

  return initialState;
};

export default getInitialState;
