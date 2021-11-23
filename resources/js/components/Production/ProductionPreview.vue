<template>
    <div v-if="axiosStatus === true" class="flex justify-center pb-12 ml-0" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center h-14 bg-blue-normal">
                <h3 class="text-xl sm:text-2xl font-bold text-white-normal">生産行程管理記録 プレビュー</h3>
            </div>
            <div class="flex flex-col justify-center mt-16 mx-5 sm:mx-8 text-lg">
                <div class="flex flex-col sm:flex-row justify-center items-center sm:items-stretch w-full sm:w-auto mb-10">
                    <p class="text-lg sm:text-xl font-medium">生産行程管理記録</p>
                    <p class="text-lg sm:text-xl font-medium">（ {{ cropDatas[0].work_date | onlyYear }} の管理記録 ）</p>
                </div>
                <!-- 生産情報start -->
                <table class="table-auto text-base lg:text-lg text-center mb-10 scroll-table">
                    <thead>
                        <tr class="h-10 bg-white-shallowDark">
                            <th class="border border-gray-400 px-4 py-2 font-medium">生産者</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">生産行程管理者</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">格付担当者</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">ほ場番号</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">ほ場面積</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">ほ場の所在地</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium w-full">作物名（品種名）</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">栽培面積</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-400 px-4 py-2">伊藤 良</td>
                            <td class="border border-gray-400 px-4 py-2">伊藤 良</td>
                            <td class="border border-gray-400 px-4 py-2">伊藤 けい子</td>
                            <td class="border border-gray-400 px-4 py-2">{{g_cropData[0].farm.farm_number}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{g_cropData[0].farm.farm_area}}(a)</td>
                            <td class="border border-gray-400 px-4 py-2">岡谷市湊</td>
                            <td class="border border-gray-400 px-4 py-2">{{g_cropData[0].variety.crop.crop_name}} ( {{g_cropData[0].variety.variety_name}} )</td>
                            <td class="border border-gray-400 px-4 py-2">{{g_cropData[0].cultivation_area}}(a)</td>
                        </tr>
                    </tbody>
                </table>
                <!-- 生産情報end -->
                <!-- 作業詳細start -->
                <table class="table-auto text-base lg:text-lg text-center scroll-table">
                    <thead>
                        <tr class="h-10 bg-white-shallowDark">
                            <th rowspan="2" class="border border-gray-400 px-4 py-2 font-medium">作業月日</th>
                            <th rowspan="2" class="border border-gray-400 px-4 py-2 font-medium w-full">作業内容</th>
                            <th colspan="5" class="border border-gray-400 px-4 py-2 font-medium">使用種苗・資材・使用機械・器具・その他の記録</th>
                            <th rowspan="2" class="border border-gray-400 px-4 py-2 font-medium">収穫後における管理</th>
                        </tr>
                        <tr class="h-10 bg-white-shallowDark">
                            <th class="border border-gray-400 px-4 py-2 font-medium">種苗</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">数量</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">機械・器具名</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">収納・洗浄方法</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">その他</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, key) in cropDatas" :key="key" class="h-20 mini:h-14">
                            <td class="border border-gray-400 px-4 py-2">{{item.work_date | monthDay}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{item.work_content_id}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{item.seed_id}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{item.seed_volume}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{item.equipments_id}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{item.equip_method_id}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{item.others}}</td>
                            <td class="border border-gray-400 px-4 py-2">{{item.after_harvest_id}}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- 作業詳細end -->
            </div>
            <div class="flex justify-center items-center sm:items-stretch mt-8 mb-16 mx-5 sm:mx-0">
                <button class="w-full sm:w-40 h-12 text-lg sm:text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="returnPage">戻る</button>
            </div>
            <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props:{
        "growing_crop_id": {
            required: false,
            default: null
        }
    },
    data: function() {
        return {
            cropDatas: {},
            g_cropData: {},
            axiosStatus: false
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("ProductionPreview: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("ProductionPreview: created");
        let values = {
            id: this.growing_crop_id
        };
        axios
            .post("/api/productions/crop_show", values)
            .then(response => {
                console.log("ProductionPreview: api create OK");
                const datas = response.data.crops;
                this.cropDatas = datas;
                axios
                    .post("/api/growing_crops/find_crop", values)
                    .then(response => {
                        console.log("ProductionPreview: api create OK");
                        const g_crop = response.data.g_crop;
                        this.g_cropData = g_crop;
                        this.axiosStatus = true;
                    })
                    .catch(error => {
                        console.log("ProductionPreview: api create NG");
                    })
            })
            .catch(error => {
                console.log("ProductionPreview: api create NG");
            })
    },
    methods: {
        //前の画面へ戻る
        returnPage() {
            console.log("ProductionPreview: returnPage");
            this.$router.push({
                name: "production", 
                params: { growing_crop_id: this.growing_crop_id }
            });
            this.scrollToTop();
        }
    }
}
</script>