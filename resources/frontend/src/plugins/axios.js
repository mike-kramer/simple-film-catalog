import axios from "axios";

export default {
    install: (app, options) => {
        app.config.globalProperties.$axios = window.$axios = axios.create({
            withCredentials: true,
            baseURL: location.host.includes("localhost") ? "http://localhost:24000" : ""
        });
    }
}
