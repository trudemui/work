<template>
    <div class="globalMenuSp fixed hidden xl:block w-80 h-full bg-blue-normal text-white-normal font-bold shadow-2xl xl:shadow-none"
        :class="{ active: activeNav }"
        v-if="this.$route.path !== '/' 
            && this.$route.path !== '/production/preview' 
            && this.$route.path !== '/jas/preview'
            && this.$route.path !== '/home/homePrintProduction'
            && this.$route.path !== '/home/homePrintJasControls'
            && !isNotFound"
    >
        <div class="flex mt-12 ml-5">
            <img :src="'/img/human.png'">
            <div class="flex flex-col justify-center ml-2 mb-1">
                <p class="text-lg">{{ newProducer }}</p>
                <hr class="w-40">
                <p>{{ newLoginId }}</p>
            </div>
        </div>
        <div class="mt-12 ml-8">
            <ul class="text-lg">
                <li>
                    <router-link to="/home" @click.native="scrollToTop">
                        <button class="flex mb-10 focus:outline-none focus:shadow-outline">
                            <img :src="'/img/home.png'" class="mr-3">
                            <p class="mt-1 font-bold">ホーム</p>
                        </button>
                    </router-link>
                </li>
                <li>
                    <router-link to="/template" @click.native="scrollToTop">
                        <button class="flex mb-10 focus:outline-none focus:shadow-outline">
                            <img :src="'/img/plus.png'" class="mr-3">
                            <p class="mt-0.5 font-bold">テンプレート登録</p>
                        </button>
                    </router-link>
                </li>
                <li>
                    <router-link to="/users" @click.native="scrollToTop">
                        <button class="flex mb-10 focus:outline-none focus:shadow-outline">
                            <img :src="'/img/secure.png'" class="mr-3">
                            <p class="mt-1 font-bold">ユーザー管理</p>
                        </button>
                    </router-link>
                </li>
                <li>
                    <button class="flex mb-10 focus:outline-none focus:shadow-outline" @click="logout">
                        <img :src="'/img/arrow.png'" class="mr-3">
                        <p class="mt-0.5 font-bold">ログアウト</p>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: ["activeNav"],
    data: function() {
        return {
            userDatas: {},
            newProducer: "",
            newLoginId: ""
        }
    },
    computed: {
        //404判定
        isNotFound() {
            console.log("Sidebar: isNotFound");
            return this.$store.state.isNotFound;
        }
    },
    created() {
        console.log("Sidebar: created");
        console.log("Sidebar: created", this.$route.path);
    },
    mounted() {
        console.log("Sidebar: mounted");
        if(state.isLogin) {
            axios
                .get("/api/user")
                .then(response => {
                    console.log("Users: api users OK");
                    this.userDatas = response.data;
                    this.newProducer = this.userDatas.producer;
                    this.newLoginId = this.userDatas.login_id;
                })
                .catch(error => {
                    console.error("Users: api users NG");
                });
        }
    },
    methods: { 
        //ログイン画面へ遷移
        logout() {
            console.log("Sidebar: logout");
            axios
                .post("/api/auth/logout")
                .then(res => {
                    axios.defaults.headers.common["Authorization"] = "";
                    state.isLogin = false;
                    this.$router.push({path: "/"});
                });
        }
    }
}
</script>
