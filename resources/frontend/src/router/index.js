import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import {UserStore} from "../store";
import EditFilm from "./../views/Films/EditFilm.vue";
import InternalView from "./../views/InternalView.vue";


const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView,
    },
    {
        path: "/internal",
        component: InternalView,
        children: [
            {
                path: "create",
                component: EditFilm,
                meta: {needAuth: true}
            }
        ]
    }
];


export const createRouterInstance = (app) => {
    const router = createRouter({
        history: createWebHistory(import.meta.env.BASE_URL),
        routes: routes
    });
    router.beforeEach(async (to, from) => {
        const storage = UserStore();
        console.log("checkUser", storage.user);
        if (!to.meta.needAuth || storage.user) {
            return true;
        }
        await storage.loadUser(app.config.globalProperties.$axios);
        console.log("userLoaded", storage.user);
        return storage.user ? true: {name: "home"};
    });
    return router;
}




