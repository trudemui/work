<template>
    <div v-if="axiosStatus === true" class="flex justify-center pb-12" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <div class="flex justify-center items-center h-14 bg-blue-normal">
                    <h3 class="text-xl sm:text-2xl font-bold text-white-normal">ホーム 編集</h3>
                </div>
                <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                    <ul class="w-full flex flex-col items-center">
                        <li class="w-full sm:w-auto mb-8">
                            <validation-provider class="flex flex-col" rules="select_required" v-slot="{ errors }">
                                <p>年月<span class="ml-1 text-red-normal">※</span></p>
                                <v-datepicker
                                    class="customPicker w-full sm:w-110 md:w-120 lg:w-130 h-10 pt-1.5 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline"
                                    v-model="g_cropData.starting_date"
                                    :format="DatePickerFormat"
                                    :language="ja"
                                    :initial-view="'year'"
                                    :minimum-view="'month'"
                                    :maximum-view="'year'"
                                    @closed="pickerClosed"
                                />
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>圃場<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md appearance-none rounded" label="farm_number" :options="farmOptions" v-model="g_cropData.farm.farm_number">
                                    <span slot="no-options">検索候補がありません。</span>
                                </v-select>
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>栽培面積<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider rules="select_required" v-slot="{ errors }" class="flex flex-col">
                                <input type="text" class="w-full sm:w-110 md:w-120 lg:w-130 h-10 px-3 py-1 shadow-md appearance-none rounded truncate focus:outline-none focus:shadow-outline" oninput="value = value.replace(/[^0-9]+/i,'');" v-model="g_cropData.cultivation_area">
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>作物名<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md appearance-none rounded" label="crop_name" :options="cropOptions" v-model="g_cropData.variety.crop.crop_name" @input="find_varieties">
                                    <span slot="no-options">検索候補がありません。</span>
                                </v-select>
                                <span id="error" class="mt-1 text-red-normal">{{ errors[0] }}</span>
                            </validation-provider>
                        </li>
                        <li class="w-full sm:w-auto mb-8">
                            <p>品種名<span class="ml-1 text-red-normal">※</span></p>
                            <validation-provider rules="select_required" v-slot="{ errors }">
                                <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md appearance-none rounded" label="variety_name" :options="varietyOptions" v-model="g_cropData.variety.variety_name" @input="delete_varieties">
                                    <span slot="no-options">検索候補がありません。</span>
                                </v-select>
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
        "validation-observer": ValidationObserver,
    },
    props:{
        "growing_crop_id": {
            required: false,
            default: null
        },
    },
    data: function() {
        return {
            cropOptions: [],
            farmOptions: [],
            varietyOptions: [],
            g_cropData: {},
            DatePickerFormat: "yyyy年M月",
            ja: ja,
            axiosStatus: false,
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("HomeEdit: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("HomeEdit: created");
        let values = {};
        values = { id: this.growing_crop_id };

        axios
            .post("/api/growing_crops/show", values)
            .then(response => {
                console.log("Home: api show OK");
                const g_crop = response.data.g_crop;
                this.g_cropData = g_crop;
                this.axiosStatus = true;
            })
            .catch(error => {
                console.log("Home: api show NG");
            });

        axios
            .get("/api/growing_crops/get_list")
            .then(response => {
                console.log("HomeEdit: api get_list OK");
                //作物名
                const crops = response.data.crops;
                let values1 = [];
                    crops.forEach(function(data) {
                        values1.push({
                            id: data.id,
                            crop_name: data.crop_name
                        })
                    })
                this.cropOptions = values1;

                //圃場
                const farms = response.data.farms;
                let values2 = [];
                    farms.forEach(function(data) {
                        values2.push({
                            id: data.id,
                            farm_number: data.farm_number
                        })
                    })
                this.farmOptions = values2;

                //品種名
                let params = { id: this.g_cropData.variety.crop.id };
                axios
                    .post(`/api/varieties/find_varieties`, params)
                    .then(response => {
                        console.log("Home: api find_varieties OK");
                        const varieties = response.data.varieties;
                        let values3 = [];
                            varieties.forEach(function(data) {
                                values3.push({
                                    id: data.id,
                                    variety_name: data.variety_name
                                })
                            })
                        this.varietyOptions = values3;
                    })
                    .catch(error => {
                        console.log("Home: api find_varieties NG");
                    });
            })
            .catch(error => {
                console.log("HomeEdit: api get_list NG");
            })
    },
    methods: { 
        //作物名に紐づいた品種名を取得
        find_varieties() {
            console.log("HomeEdit: find_varieties");
            if (this.g_cropData.variety.crop.crop_name !== null) {
                let value = { id: this.g_cropData.variety.crop.crop_name.id };

                axios
                    .post("/api/varieties/find_varieties", value)
                    .then(response => {
                        console.log("HomeEdit: api find_varieties OK");
                        const varieties = response.data.varieties;
                        let values3 = [];
                            varieties.forEach(function(data) {
                                values3.push({
                                    id: data.id,
                                    variety_name: data.variety_name
                                })
                            })
                        this.g_cropData.variety.variety_name = "";
                        this.varietyOptions = values3;
                        console.log(this.varietyOptions);
                    })
                    .catch(error => {
                        console.log("HomeEdit: find_varieties NG");
                    });
            } else {
                console.log("HomeEdit: find_varieties options delete!");
                this.g_cropData.variety.variety_name = "";
                this.varietyOptions = [];
            }
        },
        //品種名を削除する際、作物名も削除する
        delete_varieties() {
            console.log("HomeAdd: delete_varieties");
            if (this.g_cropData.variety.crop.crop_name !== null && this.g_cropData.variety.variety_name == null) {
                console.log("HomeAdd: variety delete!");
                this.g_cropData.variety.crop.crop_name = "";
                this.varietyOptions = [];
            }
        },
        //更新
        updateDatas() {
            console.log("HomeEdit: updateDatas");
            let values = {
                id: this.growing_crop_id,
                user_id: 1,
                variety_id: this.g_cropData.variety.variety_name.id,
                starting_date: this.g_cropData.starting_date,
                farm_id: this.g_cropData.farm.farm_number.farm_number,
                cultivation_area: this.g_cropData.cultivation_area
            }
            console.log(values);
            axios
                .post("/api/growing_crops/update", values)
                .then(response => {
                    console.log("HomeEdit: api update OK");
                    this.$router.push({
                        name: "home", 
                        params: { growing_crop_id: this.growing_crop_id }
                    });
                    this.scrollToTop();
                })
                .catch(error => {
                    console.log("HomeEdit: api update NG");
                });
        },
        //日付のフォーマット
        pickerClosed() {
            console.log("HomeEdit: pickerClosed");
            this.g_cropData.starting_date = moment(this.g_cropData.starting_date).format("YYYY/MM/DD");
        },
        //前の画面へ戻る
        returnPage() {
            console.log("HomeEdit: returnPage");
            this.$router.push("/home");
            this.scrollToTop();
        }
    }
}
</script>
