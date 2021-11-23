<template>
    <div class="relative" style="margin-left: 0rem; margin-top: 0rem; background-color: #ffffff;">
        <div class="flex flex-wrap justify-center inset-center w-full ">
            <div class="flex flex-col w-100 sm:w-full md:w-1/2">
                <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
                    <p class="text-center text-xl sm:text-2xl font-medium">Una 有機書類作成システム</p>
                    <div class="flex flex-col items-center sm:items-stretch pt-3 md:pt-8">
                        <div class="flex flex-col w-full pt-4">
                            <label for="email" class="text-lg">ログインID</label>
                            <input type="text" id="email" placeholder="your@email.com" class="shadow-md appearance-none rounded w-full h-10 py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" v-model="loginId">
                            <p v-show="isError" class="pt-2 text-red-normal text-sm">ログインIDが間違っています。</p>
                        </div>
                        <div class="flex flex-col w-full pt-4">
                            <label for="password" class="text-lg">パスワード</label>
                            <input type="password" id="password" placeholder="Password" class="shadow-md appearance-none rounded w-full h-10 py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" v-model="password">
                            <p v-show="isError" class="pt-2 text-red-normal text-sm">パスワードが間違っています。</p>
                        </div>
                        <button class="w-full h-12 p-2 mt-10 bg-blue-sky text-white-normal text-lg focus:outline-none focus:shadow-outline" @click="login">ログイン</button>
                    </div>
                </div>
            </div>
            <div class="w-1/2 shadow-lg bg-blue-sky h-screen hidden md:block">
                <!-- <img :src="'/img/background.jpg'" class="object-cover w-full h-screen hidden md:block"> -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    data: function() {
        return {
            loginId: "",
            password: "",
            isError: false,
        }
    },
    computed: { 
        //404判定
        isNotFound() {
            console.log("Login: isNotFound");
            return this.$store.state.isNotFound;
        }
    },
    created() {
        console.log("Login: created");
        state.isNotFound = false;
    },
    methods: { 
        //ログイン機能
        login() {
            console.log("Login: login");
            axios
                .post("/api/auth/login", { login_id: this.loginId, password: this.password })
                .then(response => {
                    console.log("Login: api login OK");
                    const token = response.data.access_token;
                    axios.defaults.headers.common["Authorization"] = "Bearer " + token;
                    state.isLogin = true;
                    this.$router.push("/home");
                }).catch(error => {
                    console.log("Login: api login NG");
                    this.isError = true;
                });
        }
    }
}
</script>