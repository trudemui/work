<template>
    <div class="flex justify-center pb-12" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center h-14 bg-blue-normal">
                <h3 class="text-xl sm:text-2xl font-bold text-white-normal">ユーザー管理</h3>
            </div>
            <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                <img :src="'/img/human2.png'">
                <div class="flex flex-col justify-center ml-4 mb-1">
                    <p class="text-xl mb-1">{{userDatas.producer}}</p>
                    <p class="ml-0.5 text-lg">{{userDatas.login_id}}</p>
                </div>
            </div>
            <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                <ul class="w-full flex flex-col items-center">
                    <li class="w-full sm:w-auto mb-8">
                        <p>ユーザー名<span class="ml-1 text-red-normal">※</span></p>
                        <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" :placeholder="userDatas.producer" v-model="newProducer">
                        <p v-show="isProducer" class="pt-2 text-red-normal text-sm">この項目は入力が必須です。</p>
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>ログインID<span class="ml-1 text-red-normal">※</span></p>
                        <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" :placeholder="userDatas.login_id" v-model="newLoginId">
                        <p v-show="isLoginId" class="pt-2 text-red-normal text-sm">この項目は入力が必須です。</p>
                        <p v-show="isErrorLoginId" class="pt-2 text-red-normal text-sm">半角英数字4文字以上で入力してください。</p>
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>パスワード<span class="ml-1 text-red-normal">※</span></p>
                        <input type="password" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" placeholder="********" v-model="newPassword">
                        <p v-show="isNewPassword" class="pt-2 text-red-normal text-sm">入力されたパスワードが一致しません。</p>
                        <p v-show="isErrorPassword" class="pt-2 text-red-normal text-sm">半角英数字8文字以上で入力してください。</p>
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>パスワード再確認<span class="ml-1 text-red-normal">※</span></p>
                        <input type="password" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" placeholder="********" v-model="confirmPassword">
                    </li>
                </ul>
            </div>
            <div class="flex justify-center items-center sm:items-stretch mt-8 mb-16 mx-5 sm:mx-0">
                <button class="w-full sm:w-40 h-12 mr-2 sm:mr-10 text-lg sm:text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="returnPage">戻る</button>
                <button class="w-full sm:w-40 h-12 ml-2 sm:ml-10 text-lg sm:text-xl bg-red-normal text-white-normal focus:outline-none focus:shadow-outline" @click="updateDatas">更新</button>
            </div>
            <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    data: function() {
        return {
            userDatas: {},
            newProducer: "",
            newLoginId: "",
            newPassword: "",
            confirmPassword: "",
            isProducer: false,
            isLoginId: false,
            isErrorLoginId: false,
            isNewPassword: false,
            isErrorPassword: false,
            isConfirmPassword: false
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            return this.$store.state.isActiveSidebar;
        }
    },
    mounted() {
        console.log("Users: created");
        this.getDatas();
    },
    methods: {
        //データ取得
        getDatas() {
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
        },
        //更新
        updateDatas() {
            console.log("Users: updateDatas");
            let errorProducer = false;
            let errorLoginId1 = false;
            let errorLoginId2 = false;
            let errorPassword1 = false;
            let errorPassword2 = false;
            let errorConfirm = false;
            if(this.newProducer === "") {
                this.isProducer = true;
                errorProducer = true;
            } else {
                this.isProducer = false;
                errorProducer = false;
            }
            if(this.newLoginId === "") {
                this.isLoginId = true;
                errorLoginId1 = true;
            } else {
                this.isLoginId = false;
                errorLoginId1 = false;
            }
            if(!this.newLoginId.match(/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,16}$/i)) {
                this.isErrorLoginId = true;
                errorLoginId2 = true;
            } else {
                this.isErrorLoginId = false;
                errorLoginId2 = false;
            }
            if(this.newPassword === "") {
                this.isNewPassword = true;
                errorPassword1 = true;
            } else {
                this.isNewPassword = false;
                errorPassword1 = false;
            }
            if(!this.newPassword.match(/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,16}$/i)) {
                this.isErrorPassword = true;
                errorPassword2 = true;
            } else {
                this.isErrorPassword = false;
                errorPassword2 = false;
            }
            if(this.confirmPassword === "" || this.newPassword !== this.confirmPassword) {
                this.isNewPassword = true;
                errorConfirm = true;
            } else {
                this.isNewPassword = false;
                errorConfirm = false;
            }
            if(errorProducer === false && errorLoginId1 === false && errorLoginId2 === false && errorPassword1 === false && errorPassword2 === false && errorConfirm === false) {
                let params = {
                    id: this.userDatas.id,
                    producer: this.newProducer,
                    loginId: this.newLoginId,
                    password: this.newPassword
                };
                axios
                    .put("/api/users/update", params)
                    .then(res => {
                        console.log("Users: api users update OK");
                        this.getDatas();
                        this.confirmPassword = "";
                        if (!alert("情報が正しく更新されました！")) {
                            this.scrollToTop();
                            axios
                                .post("/api/auth/logout")
                                .then(res => {
                                    axios.defaults.headers.common["Authorization"] = "";
                                    state.isLogin = false;
                                    this.$router.push({path: "/"});
                                });
                        };
                    })
                    .catch(err => {
                        console.error("Users: api users update NG");
                    })
            } else {
                this.confirmPassword = "";
                alert("正しい情報を入力してください。");
            }
        },
        //前の画面へ戻る
        returnPage() {
            console.log("Users: returnPage");
            this.$router.push("/home");
            this.scrollToTop();
        }
    }
}
</script>
