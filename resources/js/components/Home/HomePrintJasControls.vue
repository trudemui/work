<template>
    <div class="flex flex-col justify-center mleft-0 c-homePrint2" :class="{ 'is-disabled': isActiveSidebar }">
        <div v-if="axiosStatus === true">
            <div v-for="(item, key) in allDatas" :key="key" class="w-full bg-white-normal c-homePrint2__field">
                <div v-for="(item2, key2) in allDatas[key]" :key="key2" class="flex flex-col justify-center mx-5 sm:mx-8 text-lg c-homePrint2__field__content">
                    <div class="flex flex-col justify-center items-center sm:items-stretch w-full sm:w-auto my-10 c-homePrint2__field__content__top">
                        <p class="text-lg sm:text-xl text-center font-medium">有機農産物 格付実績（格付出荷記録）及びJASマーク使用報告書</p>
                        <p class="text-lg sm:text-xl text-center font-medium">
                            {{ allDatas[key][0][0].work_date | yearMonth }} 〜 {{ lastWorkDates[key].date | yearMonth }} までの記録
                        </p>
                    </div>
                    <table class="table-fixed text-base lg:text-lg text-center border border-gray-700 mb-7 scroll-table c-homePrint2__field__content__table">
                        <thead>
                        <tr class="bg-white-shallowDark">
                            <th colspan="4" class="border border-gray-400 px-4 font-medium">生産行程管理責任者&nbsp;&nbsp;伊藤 良</th>
                            <th colspan="3" class="border border-gray-400 px-4 font-medium">格付責任者&nbsp;&nbsp;伊藤けい子</th>
                        </tr>
                        <tr class="bg-white-shallowDark">
                            <th class="border border-gray-400 px-4 font-medium w-1/2">作物名</th>
                            <th class="border border-gray-400 px-4 font-medium">格付<br>月/日</th>
                            <th colspan="3" class="border border-gray-400 px-4 font-medium">格付数量（荷口数）</th>
                            <th class="border border-gray-400 px-4 font-medium w-1/2">出荷先</th>
                            <th class="border border-gray-400 px-4 font-medium">JASマーク<br>使用枚数</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-400 px-4">-</td>
                                <td class="border border-gray-400 px-4">-</td>
                                <td class="border border-gray-400 px-4 w-1/10">包装単位</td>
                                <td class="border border-gray-400 px-4 w-1/10">荷口数</td>
                                <td class="border border-gray-400 px-4 w-1/10">重量</td>
                                <td class="border border-gray-400 px-4">-</td>
                                <td class="border border-gray-400 px-4">-</td>
                            </tr>
                            <tr v-for="(item3, key3) in allDatas[key][key2]" :key="key3">
                                <td v-if="item3.work_date !== ''" class="border border-gray-400 px-4">{{ cropDatas[key][0].variety.crop.crop_name }}</td>
                                <td v-else class="border border-gray-400 px-4"></td>
                                <td v-if="item3.work_date !== ''" class="border border-gray-700 px-4">{{ item3.work_date | monthDay }}</td>
                                <td v-else class="border border-gray-700 px-4"></td>
                                <td class="border border-gray-700 px-4">{{ item3.packaging_unit + 'g' }}</td>
                                <td class="border border-gray-700 px-4">{{ item3.consignments_count + '袋' }}</td>
                                <td class="border border-gray-700 px-4">{{ item3.weight + 'kg' }}</td>
                                <td v-if="item3.destination_id == 0" class="border border-gray-400 px-4">角上グリーンファーム諏訪店</td>
                                <td v-else-if="item3.destination_id == 1" class="border border-gray-400 px-4">デリシア岡谷店</td>
                                <td v-else class="border border-gray-400 px-4"></td>
                                <td class="border border-gray-400 px-4">{{ item3.used_count + '枚' }}</td>
                            </tr>
                        </tbody>
                    </table>
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
            lastWorkDates: [],
            axiosStatus: false,
            isLoading: false
        }
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("HomePrintJasControls: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    watch: {
        //axiosStatus検出
        "axiosStatus": function (to, from) {
            console.log("HomePrintJasControls Watch: axiosStatus");
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
        console.log("HomePrintJasControls: created");
        let checkArray = {};
        checkArray.class = "jas_controls";
        checkArray.datas = this.growing_crop_id;
        this.isLoading = true;

        axios
            .post("/api/growing_crops/print", checkArray)
            .then(response => {
                console.log("HomePrintJasControls: api print OK");
                const datas = response.data.crops;
                const arrayChunk = ([...array], size = 1) => {
                    return array.reduce((acc, value, index) => index % size ? acc : [...acc, array.slice(index, index + size)], []);
                }

                //配列を37コずつに分割 + 各作物の個数
                for (let i = 0; i < datas.length; i++) {
                    if (datas[i].length === 0) continue;

                    this.allDatas.push(arrayChunk(datas[i], 30));
                    this.lastWorkDates.push({
                        date: datas[i][datas[i].length - 1].work_date,
                        length: datas[i].length
                    });
                }

                //足りないレーコド分、空白のレコードを追加
                for (let i2 = 0; i2 < this.allDatas.length; i2++) {
                    for (let i3 = 0; i3 < this.allDatas[i2].length; i3++) {
                        if(this.allDatas[i2][i3].length !== 30) {
                            console.log("HomePrintJasControls: " + (30 - this.allDatas[i2][i3].length) + "レコード追加");
                            let remaining = 30 - this.allDatas[i2][i3].length;
                            for (let i4 = 0; i4 < remaining; i4++) {
                                this.allDatas[i2][i3].push({
                                    work_date: "",
                                    packaging_unit: "",
                                    consignments_count: "",
                                    weight: "",
                                    destination_id: 2,
                                    used_count: "",
                                });
                            }
                        }
                    }
                }

                axios
                    .post("/api/growing_crops/find_crops", checkArray)
                    .then(response => {
                        console.log("HomePrintJasControls: api find_crops OK");
                        const g_crop = response.data.g_crop;

                        g_crop.forEach(element => {
                            this.cropDatas.push(element);
                        });

                        this.axiosStatus = true;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.log("HomePrintJasControls: api find_crops NG");
                    })
            })
            .catch(error => {
                console.log("HomePrintJasControls: api print NG");
            })
    },
    mounted() {
        console.log("HomePrintJasControls: mounted");
        //印刷キャンセルを選択すると、強制的にホームに遷移する
        window.addEventListener("afterprint", (event) => {
            console.log("HomePrintJasControls: mounted -> cancel");
            this.returnPage();
        });
    },
    methods: {
        //前の画面へ戻る
        returnPage() {
            console.log("HomePrintJasControls: returnPage");
            this.$router.push("/home");
            this.scrollToTop();
        }
    }
}
</script>