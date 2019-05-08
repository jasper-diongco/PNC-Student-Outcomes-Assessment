import axios from "axios";

export default axios.create({
  baseURL: "http://localhost/pnc_soa/public",
  headers: {
    "Content-Type": "application/json",
    "X-Requested-With": "XMLHttpRequest"
  }
});
