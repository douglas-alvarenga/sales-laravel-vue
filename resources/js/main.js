import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import api from "./services/api";
import "../css/app.css";

const app = createApp(App);
app.use(router);
app.mount("#app");

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem("auth_token");
    let isAuthenticated = false;

    if (token) {
        try {
            await api.get("/me");
            isAuthenticated = true;
        } catch (error) {
            localStorage.removeItem("auth_token");
        }
    }

    if (to.meta.requiresAuth && !isAuthenticated) {
        next("/login");
    } else if (to.path === "/login" && isAuthenticated) {
        next("/");
    } else {
        next();
    }
    /* 
    if (to.path === "/login") {
        return next();
    }

    if (!token && to.meta.requiresAuth) {
        return next("/login");
    }

    if (token && to.meta.requiresAuth) {
        try {
            await api.get("/me"); // valida se token é realmente válido
            next();
        } catch (error) {
            localStorage.removeItem("auth_token");
            return next("/login");
        }
    } else {
        next();
    } */
});
