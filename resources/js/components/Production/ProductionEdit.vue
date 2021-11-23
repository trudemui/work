<template>
    <div v-if="axiosStatus === true" class="flex justify-center pb-12" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <div class="flex justify-center items-center h-14 bg-blue-normal">
                    <h3 class="text-xl sm:text-2xl font-bold text-white-normal">生産行程管理記録 編集</h3>
                </div>
                <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                    <ul class="w-full flex flex-col items-center">
                        <li class="w-full sm:w-auto mb-8">
                            <p>作業月日<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <v-datepicker
                                    class="customPicker w-full sm:w-110 md:w-120 lg:w-130 h-10 pt-1.5 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline"
                                    v-model="productDatas.work_date"
                                    :format="DatePickerFormat"
                                    :language="ja"
                                    @closed="pickerClosed"
                                />
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>作物名</p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="productDatas.growing_crop.variety.crop.crop_name">
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>品種名</p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="productDatas.growing_crop.variety.variety_name">
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>作業内容<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <v-select
                                    id="work_content_id"
                                    class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md"
                                    label="body"
                                    :options="options1"
                                    v-model="productDatas.work_content_id"
                                    @input="changeInput('work_content_id')"
                                >
                                    <span slot="no-options">検索候補がありません。</span>
                                </v-select>
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>種苗・資材名</p>
                            <v-select
                                id="seed_id"
                                class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md"
                                label="body"
                                :options="options2"
                                v-model="productDatas.seed_id"
                                @input="changeInput('seed_id')"
                            >
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>数量</p>
                            <input
                                type="text"
                                class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline"
                                oninput="value = value.replace(/[^0-9]+/i,'');"
                                v-model="productDatas.seed_volume"
                            />
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>機械・器具名</p>
                            <v-select
                                id="equipments_id"
                                class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md"
                                label="body"
                                :options="options3"
                                v-model="productDatas.equipments_id"
                                @input="changeInput('equipments_id')"
                            >
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>収納・洗浄方法</p>
                            <v-select
                                id="equip_method_id"
                                class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md"
                                label="body"
                                :options="options4"
                                v-model="productDatas.equip_method_id"
                                @input="changeInput('equip_method_id')"
                            >
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>その他</p>
                            <input
                                type="text"
                                class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline"
                                v-model="productDatas.others"
                            />
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>収穫後における管理</p>
                            <v-select
                                id="after_harvest_id"
                                class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md"
                                label="body"
                                :options="options5"
                                v-model="productDatas.after_harvest_id"
                                @input="changeInput('after_harvest_id')"
                            >
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
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
import { ja } from "vuejs-datepicker/dist/locale"
import vDatepicker from "vuejs-datepicker"
import moment from "moment";
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css";
import "./Production.vue";

extend("select_required", {
    ...required,
    message: "入力必須項目です。",
});

