import { createRouter, createWebHistory } from "vue-router";
import authGuard from "./components/middlewares/authGuard";
const GuestLayout = () => import("./components/layouts/GuestLayout.vue");
const MainLayout = () => import("./components/layouts/MainLayout.vue");
const Login = () => import("./components/pages/Login.vue");
const Logout = () => import("./components/pages/Logout.vue");
const ForgotPassword = () => import("./components/pages/ForgotPassword.vue");
const Register = () => import("./components/pages/Register.vue");
const Account = () => import("./components/pages/Account.vue");
const Products = () => import("./components/pages/Products.vue");
const Cart = () => import("./components/pages/Cart.vue");
const PageNotFound = () => import("./components/pages/PageNotFound.vue");
const Unauthorized = () => import("./components/pages/Unauthorized.vue");

const router = createRouter({
    history: createWebHistory(import.meta.env.APP_URL),
    routes: [
        {
            path: "/",
            component: GuestLayout,
            children: [
                {
                    path: "/login",
                    name: "Login",
                    component: Login,
                },
                {
                    path: "/register",
                    name: "Register",
                    component: Register,
                },
                {
                    path: "/forgot-password",
                    name: "ForgotPassword",
                    component: ForgotPassword,
                },
            ],
        },
        {
            component: MainLayout,
            meta: { requiresAuth: true },
            children: [
                {
                    path: "/products",
                    name: "Products",
                    component: Products,
                },
                {
                    path: "/cart",
                    name: "Cart",
                    component: Cart,
                },
                {
                    path: "/account",
                    name: "Account",
                    component: Account,
                },
                {
                    path: "/logout",
                    name: "Logout",
                    component: Logout,
                },
            ],
        },
        {
            path: "/:pathMatch(.*)*",
            redirect: { name: "NotFound" },
        },
        {
            path: "/not-found",
            name: "NotFound",
            component: PageNotFound,
        },
        {
            path: "/unauthorized",
            name: "Unauthorized",
            component: Unauthorized,
        },
    ],
});

router.beforeEach((to, from, next) => authGuard(to, next));

export default router;
