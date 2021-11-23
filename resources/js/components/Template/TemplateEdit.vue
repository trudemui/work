<template>
    <div class="flex justify-center pb-12" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <div class="flex justify-center items-center h-14 bg-blue-normal">
                    <h3 class="text-xl sm:text-2xl font-bold text-white-normal">テンプレート 編集</h3>
                </div>
                <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                    <ul class="w-full flex flex-col items-center">
                        <li class="w-full sm:w-auto mb-8">
                            <p>分類</p>
                            <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded pointer-events-none focus:outline-none focus:shadow-outline" :value="selectedDatas.class">
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>項目</p>
                            <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded pointer-events-none focus:outline-none focus:shadow-outline" :value="selectedDatas.article.category_name">
                        </li>
                        <li class="w-full sm:w-auto mb-8" v-if="selectedDatas.article.category_name === '作物名・品種名'">
                            <p>作物名<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <v-select
                                    id="crop_id"
                                    class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md focus:outline-none focus:shadow-outline"  
                                    label="crop_name"
                                    :options="options1"
                                    v-model="selected1"
                                    @input="changeInput('crop_id')"
                                    taggable
                                    multiple 
                                >
                                    <span slot="no-options">検索候補がありません。</span>
                                </v-select>
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p v-if="selectedDatas.article.category_name === '作物名・品種名'">品種名<span class="ml-1 text-red-normal">※</span></p>
                            <p v-else>内容<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <input v-if="selectedDatas.article.id === 7" type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" v-model="selectedDatas.name2">
                                <input v-else-if="selectedDatas.article.id !== 7" type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-2 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" v-model="selectedDatas.name1">
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                    </ul>
                </div>
                <div class="flex justify-center items-center sm:items-stretch mt-8 mb-16 mx-5 sm:mx-0">
                    <button class="w-full sm:w-40 h-12 mr-2 sm:mr-10 text-lg sm:text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="returnPage">戻る</button>
                    <button class="w-full sm:w-40 h-12 ml-2 sm:ml-10 text-lg sm:text-xl bg-red-normal text-white-normal focus:outline-none focus:shadow-outline" @click="handleSubmit(updateDatas)">更新</button>
                </div>
                <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
            </validation-observer>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required } from "vee-validate/dist/rules";
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css";

extend("select_required", {
    ...required,
    message: "入力必須項目です。",
});

export default {
    components: {
        "v-select": vSelect,
        "validation-provider": ValidationProvider,
        "validation-observer": ValidationObserver
    },
    props:{
        "selectedDatas": {
            required: false,
            default: null
        },
        "selectedId": {
            required: false,
            default: null
        }
    },
    data: function() {
        return {
            selected1: [],
            options1: [],
            values: [],
            test: []
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("TemplateEdit: created");
        axios
            .get("/api/crops")
            .then(response => {
                console.log("TemplateEdit: api created OK");
                //作物名取得
                const crops = response.data.crops;
                let values = [];
                crops.forEach(function(data) {
                    values.push({
                        id: data.id,
                        crop_name: data.crop_name
                    })
                })
                this.options1 = values;
                this.selected1 = this.selectedDatas.name3;
            })
            .catch(error => {
                console.log("TemplateEdit: api created NG");
            });
    },
    methods: { 
        //更新
        updateDatas() {
            console.log("TemplateEdit: updateDatas");
            if(this.selectedDatas.article.id === 7) {
                this.values.push({
                    id: this.selectedDatas.id,
                    number: 1,
                    crop_name: this.selected1,
                    variety_name: this.selectedDatas.name2
                })
            } else if(this.selectedDatas.article.id !== 7) {
                this.values.push({
                    id: this.selectedDatas.id,
                    number: 0,
                    body: this.selectedDatas.name1
                })
            }
            axios
                .post(`/api/templates/update`, this.values[0])
                .then(response => {
                    console.log("TemplateEdit: api update OK");
                    this.$router.push({
                        name: "template", 
                        params: { 
                            selectedDatas: this.selectedDatas,
                            selectedId: this.selectedId
                        }
                    });
                })
                .catch(error => {
                    console.log("TemplateEdit: api update NG");
                });
        },
        //前の画面へ戻る
        returnPage() {
            console.log("TemplateEdit: returnPage");
            this.$router.push({
                name: "template", 
                params: { 
                    selectedDatas: this.selectedDatas,
                    selectedId: this.selectedId
                }
            });
            this.scrollToTop();
        },
        //セレクトボックスの状態操作
        changeInput(id) {
            console.log("TemplateEdit: changeInput");
            if(id == document.getElementById("crop_id").id) {
                //データ取得
                if(this.selected1.length > 1) {
                    this.selected1.shift();
                }
                //オブジェクトから単一のデータに変換する
                if(this.selected1.length !== 0) {
                    this.selected1 = this.selected1[0].crop_name
                }
            }
        }
    }
}
</script>