export default {
    components: {
        "v-select": vSelect,
        "v-datepicker": vDatepicker,
        "validation-provider": ValidationProvider,
        "validation-observer": ValidationObserver
    },
    props:{
        "growing_crop_id": {
            required: false,
            default: null
        },
        "production_edit_id": {
            required: false,
            default: null
        },
    },
    data: function() {
        return {
            productDatas: {},
            updatedSelected1: null,
            updatedSelected2: null,
            updatedSelected3: null,
            updatedSelected4: null,
            updatedSelected5: null,
            updatedSelected_date: null,
            updatedVolume: null,
            options1: [],
            options2: [],
            options3: [],
            options4: [],
            options5: [],
            DatePickerFormat: "M月d日",
            ja: ja,
            axiosStatus: false
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("ProductionEdit: created");
        let values = {
            id: this.production_edit_id
        }
        axios
            .post("/api/productions/find_data", values)
            .then(response => {
                console.log('ProductionEdit: api find_data OK');
                const product = response.data.product;
                this.productDatas = product;
                this.axiosStatus = true;
            })
            .catch(error => {
                console.log("ProductionEdit: api find_data NG");
            })
        axios
            .get("/api/productions/get_selectbox")
            .then(response => {
                console.log("ProductonEdit: api get_selectbox OK");
                //作業内容
                const select1 = response.data.select1;
                let values = [];
                    select1.forEach(function(data) {
                        values.push({
                            id: data.id,
                            body: data.body
                        })
                    })
                this.options1 = values;
                console.log(this.options1);
                //種苗・資源名
                const select2 = response.data.select2;
                let values2 = [];
                    select2.forEach(function(data) {
                        values2.push({
                            id: data.id,
                            body: data.body
                        })
                    })
                this.options2 = values2;
                //機械・器具名
                const select3 = response.data.select3;
                let values3 = [];
                    select3.forEach(function(data) {
                        values3.push({
                            id: data.id,
                            body: data.body
                        })
                    })
                this.options3 = values3;
                //収納・洗浄方法
                const select4 = response.data.select4;
                let values4 = [];
                    select4.forEach(function(data) {
                        values4.push({
                            id: data.id,
                            body: data.body
                        })
                    })
                this.options4 = values4;
                //収穫後における管理
                const select5 = response.data.select6;
                let values5 = [];
                    select5.forEach(function(data) {
                        values5.push({
                            id: data.id,
                            body: data.body
                        })
                    })
                this.options5 = values5;
            })
            .catch(error => {
                console.log("ProductonEdit: api get_selectbox NG");
            })
    },
    methods: { 
        //更新
        updateDatas() {
            console.log("ProductionEdit: updateDatas");
            let values = {
                id: this.production_edit_id,
                work_date: this.productDatas.work_date,
                work_content_id: this.productDatas.work_content_id,
                seed_id: this.productDatas.seed_id,
                seed_volume: this.productDatas.seed_volume,
                equipments_id: this.productDatas.equipments_id,
                equip_method_id: this.productDatas.equip_method_id,
                others: this.productDatas.others,
                after_harvest_id: this.productDatas.after_harvest_id
            }
            console.log(values);
            axios
                .post("/api/productions/update", values)
                .then(response => {
                    console.log("ProductionEdit: api update OK");
                    this.$router.push({
                        name: "production", 
                        params: { growing_crop_id: this.growing_crop_id }
                    });
                    this.scrollToTop();
                })
                .catch(error => {
                    console.error("ProductionEdit: api update NG");
                });
        },
        //前の画面へ戻る
        returnPage() {
            console.log("ProductionEdit: returnPage");
            this.$router.push({
                name: "production", 
                params: { selectedDatas: this.selectedDatas }
            });
            this.scrollToTop();
        },
        //セレクトボックスの状態操作
        changeInput(id) {
            console.log("ProductionEdit: changeInput");
            console.log(id);
            if(id == document.getElementById("work_content_id").id) {
                //データ取得
                if(this.productDatas.work_content_id !== null) {
                    if(this.productDatas.work_content_id.length > 1) {
                        this.productDatas.work_content_id.shift();
                    }
                    //オブジェクトから単一のデータに変換する
                    if(this.productDatas.work_content_id.length !== 0) {
                        this.productDatas.work_content_id = this.productDatas.work_content_id.body
                    }
                }
            } else if (id == document.getElementById("seed_id").id) {
                if(this.productDatas.seed_id !== null) {
                    if(this.productDatas.seed_id.length > 1) {
                        this.productDatas.seed_id.shift();
                    }
                    if(this.productDatas.seed_id.length !== 0) {
                        this.productDatas.seed_id = this.productDatas.seed_id.body
                    }
                }
            } else if (id == document.getElementById("equipments_id").id) {
                if(this.productDatas.equipments_id !== null) {
                    if(this.productDatas.equipments_id.length > 1) {
                        this.productDatas.equipments_id.shift();
                    }
                    if(this.productDatas.equipments_id.length !== 0) {
                        this.productDatas.equipments_id = this.productDatas.equipments_id.body
                    }
                }
            } else if (id == document.getElementById("equip_method_id").id) {
                if(this.productDatas.equip_method_id !== null) {
                    if(this.productDatas.equip_method_id.length > 1) {
                        this.productDatas.equip_method_id.shift();
                    }
                    if(this.productDatas.equip_method_id.length !== 0) {
                        this.productDatas.equip_method_id = this.productDatas.equip_method_id.body
                    }
                }
            } else if (id == document.getElementById("after_harvest_id").id) {
                if(this.productDatas.after_harvest_id !== null) {
                    if(this.productDatas.after_harvest_id.length > 1) {
                        this.productDatas.after_harvest_id.shift();
                    }
                    if(this.productDatas.after_harvest_id.length !== 0) {
                        this.productDatas.after_harvest_id = this.productDatas.after_harvest_id.body
                    }
                }
            }
        },
        //日付のフォーマット
        pickerClosed() {
            console.log("ProductionEdit: pickerClosed");
            this.productDatas.work_date = moment(this.productDatas.work_date).format("YYYY/MM/DD");
        }
    }
}
</script>
