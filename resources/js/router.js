import { createRouter, createWebHistory } from "vue-router";

import Login from "./pages/Login.vue";
import Home from "./pages/Home.vue";
import UserIndex from "./pages/users/UserIndexPage.vue";
import UserForm from "./pages/users/UserFormPage.vue";
import UserView from "./pages/users/UserViewPage.vue";
import SellerIndex from "./pages/sellers/SellerIndexPage.vue";
import SellerForm from "./pages/sellers/SellerFormPage.vue";
import SellerView from "./pages/sellers/SellerViewPage.vue";
import SaleIndex from "./pages/sales/SaleIndexPage.vue";
import SaleForm from "./pages/sales/SaleFormPage.vue";
import SaleView from "./pages/sales/SaleViewPage.vue";

const routes = [
    { path: "/login", name: "login", component: Login },
    { path: "/", name: "home", component: Home, meta: { requiresAuth: true } },
    { path: "/users", component: UserIndex, meta: { requiresAuth: true } },
    { path: "/users/form", component: UserForm, meta: { requiresAuth: true } },
    {
        path: "/users/form/:id",
        component: UserForm,
        meta: { requiresAuth: true },
    },
    { path: "/users/:id", component: UserView, meta: { requiresAuth: true } },
    { path: "/sellers", component: SellerIndex, meta: { requiresAuth: true } },
    {
        path: "/sellers/form",
        component: SellerForm,
        meta: { requiresAuth: true },
    },
    {
        path: "/sellers/form/:id",
        component: SellerForm,
        meta: { requiresAuth: true },
    },
    {
        path: "/sellers/:id",
        component: SellerView,
        meta: { requiresAuth: true },
    },
    { path: "/sales", component: SaleIndex, meta: { requiresAuth: true } },
    {
        path: "/sales/form",
        component: SaleForm,
        meta: { requiresAuth: true },
    },
    {
        path: "/sales/form/:id",
        component: SaleForm,
        meta: { requiresAuth: true },
    },
    {
        path: "/sales/:id",
        component: SaleView,
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem("auth_token") !== null;

    if (to.meta.requiresAuth && !isAuthenticated) {
        next("/login");
    } else if (to.path === "/login" && isAuthenticated) {
        next("/");
    } else {
        next();
    }
});

export default router;
