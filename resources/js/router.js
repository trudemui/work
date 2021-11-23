import Vue from "vue";
import VueRouter from "vue-router";
 
Vue.use(VueRouter);
 
import Login from "./components/Login/Login.vue";
import Home from "./components/Home/Home.vue";
import HomeAdd from "./components/Home/HomeAdd.vue";
import HomeEdit from "./components/Home/HomeEdit.vue";
import HomePrintProduction from "./components/Home/HomePrintProduction.vue";
import HomePrintJasControls from "./components/Home/HomePrintJasControls.vue";
import Users from "./components/Users/Users.vue";
import Template from "./components/Template/Template.vue";
import TemplateAdd from "./components/Template/TemplateAdd.vue";
import TemplateEdit from "./components/Template/TemplateEdit.vue";
import Production from "./components/Production/Production.vue";
import ProductionAdd from "./components/Production/ProductionAdd.vue";
import ProductionEdit from "./components/Production/ProductionEdit.vue";
import ProductionPreview from "./components/Production/ProductionPreview.vue";
import Jas from "./components/Jas/Jas.vue";
import JasAdd from "./components/Jas/JasAdd.vue";
import JasEdit from "./components/Jas/JasEdit.vue";
import JasPreview from "./components/Jas/JasPreview.vue";
import NotFound from "./components/Error/NotFound.vue";
 
const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "login",
            component: Login
        },
        {
            path: "/home",
            name: "home",
            component: Home,
            meta: { requiresAuth: true }
        },
        {
            path: "/home/add",
            name: "homeAdd",
            component: HomeAdd,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/home/edit",
            name: "homeEdit",
            component: HomeEdit,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/home/homePrintProduction",
            name: "homePrintProduction",
            component: HomePrintProduction,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/home/homePrintJasControls",
            name: "homePrintJasControls",
            component: HomePrintJasControls,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/users",
            name: "users",
            component: Users,
            meta: { requiresAuth: true }
        },
        {
            path: "/template",
            name: "template",
            component: Template,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/template/add",
            name: "templateAdd",
            component: TemplateAdd,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/template/edit",
            name: "templateEdit",
            component: TemplateEdit,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/production/growing_crop/:growing_crop_id",
            name: "production",
            component: Production,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/production/add",
            name: "productionAdd",
            component: ProductionAdd,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/production/growing_crop/:growing_crop_id/production/:production_edit_id/edit",
            name: "productionEdit",
            component: ProductionEdit,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/production/preview",
            name: "productionPreview",
            component: ProductionPreview,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/jas/growing_crop/:growing_crop_id",
            name: "jas",
            component: Jas,
            props: true,
            meta: { requiresAuth: true }
        },        {
            path: "/jas/add",
            name: "jasAdd",
            component: JasAdd,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/jas/growing_crop/:growing_crop_id/jas/:jas_controls_id/edit",
            name: "jasEdit",
            component: JasEdit,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: "/jas/preview",
            name: "jasPreview",
            component: JasPreview,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: '*',
            name: "notFound",
            component: NotFound
        }
    ]
});

// 未ログインの場合は、ログイン画面へ強制遷移
// router.beforeEach((to, from, next) => {
//     if(to.name === "notFound") {
//         console.log("beforeEach: 404 NotFound!!");
//         state.isNotFound = true;
//     }
//     next();
//     if (to.matched.some(record => record.meta.requiresAuth)) {
//         if (state.isLogin === false) {
//             next({
//                 path: "/",
//                 query: { redirect: to.fullPath }
//             })
//         } else {
//             next()
//         }
//     } else {
//         next();
//     }
// });

export default router;