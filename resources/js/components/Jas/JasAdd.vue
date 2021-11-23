<template>
    <div v-if="axiosStatus === true" class="flex justify-center pb-12" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <div class="flex justify-center items-center h-14 bg-blue-normal">
                    <h3 class="text-xl sm:text-2xl font-bold text-white-normal">JASマーク使用報告書 新規登録</h3>
                </div>
                <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                    <ul class="w-full flex flex-col items-center">
                        <li class="w-full sm:w-auto mb-8">
                            <p>作物名</p>
                            <input class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded pointer-events-none" label="crop_name" :value="g_cropData[0].variety.crop.crop_name">
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>品種名</p>
                            <input class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded pointer-events-none" label="crop_name" :value="g_cropData[0].variety.variety_name">
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>格付月日<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <v-datepicker
                                    class="customPicker w-full sm:w-110 md:w-120 lg:w-130 h-10 pt-1.5 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline"
                                    v-model="workDate"
                                    :format="DatePickerFormat"
                                    :language="ja"
                                    @closed="pickerClosed"
                                />
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>包装単位<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" oninput="value = value.replace(/[^0-9]+/i,'');" v-model="packagingUnit" />
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>荷口数<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" oninput="value = value.replace(/[^0-9]+/i,'');" v-model="consignmentsCount" />
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>重量（自動計算）</p>
                            <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline pointer-events-none" oninput="value = value.replace(/[^0-9]+/i,'');" v-model="weight" />
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>出荷先</p>
                            <input class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded pointer-events-none focus:outline-none focus:shadow-outline" label="crop_name" :value="destination.body">
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>使用枚数<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-0.5 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline" oninput="value = value.replace(/[^0-9]+/i,'');" v-model="usedCount" />
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
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
import { ja } from "vuejs-datepicker/dist/locale"
import vDatepicker from "vuejs-datepicker"
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
        "destination": {
            required: false,
            default: null
        },
    },
    data: function() {
        return {
            g_cropData: {},
            packagingUnit: "",
            consignmentsCount: "",
            weight: "",
            destination_id: "",
            usedCount: "",
            workDate: "",
            DatePickerFormat: "M月d日",
            ja: ja,
            axiosStatus: false
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("JasAdd: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    watch: {
        //重量の自動計算
        "packagingUnit": function (to, from) {
            console.log("JasAdd Watch: packagingUnit")
            this.weight = this.packagingUnit * this.consignmentsCount;
        },
        //重量の自動計算
        "consignmentsCount": function (to, from) {
            console.log("JasAdd Watch: consignmentsCount")
            this.weight = this.packagingUnit * this.consignmentsCount;
        },
    },
    created() {
        console.log("JasAdd: created");
        const values = { id: this.growing_crop_id };
        axios
            .post("/api/growing_crops/find_crop", values)
            .then(response => {
                console.log("JasAdd: api find_crop OK");
                const g_crop = response.data.g_crop;
                this.g_cropData = g_crop;
                this.axiosStatus = true;
            })
            .catch(error => {
                console.log("JasAdd: api find_crop NG");
            })
    },
    methods: { 
        //新規登録
        addDatas() {
            console.log("JasAdd: addDatas");
            let values = {};
            values = {
                growing_crop_id: this.growing_crop_id,
                work_date: this.workDate !== null ? this.workDate : null,
                packaging_unit: this.packagingUnit !== null ? this.packagingUnit : null,
                consignments_count: this.consignmentsCount !== null ? this.consignmentsCount : null,
                weight: this.weight !== null ? this.weight : null,
                destination_id: this.destination.destination_id,
                used_count: this.usedCount !== null ? this.usedCount : null
            };

            axios
                .post("/api/jas_controls", values)
                .then(response => {
                    console.log("JasAdd: api jas_controls OK");
                    this.$router.push({
                        name: "jas", 
                        params: { growing_crop_id: this.growing_crop_id }
                    });
                    this.scrollToTop();
                })
                .catch(error => {
                    console.log("JasAdd: api jas_controls NG");
                });
        },
        //日付のフォーマット
        pickerClosed() {
            console.log("JasAdd: pickerClosed");
            this.workDate = moment(this.workDate).format("YYYY/MM/DD");
        },
        //前の画面へ戻る
        returnPage() {
            console.log("JasAdd: returnPage");
            this.$router.push({
                name: "jas", 
                params: { growing_crop_id: this.growing_crop_id }
            });
            this.scrollToTop();
        }
    }
}
</script>
