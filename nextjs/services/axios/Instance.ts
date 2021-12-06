import axios from "axios";

const instance = axios.create({
  baseURL: "http://nginx",
});

export default instance;
