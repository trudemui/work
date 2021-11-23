<template>
    <div v-if="axiosStatus === true" class="relative flex justify-center pb-12 disableBodyScroll" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="flex flex-col items-stretch sm:items-center w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center w-full h-14 bg-blue-normal">
                <h3 class="text-xl sm:text-2xl font-bold text-white-normal">生産行程管理記録</h3>
            </div>
            <div class="flex flex-col justify-center max-w-110 md:max-w-120 lg:max-w-130 mt-16 mx-5 sm:mx-8 text-lg">
                <ul class="flex flex-wrap justify-center sm:justify-between items-center sm:items-stretch">
                    <li class="w-full sm:w-auto mb-8">
                        <p>作物名</p>
                        <input type="text" class="w-full sm:w-60 md:w-72 lg:w-96 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].variety.crop.crop_name">
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>品種名</p>
                        <input type="text" class="w-full sm:w-60 md:w-72 lg:w-96 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].variety.variety_name">
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>年月</p>
                        <input type="text" class="w-full sm:w-60 md:w-72 lg:w-96 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].starting_date | yearMonth">
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>圃場番号</p>
                        <input type="text" class="w-full sm:w-60 md:w-72 lg:w-96 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].farm.farm_number">
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>圃場面積</p>
                        <input type="text" class="w-full sm:w-60 md:w-72 lg:w-96 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].farm.farm_area + '(a)'">
                    </li>
                    <li class="w-full sm:w-auto mb-10">
                        <p>栽培面積</p>
                        <input type="text" class="w-full sm:w-60 md:w-72 lg:w-96 h-10 px-3 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].cultivation_area + '(a)'">
                    </li>
                </ul>
                <ul class="flex flex-col items-center">
                    <li class="flex flex-col-reverse lg:flex-row justify-between w-full sm:w-auto mb-10">
                        <ul class="flex">
                            <li class="w-full mr-2 sm:mr-0">
                                <button class="w-full sm:w-56 lg:w-40 h-12 mt-9 lg:mt-0 mr-0 sm:mr-12 mb-0 md:mt-9 lg:-mb-9 text-lg sm:text-xl bg-orange-normal text-white-normal focus:outline-none focus:shadow-outline" @click="preview">プレビュー</button>
                            </li>
                            <li class="w-full ml-2 sm:ml-0">
                                <button class="w-full sm:w-56 lg:w-40 h-12 mt-9 lg:mt-0 mr-0 lg:mr-12 lg:-mb-9 text-lg sm:text-xl bg-beige-normal text-white-normal focus:outline-none focus:shadow-outline" @click="returnPage">ホームへ</button>
                            </li>
                        </ul>
                        <ul class="flex">
                            <li class="w-full mr-2 sm:mr-0">
                                <button class="w-full sm:w-56 lg:w-40 h-12 mr-0 sm:mr-12 -mb-9 text-lg sm:text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="deleteDatas">削除</button>
                            </li>
                            <li class="w-full ml-2 sm:ml-0">
                                <button class="w-full sm:w-56 lg:w-40 h-12 text-lg sm:text-xl -mb-9 bg-red-normal text-white-normal focus:outline-none focus:shadow-outline" @click="addDatas">新規登録</button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="flex justify-center mx-8 mb-14">
                <table class="table-auto text-base lg:text-lg text-center table-scroll-custom">
                    <thead>
                        <tr class="h-10">
                            <th class="border">
                            </th>
                            <th class="border px-4 py-2">
                                <div class="flex justify-center">
                                    <p class="font-medium mr-2">年月</p>
                                    <button v-if="!isSort" class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortDesc">▲</button>
                                    <button v-else class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortAsc">▼</button>
                                </div>
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium mr-2 focus:outline-none focus:shadow-outline">作業内容</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="cursor-pointer">
                        <tr v-for="(item, key) in cropDatas" :key="key" class="h-14 hover:bg-blue-light">
                            <td class="border">
                                <label class="ECM_CheckboxInput">
                                    <input class="ECM_CheckboxInput-Input" type="checkbox" :id="item" :value="item" v-model="checkedItems" @change="getCheckId">
                                    <span class="ECM_CheckboxInput-DummyInput"></span>
                                </label>
                            </td>
                            <td class="border px-4 py-2 table-width" @click="editDatas(item)">{{item.work_date | monthDay}}</td>
                            <td class="border px-4 py-2 table-width max-w-12 md:max-w-sm lg:max-w-lg truncate" @click="editDatas(item)">{{item.work_content_id}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center mx-8 mb-20">
                <div class="bootstrap">
                    <v-paginate
                        :page-count="getPageCount"
                        :click-handler="clickCallback"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'pagination'"
                        :page-class="'page-item'">
                    </v-paginate>
                </div>
            </div>
            <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
        </div>
    </div>
</template>

<script>
import vPaginate from "vuejs-paginate"
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css";

export default {
    components: {
        "v-select": vSelect,
        "v-paginate": vPaginate
    },
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
            parPage: 10,
            currentPage: 1,
            selected1: null,
            selected2: null,
            selected3: null,
            selected4: null,
            checkedId: null,
            checkedItems: [],
            options1: [],
            options2: [],
            options3: [],
            options4: [],
            isSort: false,
            axiosStatus: false
        }
    },
    computed: {
        //ページネーション
        getTableItems: function() {
            console.log("Production: getTableItems");
            let current = this.currentPage * this.parPage;
            let start = current - this.parPage;
            return this.tableItems.slice(start, current);
        },
        //ページネーション
        getPageCount: function() {
            console.log("Production: getPageCount");
            return this.lastPage;
        },
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("Production: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("Production: created");
        let values = { id: this.growing_crop_id };
        axios
            .post(`/api/productions/crop_index?page=${this.currentPage}`, values)
            .then(response => {
                console.log("Production: api crop_index OK");
                const datas = response.data.crops;
                this.cropDatas = datas.data;
                this.currentPage = datas.current_page;
                this.lastPage = datas.last_page;
                axios
                    .post("/api/growing_crops/find_crop", values)
                    .then(response => {
                        console.log("Production: api find_crop OK");
                        const g_crop = response.data.g_crop;
                        this.g_cropData = g_crop;
                        this.axiosStatus = true;
                    })
                    .catch(error => {
                        console.log("Production: api find_crop NG");
                    })
            })
            .catch(error => {
                console.log("Production: api crop_index NG");
            })
    },
    mounted() {
        console.log("Production: mounted");
    },
    methods: {
        //ページネーションをクリック
        clickCallback(pageNum) {
            console.log("Production: clickCallback");
            this.currentPage = Number(pageNum);
            this.getCurrentDatas();
        },
        //現在ページのデータを表示
        getCurrentDatas() {
            console.log("Production: getCurrentDatas");
            let values = {
                id: this.growing_crop_id
            };
            axios
            .post(`/api/productions/crop_index?page=${this.currentPage}`, values)
                .then(response => {
                    console.log("Production: api create OK");
                    const datas = response.data.crops;
                    this.cropDatas = datas.data;
                    console.log(this.cropDatas);
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
                })
                .catch(error => {
                    console.log("Production: api create NG");
                })
        },
        //新規登録
        addDatas() {
            console.log("Production: addDatas");
            this.$router.push({
                name: "productionAdd", 
                params: { growing_crop_id: this.growing_crop_id }
            });
            this.scrollToTop();
        },
        //編集
        editDatas(item) {
            console.log("Production: editDatas");
            this.$router.push({
                name: "productionEdit", 
                params: { growing_crop_id: this.growing_crop_id, production_edit_id: item.id }
            });
            this.scrollToTop();
        },
        //削除
        deleteDatas() {
            console.log("Production: deleteDatas");
            if(this.checkedItems.length > 0) {
                console.log(this.checkedItems[0])
                if(!confirm("選択した項目を削除してもよろしいですか？")) {
                    return;
                } else {
                    let params = {};
                    params = {
                        id: this.checkedItems[0].id,
                        growing_crop_id: this.growing_crop_id,
                        harvest: this.checkedItems[0].work_content_id
                    };

                    axios
                        .post("/api/productions/delete", params)
                        .then(response => {
                            console.log("Production: api delete OK");
                            this.getCurrentDatas();
                        })
                        .catch(error => {
                            console.error("Production: api delete NG");
                        });
                }
            } else {
                console.log("Production: delete notSelected");
                alert("削除する項目を選択してください。");
            }
        },
        //プレビュー
        preview() {
            console.log("Production: preview");
            if(this.cropDatas.length > 0) {
                this.$router.push({
                    name: "productionPreview", 
                    params: { growing_crop_id: this.growing_crop_id }
                });
                this.scrollToTop();
            } else {
                console.log("Production: preview undefinedDatas");
                alert("作業内容が登録されていません。");
            }
        },
        //前の画面へ戻る
        returnPage() {
            console.log("Production: returnPage");
            this.$router.push("/home");
            this.scrollToTop();
        },
        //チェックボックスのid取得
        getCheckId() {
            console.log("Production: getCheckId");
            if(this.checkedItems.length > 1) {
                this.checkedItems.shift();
            }
            if(this.checkedItems[0]) {
                this.checkedId = this.checkedItems[0];
            }
        },
        //年月昇順ソート
        sortAsc() {
            console.log("Production: sortAsc");
            axios
                .post(`/api/productions/sort?page=${this.currentPage}`, { id: this.growing_crop_id, class: "asc" })
                .then(response => {
                    console.log("Production: api sort OK");
                    const datas = response.data.result;
                    this.cropDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                    this.isSort = false;
                })
                .catch(error => {
                    console.error("Production: api sort NG");
                });
        },
        //年月降順ソート
        sortDesc() {
            console.log("Production: sortDesc");
            axios
                .post(`/api/productions/sort?page=${this.currentPage}`, { id: this.growing_crop_id, class: "desc" })
                .then(response => {
                    console.log("Production: api sort OK");
                    const datas = response.data.result;
                    this.cropDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                    this.isSort = true;
                })
                .catch(error => {
                    console.error("Production: api sort NG");
                });
        }
    }
}
</script>
