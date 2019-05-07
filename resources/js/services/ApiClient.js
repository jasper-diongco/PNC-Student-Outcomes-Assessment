import axios from "axios";

export default axios.create({
  baseURL: "http://localhost/pnc_soa/public",
  withCredentials: false,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
    "X-Requested-With": "XMLHttpRequest"
  }
});
