<template>
    <div v-if="this.$route.path !== '/'
        && this.$route.path !== '/home/homePrintProduction'
        && this.$route.path !== '/home/homePrintJasControls'"
    >
        <div class="flex justify-between xl:justify-start items-center h-12 px-6 xl:px-0 bg-blue-light">
            <router-link to="/home" @click.native="scrollToTop">
                <div v-if="!isNotFound" class="flex items-center">
                    <h3 class="ml-0 xl:ml-14 text-blue-dark text-lg sm:text-xl font-bold">Una</h3>
                    <p class="ml-2 mini:ml-1 text-blue-dark text-base sm:text-lg font-bold">有機書類作成システム</p>
                </div>
            </router-link>
            <div v-if="this.$route.path !== '/production/preview'
                && this.$route.path !== '/jas/preview'
                && this.$route.path !== '/home/homePrintProduction'
                && this.$route.path !== '/home/homePrintJasControls'" class="block xl:hidden -mt-2 text-blue-dark text-2xl mini:text-xl font-bold">
                <div class="hamburger" :class="{ active: activeNav, 'is-hidden': isActiveModal }" @click="activeNavClass">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        <Sidebar :activeNav="activeNav"></Sidebar>
    </div>
</template>

<script>
import Sidebar from "./Sidebar";

export default {
    components: {
        Sidebar
    },
    data: function() {
        return {
            activeNav: false,
            currentPath: this.$route.path
        }
    },
    computed: { 
        //モーダルウインドウ判定
        isActiveModal() {
            console.log("Header: isActiveModal");
            return this.$store.state.isActiveModal;
        },
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("Header: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        },
        //404判定
        isNotFound() {
            console.log("Header: isNotFound");
            return this.$store.state.isNotFound;
        }
    },
    watch: {
        //VueRouterの画面遷移を検出
        "$route": function (to, from) {
            console.log("Header Watch: $route")
            if (this.activeNav === true) {
                this.activeNav = false;
            }
            if (this.$store.state.isActiveSidebar === true) {
                this.$store.commit("SidebarStatus", false);
            }
        }
    },
    methods: { 
        //ヘッダー表示切り替え
        activeNavClass() {
            console.log("Header: activeNavClass");
            if (this.activeNav === false) {
                this.activeNav = true;
                this.$store.commit("SidebarStatus", true);
            } else {
                this.activeNav = false;
                this.$store.commit("SidebarStatus", false);
            }
        }
    }
}
</script>
