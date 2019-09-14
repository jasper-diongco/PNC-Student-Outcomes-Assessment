import axios from "axios";

export default axios.create({
    baseURL: "/pnc_soa/public",
    headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest"
    }
});
