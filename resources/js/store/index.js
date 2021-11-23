import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isActiveModal: false,
        isActiveSidebar: false,
        isLogin: false,
        isProduction: false,
        isNotFound: false,
    },
    mutations: {
        modalStatus(state, isActiveModal) {
            state.isActiveModal = isActiveModal;
        },
        SidebarStatus(state, isActiveSidebar) {
            state.isActiveSidebar = isActiveSidebar;
        },
        notFoundStatus(state, isNotFound) {
            state.isNotFound = isNotFound;
        }
    }
});
