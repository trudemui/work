import bootstrap from "./bootstrap";
import Header from "./components/Layouts/Header";
import Sidebar from "./components/Layouts/Sidebar";
import utility from "./mixins/utility";

//storeの簡易呼び出し
window.Vue = require("vue");
window.state = store.state;

Vue.component("header-component", Header);
Vue.component("sidebar-component", Sidebar);

import Vue from "vue";
import router from "./router";
import store from "./store/index";
import VModal from "vue-js-modal";

Vue.use(VModal);
Vue.mixin(utility);

const app = new Vue({
    el: "#app",
    router: router,
    store: store
});