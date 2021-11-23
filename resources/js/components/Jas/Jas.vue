<template>
    <div v-if="axiosStatus === true" class="relative flex justify-center pb-12 disableBodyScroll" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="flex flex-col items-stretch sm:items-center w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center w-full h-14 bg-blue-normal">
                <h3 class="text-xl sm:text-2xl font-bold text-white-normal">JASマーク使用報告書</h3>
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
                        <input type="text" class="w-full sm:w-60 md:w-72 lg:w-96 h-10 px-3 py-1 shadow-md appearance-none rounded truncate pointer-events-none" :value="g_cropData[0].farm_id">
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>出荷先</p>
                        <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="body" :options="options" v-model="selected">
                            <span slot="no-options">検索候補がありません。</span>
                        </v-select>
                    </li>
                    <li class="w-full sm:w-auto mb-10">
                        <button class="w-full sm:w-110 md:w-120 lg:w-130 h-12 text-xl bg-blue-sky text-white-normal focus:outline-none focus:shadow-outline" @click="searchDatas(true)">検索</button>
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
                                    <p class="font-medium mr-2">格付月日</p>
                                    <button v-if="!isSort" class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortDesc">▲</button>
                                    <button v-else class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortAsc">▼</button>
                                </div>
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium mr-2">使用枚数</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="cursor-pointer">
                        <tr v-for="(item, key) in jasDatas" :key="key" class="h-14 hover:bg-blue-light">
                            <td class="border">
                                <label class="ECM_CheckboxInput">
                                    <input class="ECM_CheckboxInput-Input" type="checkbox" :id="item.id" :value="item.id" v-model="checkedItems" @change="getCheckId">
                                    <span class="ECM_CheckboxInput-DummyInput"></span>
                                </label>
                            </td>
                            <td class="border px-4 py-2 table-width max-w-12 md:max-w-sm lg:max-w-lg truncate" @click="editDatas(item)">{{item.work_date | monthDay}}</td>
                            <td class="border px-4 py-2 table-width max-w-12 md:max-w-sm lg:max-w-lg truncate" @click="editDatas(item)">{{item.used_count}}</td>
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
import vPaginate from "vuejs-paginate";
import vSelect from "vue-select";
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
            jasDatas: {},
            g_cropData: {},
            parPage: 10,
            currentPage: 1,
            selected: null,
            checkedId: null,
            checkedItems: [],
            options: [
                { destination_id: 0, body: "角上グリーンファーム諏訪店" },
                { destination_id: 1, body: "デリシア岡谷店" }
            ],
            axiosStatus: false,
            isSearchDatas: false,
            isSort: false
        }
    },
    computed: {
        //ページネーション
        getTableItems: function() {
            console.log("Jas: getTableItems");
            let current = this.currentPage * this.parPage;
            let start = current - this.parPage;
            return this.tableItems.slice(start, current);
        },
        //ページネーション
        getPageCount: function() {
            console.log("Jas: getPageCount");
            return this.lastPage;
        },
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("Jas: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("Jas: created");
        const values = { id: this.growing_crop_id };
        this.selected = { destination_id: 0, body: "角上グリーンファーム諏訪店" };
        axios
            .post(`/api/jas_controls/crop_index?page=${this.currentPage}`, values)
            .then(response => {
                console.log("Jas: api crop_index OK");
                const datas = response.data.jas_values;
                this.jasDatas = datas.data;
                this.currentPage = datas.current_page;
                this.lastPage = datas.last_page;
                axios
                    .post("/api/growing_crops/find_crop", values)
                    .then(response => {
                        console.log("Jas: api find_crop OK");
                        const g_crop = response.data.g_crop;
                        this.g_cropData = g_crop;
                        this.axiosStatus = true;
                    })
                    .catch(error => {
                        console.log("Jas: api find_crop NG");
                    })
            })
            .catch(error => {
                console.log("Jas: api crop_index NG");
            })
    },
    mounted() {
        console.log("Jas: mounted");
    },
    methods: {
        //ページネーションをクリック
        clickCallback(pageNum) {
            console.log("Jas: clickCallback");
            this.currentPage = Number(pageNum);
            this.getCurrentDatas();
        },
        //現在ページのデータを表示
        getCurrentDatas() {
            console.log("Jas: getCurrentDatas");
            const values = { id: this.growing_crop_id };
            axios
                .post(`/api/jas_controls/crop_index?page=${this.currentPage}`, values)
                .then(response => {
                    console.log("Jas: api crop_index OK");
                    const datas = response.data.jas_values;
                    this.jasDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                })
                .catch(error => {
                    console.log("Jas: api crop_index NG");
                })
        },
        //新規登録
        addDatas() {
            console.log("Jas: addDatas");
            this.$router.push({
                name: "jasAdd", 
                params: { growing_crop_id: this.growing_crop_id, destination: this.selected }
            });
            this.scrollToTop();
        },
        //編集
        editDatas(item) {
            console.log("Jas: editDatas");
            this.$router.push({
                name: "jasEdit", 
                params: { growing_crop_id: this.growing_crop_id, jas_controls_id: item.id }
            });
            this.scrollToTop();
        },
        //削除
        deleteDatas() {
            console.log("Jas: deleteDatas");
            if(this.checkedItems.length > 0) {
                if(!confirm("選択した項目を削除してもよろしいですか？")) {
                    return;
                } else {
                    axios
                        .post("/api/jas_controls/delete", { id: this.checkedItems[0] })
                        .then(response => {
                            console.log("Jas: api delete OK");
                            this.getCurrentDatas();
                        })
                        .catch(error => {
                            console.error("Jas: api delete NG");
                        });
                }
            } else {
                console.log("Jas: delete notSelected");
                alert("削除する項目を選択してください。");
            }
        },
        //検索
        searchDatas(firstSearch) {
            console.log("Jas: searchDatas");
            console.log(this.selected);
            if(this.selected != null) {
                const values = {
                    id: this.growing_crop_id,
                    destination_id: this.selected.destination_id
                };
                if(firstSearch === true) {
                    console.log("Jas: searchDatas -> firstSearch");
                    this.currentPage = 1;
                }
                axios
                    .post(`/api/jas_controls/search?page=${this.currentPage}`, values)
                    .then(response => {
                        console.log("Jas: api search OK");
                        const datas = response.data.search;
                        this.jasDatas = datas.data;
                        this.currentPage = datas.current_page;
                        this.lastPage = datas.last_page;
                        this.isSearchDatas = true;
                    })
                    .catch(error => {
                        console.log("Jas: api search NG");
                    })
            } else {
                console.log("Jas: searchDatas notSelected");
                alert("検索欄に値を入力してください。");
            }
        },
        //プレビュー
        preview() {
            console.log("Jas: preview");
            if(this.jasDatas.length > 0) {
                this.$router.push({
                    name: "jasPreview", 
                    params: { growing_crop_id: this.growing_crop_id }
                });
                this.scrollToTop();
            } else {
                console.log("Jas: preview undefinedDatas");
            }
        },
        //前の画面へ戻る
        returnPage() {
            console.log("Jas: returnPage");
            this.$router.push("/home");
            this.scrollToTop();
        },
        //チェックボックスのid取得
        getCheckId() {
            console.log("Jas: getCheckId");
            if(this.checkedItems.length > 1) {
                this.checkedItems.shift();
            }
            if(this.checkedItems[0]) {
                this.checkedId = this.checkedItems[0];
            }
        },
        //年月昇順ソート
        sortAsc() {
            console.log("Jas: sortAsc");
            axios
                .post(`/api/jas_controls/sort?page=${this.currentPage}`, { id: this.growing_crop_id, class: "asc" })
                .then(response => {
                    console.log("Jas: api sort OK");
                    const datas = response.data.result;
                    this.jasDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                    this.isSort = false;
                })
                .catch(error => {
                    console.error("Jas: api sort NG");
                });
        },
        //年月降順ソート
        sortDesc() {
            console.log("Jas: sortDesc");
            axios
                .post(`/api/jas_controls/sort?page=${this.currentPage}`, { id: this.growing_crop_id, class: "desc" })
                .then(response => {
                    console.log("Jas: api sort OK");
                    const datas = response.data.result;
                    this.jasDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                    this.isSort = true;
                })
                .catch(error => {
                    console.error("Jas: api sort NG");
                });
        }
    }
}
</script>
