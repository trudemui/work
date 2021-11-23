<template>
    <div v-if="axiosStatus === true" class="flex justify-center pb-12" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <div class="flex justify-center items-center h-14 bg-blue-normal">
                    <h3 class="text-xl sm:text-2xl font-bold text-white-normal">生産行程管理記録 新規登録</h3>
                </div>
                <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                    <ul class="w-full flex flex-col items-center">
                        <li class="w-full sm:w-auto mb-8">
                            <p>作業月日<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <v-datepicker
                                    class="customPicker w-full sm:w-110 md:w-120 lg:w-130 h-10 pt-1.5 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline"
                                    v-model="selected_date"
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
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].variety.crop.crop_name">
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>品種名</p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].variety.variety_name">
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>作業内容<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="body" :options="options1" v-model="selected1">
                                    <span slot="no-options">検索候補がありません。</span>
                                </v-select>
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>種苗・資材名</p>
                            <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="body" :options="options2" v-model="selected2">
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>数量</p>
                            <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" oninput="value = value.replace(/[^0-9]+/i,'');" v-model="volume"/>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>機械・器具名</p>
                            <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="body" :options="options3" v-model="selected3">
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>収納・洗浄方法</p>
                            <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="body" :options="options4" v-model="selected4">
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>その他</p>
                            <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" v-model="others"/>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>収穫後における管理</p>
                            <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="body" :options="options5" v-model="selected5">
                                <span slot="no-options">検索候補がありません。</span>
                            </v-select>
                        </li>
                    </ul>
                </div>
                <div class="flex justify-center items-center sm:items-stretch mt-8 mb-16 mx-5 sm:mx-0">
                    <button class="w-full sm:w-40 h-12 mr-2 sm:mr-10 text-lg sm:text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="returnPage">戻る</button>
                    <button class="w-full sm:w-40 h-12 ml-2 sm:ml-10 text-lg sm:text-xl bg-red-normal text-white-normal focus:outline-none focus:shadow-outline" @click="handleSubmit(addDatas)">登録</button>
                </div>
                <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
            </validation-observer>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required } from "vee-validate/dist/rules";
import { ja } from "vuejs-datepicker/dist/locale";
import vDatepicker from "vuejs-datepicker";
import moment from "moment";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

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
    },
    data: function() {
        return {
            selected1: null,
            selected2: null,
            selected3: null,
            selected4: null,
            selected5: null,
            selected_date: null,
            volume: null,
            others: null,
            options1: [],
            options2: [],
            options3: [],
            options4: [],
            options5: [],
            g_cropData: {},
            DatePickerFormat: "M月d日",
            ja: ja,
            axiosStatus: false,
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("ProductionAdd: created");
        let values = {
            id: this.growing_crop_id
        }
        axios
            .post("/api/growing_crops/find_crop", values)
            .then(response => {
                console.log("Production: api create OK");
                const g_crop = response.data.g_crop;
                this.g_cropData = g_crop;
                this.axiosStatus = true;
            })
            .catch(error => {
                console.log("Production: api create NG");
            })
        axios
            .get("/api/productions/get_selectbox")
            .then(response => {
                console.log("ProductonAdd: api created OK");
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
                const select5 = response.data.select6; //その他を使用しなくなったため 「select5」が必要ない
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
                console.log("ProductonAdd: api created NG");
            })
    },
    methods: {
        //新規登録
        addDatas() {
            console.log("ProductionAdd: addDatas");
            const body1 = this.selected1 !== null ? this.selected1.body : null;
            const body2 = this.selected2 !== null ? this.selected2.body : null;
            const body3 = this.selected3 !== null ? this.selected3.body : null;
            const body4 = this.selected4 !== null ? this.selected4.body : null;
            const body5 = this.selected5 !== null ? this.selected5.body : null;
            let datas = [];
            datas.push({
                value1: body1,
                value2: body2,
                value3: body3,
                value4: body4,
                value5: body5,
                volume: this.volume,
                other: this.others,
                date: this.selected_date,
                growing_crop_id: this.growing_crop_id
            })
            axios
                .post("/api/productions", datas[0])
                .then(response => {
                    console.log("ProductionAdd: api productions OK");
                    this.$router.push({
                        name: "production", 
                        params: { growing_crop_id: this.growing_crop_id }
                    });
                    this.scrollToTop();
                })
                .catch(error => {
                    console.log("ProductionAdd: api productions NG");
                    //422エラーでエラー内容を返したいが、未解決状態
                    alert("作業内容「収穫済み」は既に登録されています。")
                });
        },
        //前の画面へ戻る
        returnPage() {
            console.log("ProductionAdd: returnPage");
            this.$router.push({
                name: "production", 
                params: { growing_crop_id: this.growing_crop_id }
            });
            this.scrollToTop();
        },
        //日付のフォーマット
        pickerClosed() {
            console.log("ProductionAdd: pickerClosed");
            this.selected_date = moment(this.selected_date).format("YYYY/MM/DD");
        }
    }
}
</script>
