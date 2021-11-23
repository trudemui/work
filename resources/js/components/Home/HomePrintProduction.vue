<template>
    <div class="flex flex-col justify-center mleft-0 c-homePrint" :class="{ 'is-disabled': isActiveSidebar }">
        <div v-if="axiosStatus === true">
            <div v-for="(item, key) in allDatas" :key="key" class="w-full bg-white-normal c-homePrint__field">
                <div v-for="(item2, key2) in allDatas[key]" :key="key2" class="flex flex-col justify-center mx-5 sm:mx-8 text-lg c-homePrint__field__content">
                    <div class="flex flex-col sm:flex-row justify-center items-center sm:items-stretch w-full sm:w-auto my-10 c-homePrint__field__content__top">
                        <p class="text-lg sm:text-xl font-medium">生産行程管理記録</p>
                        <p class="text-lg sm:text-xl font-medium">（ {{ allDatas[key].work_date | moment2 }} の管理記録 ）</p>
                    </div>
                    <!-- 生産情報start -->
                    <table class="table-auto text-base lg:text-lg text-center mb-7 border border-gray-700 scroll-table c-homePrint__field__content__table">
                        <thead>
                            <tr class="bg-white-shallowDark">
                                <th class="border border-gray-700 px-4 py-2 font-medium">生産者</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">生産行程管理者</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">格付担当者</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">ほ場番号</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">ほ場面積</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">ほ場の所在地</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium w-full">作物名（品種名）</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">栽培面積</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-700 px-4 py-2">伊藤 良</td>
                                <td class="border border-gray-700 px-4 py-2">伊藤 良</td>
                                <td class="border border-gray-700 px-4 py-2">伊藤 けい子</td>
                                <td class="border border-gray-700 px-4 py-2">{{ cropDatas[key][0].farm.farm_number }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ cropDatas[key][0].farm.farm_area }}(a)</td>
                                <td class="border border-gray-700 px-4 py-2">岡谷市湊</td>
                                <td class="border border-gray-700 px-4 py-2">{{ cropDatas[key][0].variety.crop.crop_name }} ( {{cropDatas[key][0].variety.variety_name}} )</td>
                                <td class="border border-gray-700 px-4 py-2">{{ cropDatas[key][0].cultivation_area }}(a)</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- 生産情報end -->
                    <!-- 作業詳細start -->
                    <table class="table-auto text-base lg:text-lg text-center border border-gray-700 mb-7 scroll-table c-homePrint__field__content__table">
                        <thead>
                            <tr class="bg-white-shallowDark">
                                <th rowspan="2" class="border border-gray-700 px-4 py-2 font-medium">作業月日</th>
                                <th rowspan="2" class="border border-gray-700 px-4 py-2 font-medium w-full">作業内容</th>
                                <th colspan="5" class="border border-gray-700 px-4 py-2 font-medium">使用種苗・資材・使用機械・器具・その他の記録</th>
                                <th rowspan="2" class="border border-gray-700 px-4 py-2 font-medium">収穫後における管理</th>
                            </tr>
                            <tr class="bg-white-shallowDark">
                                <th class="border border-gray-700 px-4 py-2 font-medium">種苗</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">数量</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">機械・器具名</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">収納・洗浄方法</th>
                                <th class="border border-gray-700 px-4 py-2 font-medium">その他</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr v-for="(item3, key3) in allDatas[key][key2]" :key="key3" class="h-10">
                                <td v-if="item3.work_date !== ''" class="border border-gray-700 px-4 py-2">{{ item3.work_date | moment }}</td>
                                <td v-else class="border border-gray-700 px-4 py-2"></td>
                                <td class="border border-gray-700 px-4 py-2">{{ item3.work_content_id }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ item3.seed_id }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ item3.seed_volume }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ item3.equipments_id }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ item3.equip_method_id }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ item3.others }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ item3.after_harvest_id }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- 作業詳細end -->
                </div>
            </div>
        </div>
        <div v-if="isLoading" class="modalOverLayComponent">
            <div class="loading">
                <vue-loading
                    type="spin"
                    color="#ffffff"
                    class="loading__absolute"
                    :size="{ width: '80px', height: '80px' }"
                >
                </vue-loading>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import { VueLoading } from "vue-loading-template";

export default {
    components: {
        VueLoading
    },
    props:{
        "growing_crop_id": {
            required: false,
            default: null
        }
    },
    data: function() {
        return {
            allDatas: [],
            cropDatas: [],
            axiosStatus: false,
            isLoading: false
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("HomePrintProduction: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    watch: {
        //axiosStatus検出
        "axiosStatus": function (to, from) {
            console.log("HomePrintProduction Watch: axiosStatus");
            setTimeout(() => {
                const result = confirm("印刷プレビューを表示しますか？");
                if (result) {
                    window.print();
                } else {
                    this.returnPage();
                }
            }, 1000);
        }
    },
    created() {
        console.log("HomePrintProduction: created");
        let checkArray = {};
        checkArray.class = "production";
        checkArray.datas = this.growing_crop_id;
        this.isLoading = true;

        axios
            .post("/api/growing_crops/print", checkArray)
            .then(response => {
                console.log("HomePrintProduction: api print OK");
                const datas = response.data.crops;
                const arrayChunk = ([...array], size = 1) => {
                    return array.reduce((acc, value, index) => index % size ? acc : [...acc, array.slice(index, index + size)], []);
                }

                //配列を9コずつに分割
                for (let i = 0; i < datas.length; i++) {
                    this.allDatas.push(arrayChunk(datas[i], 9));
                }

                //足りないレーコド分、空白のレコードを追加
                for (let i2 = 0; i2 < this.allDatas.length; i2++) {
                    for (let i3 = 0; i3 < this.allDatas[i2].length; i3++) {
                        if(this.allDatas[i2][i3].length !== 9) {
                            console.log("HomePrintProduction: " + (9 - this.allDatas[i2][i3].length) + "レコード追加");
                            let remaining = 9 - this.allDatas[i2][i3].length;
                            for (let i4 = 0; i4 < remaining; i4++) {
                                this.allDatas[i2][i3].push({
                                    work_content_id: "",
                                    work_date: "",
                                    seed_id: "",
                                    seed_volume: "",
                                    equipments_id: "",
                                    others: "",
                                    after_harvest_id: "",
                                });
                            }
                        }
                    }
                }
                
                axios
                    .post("/api/growing_crops/find_crops", checkArray)
                    .then(response => {
                        console.log("HomePrintProduction: api find_crops OK");
                        const g_crop = response.data.g_crop;

                        g_crop.forEach(element => {
                            this.cropDatas.push(element);
                        });

                        this.axiosStatus = true;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.log("HomePrintProduction: api find_crops NG");
                    })
            })
            .catch(error => {
                console.log("HomePrintProduction: api print NG");
            })
    },
    mounted() {
        console.log("HomePrintProduction: mounted");
        //印刷キャンセルを選択すると、強制的にホームに遷移する
        window.addEventListener("afterprint", (event) => {
            console.log("HomePrintProduction: mounted -> cancel");
            this.returnPage();
        });
    },
    methods: {
        //前の画面へ戻る
        returnPage() {
            console.log("HomePrintProduction: returnPage");
            this.$router.push("/home");
            this.scrollToTop();
        }
    },
    filters: {
        moment: function (date) {
            const result = moment(date).format("M月D日")
            return result;
        },
        moment2: function (date) {
            const value1 = moment(date).format("YYYY");
            const value2 = value1 - 2018;
            const result = "令和" + value2 + "年";
            return result;
        }
    }
}
</script>