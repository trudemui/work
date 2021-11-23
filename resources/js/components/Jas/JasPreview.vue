<template>
    <div v-if="axiosStatus === true" class="flex justify-center pb-12 ml-0" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center h-14 bg-blue-normal">
                <h3 class="text-xl sm:text-2xl font-bold text-white-normal">JASマーク使用報告書 プレビュー</h3>
            </div>
            <div class="flex flex-col justify-center mt-16 mx-5 sm:mx-8 text-lg">
                <div class="flex flex-col sm:flex-row justify-center items-center sm:items-stretch w-full sm:w-auto mb-10">
                    <p class="text-lg md:text-xl font-medium text-center">有機農産物 格付実績（格付出荷記録）及びJASマーク使用報告書</p>
                </div>
                <div class="flex flex-col sm:flex-row justify-center items-center sm:items-stretch w-full sm:w-auto mb-10">
                    <p class="text-base md:text-lg font-medium text-center">{{ jasDatas[0].work_date | moment2 }} 〜 {{ jasDatas[jasDatas.length - 1].work_date | moment2 }} までの記録</p>
                </div>
                <!-- 生産情報start -->
                <table class="table-fixed text-base lg:text-lg text-center mb-10 scroll-table">
                    <thead>
                        <tr class="h-10 bg-white-shallowDark">
                            <th colspan="4" class="border border-gray-400 px-4 py-2 font-medium">生産行程管理責任者&nbsp;&nbsp;伊藤 良</th>
                            <th colspan="3" class="border border-gray-400 px-4 py-2 font-medium">格付責任者&nbsp;&nbsp;伊藤けい子</th>
                        </tr>
                        <tr class="bg-white-shallowDark">
                            <th class="border border-gray-400 px-4 py-2 font-medium w-1/2">作物名</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">格付<br>月/日</th>
                            <th colspan="3" class="border border-gray-400 px-4 py-2 font-medium">格付数量（荷口数）</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium w-1/2">出荷先</th>
                            <th class="border border-gray-400 px-4 py-2 font-medium">JASマーク<br>使用枚数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-400 px-4 py-2">-</td>
                            <td class="border border-gray-400 px-4 py-2">-</td>
                            <td class="border border-gray-400 px-4 py-2 w-1/10">包装単位</td>
                            <td class="border border-gray-400 px-4 py-2 w-1/10">荷口数</td>
                            <td class="border border-gray-400 px-4 py-2 w-1/10">重量</td>
                            <td class="border border-gray-400 px-4 py-2">-</td>
                            <td class="border border-gray-400 px-4 py-2">-</td>
                        </tr>
                        <tr v-for="(item, key) in jasDatas" :key="key">
                            <td class="border border-gray-400 px-4 py-2">{{ g_cropData[0].variety.crop.crop_name }}</td>
                            <td class="border border-gray-400 px-4 py-2">{{ item.work_date | moment }}</td>
                            <td class="border border-gray-400 px-4 py-2 w-1/10">{{ item.packaging_unit + 'g' }}</td>
                            <td class="border border-gray-400 px-4 py-2 w-1/10">{{ item.consignments_count + '袋' }}</td>
                            <td class="border border-gray-400 px-4 py-2 w-1/10">{{ item.weight + 'kg' }}</td>
                            <td v-if="item.destination_id == 0" class="border border-gray-400 px-4 py-2">角上グリーンファーム諏訪店</td>
                            <td v-else class="border border-gray-400 px-4 py-2">デリシア岡谷店</td>
                            <td class="border border-gray-400 px-4 py-2">{{ item.used_count + '枚' }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- 生産情報end -->
            </div>
            <div class="flex justify-center mt-20 mb-20">
                <button class="w-40 h-12 mr-10 text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="returnPage">戻る</button>
            </div>
            <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

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
            jasDatas: {},
            g_cropData: {},
            axiosStatus: false
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("JasPreview: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("JasPreview: created");
        let values = {
            id: this.growing_crop_id
        };
        axios
            .post("/api/jas_controls/crop_show", values)
            .then(response => {
                console.log("JasPreview: api create OK");
                console.log(response.data);
                const datas = response.data.jas_values;
                this.jasDatas = datas;
                axios
                    .post("/api/growing_crops/find_crop", values)
                    .then(response => {
                        console.log("JasPreview: api create OK");
                        const g_crop = response.data.g_crop;
                        this.g_cropData = g_crop;
                        this.axiosStatus = true;
                    })
                    .catch(error => {
                        console.log("JasPreview: api create NG");
                    })
            })
            .catch(error => {
                console.log("JasPreview: api create NG");
            })
    },
    methods: {
        //前の画面へ戻る
        returnPage() {
            console.log("JasPreview: returnPage");
            this.$router.push({
                name: "jas", 
                params: { growing_crop_id: this.growing_crop_id }
            });
            this.scrollToTop();
        }
    },
    filters: {
        moment: function (date) {
            const result = moment(date).format("M/D")
            return result;
        },
        moment2: function (date) {
            const value1 = moment(date).format("YYYY");
            const value2 = value1 - 2018;
            const result = "令和" + value2 + "年" + moment(date).format("M月D日");
            return result;
        }
    }
}
</script>