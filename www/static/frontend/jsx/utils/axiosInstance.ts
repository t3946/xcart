import axios from "axios";

const instance = axios.create();

instance.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    if (error.response.status === 401) {
      setTimeout(() => (window.location.href = "/account/login"), 250);
    }
    return Promise.reject(error);
  }
);

export default instance;
