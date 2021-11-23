<template>
    <div v-if="axiosStatus === true" class="relative flex justify-center pb-12 disableBodyScroll" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center h-14 bg-blue-normal">
                <h3 class="text-xl sm:text-2xl font-bold text-white-normal">ホーム</h3>
            </div>
            <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                <ul class="w-full flex flex-col items-center">
                    <li class="w-full sm:w-auto mb-8">
                        <p>年月</p>
                        <v-datepicker
                            class="customPicker w-full sm:w-110 md:w-120 lg:w-130 h-10 pt-1.5 px-3 shadow-md appearance-none rounded focus:outline-none focus:shadow-outline"
                            v-model="selected4"
                            :format="DatePickerFormat"
                            :language="ja"
                            :initial-view="'year'"
                            :minimum-view="'month'"
                            :maximum-view="'year'"
                            @closed="pickerClosed"
                        />
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>作物名</p>
                        <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="crop_name" :options="options1" v-model="selected1" @input="find_varieties">
                            <span slot="no-options">検索候補がありません。</span>
                        </v-select>
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>品種</p>
                        <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="variety_name" :options="options3" v-model="selected3">
                            <span slot="no-options">検索候補がありません。</span>
                        </v-select>
                    </li>
                    <li class="w-full sm:w-auto mb-10">
                        <p>圃場</p>
                        <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="farm_number" :options="options2" v-model="selected2">
                            <span slot="no-options">検索候補がありません。</span>
                        </v-select>
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>収穫</p>
                        <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="harvest_flg" :options="options5" v-model="selected5">
                            <span slot="no-options">検索候補がありません。</span>
                        </v-select>
                    </li>
                    <li class="w-full sm:w-auto mb-10">
                        <button class="w-full sm:w-110 md:w-120 lg:w-130 h-12 text-xl bg-blue-sky text-white-normal focus:outline-none focus:shadow-outline" @click="searchDatas(true)">検索</button>
                    </li>
                    <li class="w-full sm:w-auto mb-10">
                        <button class="w-full sm:w-110 md:w-120 lg:w-130 h-12 text-xl bg-beige-normal text-white-normal focus:outline-none focus:shadow-outline" @click="resetDatas">リセット</button>
                    </li>
                    <li class="flex flex-col-reverse lg:flex-row justify-between w-full sm:w-auto  mb-10">
                        <ul class="flex">
                            <li class="w-full mr-2 sm:mr-0">
                                <button class="w-full sm:w-56 lg:w-40 h-12 mt-9 lg:mt-0 mr-0 sm:mr-12 mb-0 md:mt-9 lg:-mb-9 text-lg sm:text-xl bg-orange-normal text-white-normal focus:outline-none focus:shadow-outline" @click="showModal(null, 1)">印刷</button>
                            </li>
                            <li class="w-full ml-2 sm:ml-0">
                                <button class="w-full sm:w-56 lg:w-40 h-12 mt-9 lg:mt-0 mr-0 lg:mr-12 lg:-mb-9 text-lg sm:text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="deleteDatas">削除</button>
                            </li>
                        </ul>
                        <ul class="flex">
                            <li class="w-full mr-2 sm:mr-0">
                                <button class="w-full sm:w-56 lg:w-40 h-12 mr-12 mb-0 md:-mb-9 text-lg sm:text-xl bg-green-normal text-white-normal focus:outline-none focus:shadow-outline" @click="editDatas">編集</button>
                            </li>
                            <li class="w-full ml-2 sm:ml-0">
                                <router-link to="/home/add" class="flex md:mb-10" @click.native="scrollToTop">
                                    <button class="w-full sm:w-56 lg:w-40 h-12 text-lg sm:text-xl mb-0 md:-mb-9 bg-red-normal text-white-normal focus:outline-none focus:shadow-outline">新規登録</button>
                                </router-link>
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
                                <label class="ECM_CheckboxInput">
                                    <input class="ECM_CheckboxInput-Input" type="checkbox" v-model="checkedAllItems">
                                    <span class="ECM_CheckboxInput-DummyInput"></span>
                                </label>
                            </th>
                            <th class="border px-4 py-2">
                                <div class="flex justify-center">
                                    <p class="font-medium mr-2">年月</p>
                                    <button v-if="!isSortYear" class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortAscYear">▼</button>
                                    <button v-else class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortDescYear">▲</button>
                                </div>
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium">作物名</p>
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium">品種名</p>
                            </th>
                            <th class="border px-4 py-2">
                                <div class="flex justify-center">
                                    <p class="font-medium mr-2">圃場</p>
                                    <button v-if="!isSortFarm" class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortAscFarm">▼</button>
                                    <button v-else class="text-xl -mt-0.5 focus:outline-none focus:shadow-outline" @click="sortDescFarm">▲</button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="cursor-pointer">
                        <tr v-for="(item, key) in cropDatas" :key="key" class="h-14 hover:bg-blue-light">
                            <!-- 未収穫レコードstart -->
                            <td v-if="item.harvest_flg == 0" class="border">
                                <label class="ECM_CheckboxInput">
                                    <input class="ECM_CheckboxInput-Input" type="checkbox" :value="item" v-model="checkedItems" @change="getCheckId">
                                    <span class="ECM_CheckboxInput-DummyInput"></span>
                                </label>
                            </td>
                            <td v-if="item.harvest_flg == 0" class="border px-4 py-2" @click="showModal(item, 0)">{{ item.starting_date | yearMonth}}</td>
                            <td v-if="item.harvest_flg == 0" class="border px-4 py-2" @click="showModal(item, 0)">{{ item.variety.crop.crop_name }}</td>
                            <td v-if="item.harvest_flg == 0" class="border px-4 py-2" @click="showModal(item, 0)">{{ item.variety.variety_name }}</td>
                            <td v-if="item.harvest_flg == 0" class="border px-4 py-2" @click="showModal(item, 0)">{{ item.farm.farm_number }}</td>
                            <!-- 未収穫レコードend -->
                            <!-- 収穫済みレコードstart -->
                            <td v-if="item.harvest_flg == 1" class="border bg-white-shallowDark">
                                <label class="ECM_CheckboxInput">
                                    <input class="ECM_CheckboxInput-Input" type="checkbox" :value="item" v-model="checkedItems" @change="getCheckId">
                                    <span class="ECM_CheckboxInput-DummyInput"></span>
                                </label>
                            </td>
                            <td v-if="item.harvest_flg == 1" class="border px-4 py-2 bg-white-shallowDark" @click="showModal(item, 0)">{{ item.starting_date | yearMonth}}</td>
                            <td v-if="item.harvest_flg == 1" class="border px-4 py-2 bg-white-shallowDark" @click="showModal(item, 0)">{{ item.variety.crop.crop_name }}</td>
                            <td v-if="item.harvest_flg == 1" class="border px-4 py-2 bg-white-shallowDark" @click="showModal(item, 0)">{{ item.variety.variety_name }}</td>
                            <td v-if="item.harvest_flg == 1" class="border px-4 py-2 bg-white-shallowDark" @click="showModal(item, 0)">{{ item.farm.farm_number }}</td>
                            <!-- 収穫済みレコードend -->
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center mx-8 mb-20">
                <div class="bootstrap">
                    <v-paginate
                        v-if="isPaginate"
                        :page-count="getPageCount"
                        :click-handler="clickCallback"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'pagination'"
                        :page-class="'page-item'">
                    </v-paginate>
                </div>
            </div>
            <modal name="modalWindow" :draggable="false" :resizable="false" :width="'270px'" :height="'400px'">
                <div class="relative w-full h-full">
                    <div class="inset-center-fixed w-full p-4">
                        <div class="flex flex-col justify-center items-center mb-5">
                            <p class="mb-3 text-lg">対象選択</p>
                            <ul>
                                <li class="flex mb-3">
                                    <p class="mr-2">年月</p>
                                    <p>{{modalStartingDate | yearMonth}}</p>
                                </li>
                                <li class="flex mb-3">
                                    <p class="mr-2">圃場</p>
                                    <p>{{modalfarmNumber}}</p>
                                </li>
                                <li class="flex mb-3">
                                    <p class="mr-2">作物名</p>
                                    <p>{{modalCropName}}</p>
                                </li>
                                <li class="flex">
                                    <p class="mr-2">品種名</p>
                                    <p>{{modalVarietyName}}</p>
                                </li>
                            </ul>
                        </div>
                        <div class="flex flex-col items-center">
                            <button class="w-44 h-8 mb-4 bg-blue-sky text-white-normal focus:outline-none focus:shadow-outline" @click="production">生産行程管理記録</button>
                            <button class="w-44 h-8 mb-4 bg-blue-sky text-white-normal focus:outline-none focus:shadow-outline" @click="jasControls">JASマーク使用報告書</button>
                            <button class="w-44 h-8 bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="hideModal(0)">閉じる</button>
                        </div>  
                    </div>
                </div>
            </modal>
            <modal name="modalWindow2" :draggable="false" :resizable="false" :width="'270px'" :height="'400px'">
                <div class="relative w-full h-full">
                    <div class="inset-center-fixed w-full p-4">
                        <div class="flex flex-col justify-center items-center mb-5">
                            <p class="mb-3 text-lg">印刷対象選択</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <button class="w-44 h-8 mb-4 bg-blue-sky text-white-normal focus:outline-none focus:shadow-outline" @click="print(0)">生産行程管理記録</button>
                            <button class="w-44 h-8 mb-4 bg-blue-sky text-white-normal focus:outline-none focus:shadow-outline" @click="print(1)">JASマーク使用報告書</button>
                            <button class="w-44 h-8 bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="hideModal(1)">閉じる</button>
                        </div>  
                    </div>
                </div>
            </modal>
            <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
        </div>
    </div>
</template>

<script>
import { ja } from "vuejs-datepicker/dist/locale"
import vDatepicker from "vuejs-datepicker"
import moment from "moment";
import vPaginate from "vuejs-paginate";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        "v-select": vSelect,
        "v-paginate": vPaginate,
        "v-datepicker": vDatepicker,
    },
    data: function() {
        return {
            selected1: null,
            selected2: null,
            selected3: null,
            selected4: null,
            selected5: null,
            growingCropId: "",
            modalGrowingCropId: "",
            modalStartingDate: "",
            modalfarmNumber: "",
            modalCropName: "",
            modalVarietyName: "",
            parPage: 10,
            currentPage: 1,
            lastPage: 0,
            cropDatas: {},
            resultSearchDatas: {},
            resultTableItems: [],
            options1: [],
            options2: [],
            options3: [],
            options4: [],
            options5: [{ id: 1, harvest_flg: "収穫済み"  }, { id: 0, harvest_flg: "未収穫" }],
            checkedItems: [],
            DatePickerFormat: "yyyy年M月",
            ja: ja,
            isSearchDatas: false, //検索を含むソートかどうか
            isSortYear: false, //年月ソートの昇順・降順切り替え
            isSortFarm: false, //圃場ソートの昇順・降順切り替え
            isPaginate: true, //ページネート番号を初期化するため
            axiosStatus: false
        }
    },
    computed: {
        //ページネーションと検索機能
        getTableItems: function() {
            console.log("Home: getTableItems");
            let current = this.currentPage * this.parPage;
            let start = current - this.parPage;
            this.resultTableItems = resultTableItems;
            return resultTableItems.slice(start, current);
        },
        //ページネーション
        getPageCount: function() {
            console.log("Home: getPageCount");
            return this.lastPage;
        },
        //モーダルウインドウ判定
        isActiveModal() {
            console.log("Home: isActiveModal");
            return this.$store.state.isActiveModal;
        },
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("Home: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        },
        //生産行程記録判定
        isActiveProduction() {
            console.log("Home: isActiveProduction");
            return this.$store.state.isProduction;
        },
        //チェックボックス全選択
        checkedAllItems: {
            get() {
                console.log("Home: checkedAllItems get");
                return this.checkedItems.length === this.cropDatas.length;
            },
            set(checked) {
                console.log("Home: checkedAllItems set");
                this.checkedItems = checked ? this.cropDatas : [];
            },
        },
    },
    created() {
        console.log("Home: created");
        axios
            .get(`/api/growing_crops?page=${this.currentPage}`)
            .then(response => {
                console.log("Home: api growing_crops OK");
                const datas = response.data.crops;
                this.cropDatas = datas.data;
                this.currentPage = datas.current_page;
                this.lastPage = datas.last_page;
                this.axiosStatus = true;
            })
            .catch(error => {
                console.error("Home: api growing_crops NG");
            });
        axios
            .get("/api/growing_crops/get_list")
            .then(response => {
                console.log("Home: api get_list OK");
                //作物名
                const crops = response.data.crops;
                let values1 = [];
                    crops.forEach(function(data) {
                        values1.push({
                            id: data.id,
                            crop_name: data.crop_name
                        })
                    })
                this.options1 = values1;
                //圃場
                const farms = response.data.farms;
                let values2 = [];
                    farms.forEach(function(data) {
                        values2.push({
                            id: data.id,
                            farm_number: data.farm_number
                        })
                    })
                this.options2 = values2;
            })
            .catch(error => {
                console.log("Home: api get_list NG");
            })
    },
    methods: {
        //ページネーションをクリック
        clickCallback(pageNum) {
            console.log("Home: clickCallback");
            if(this.isSearchDatas === false && this.isSortYear === false && this.isSortFarm === false) {
                console.log("Home: clickCallback -> normalPaginate");
                this.currentPage = Number(pageNum);
                this.getCurrentDatas();
            } else if (this.isSortYear === true) {
                console.log("Home: clickCallback -> sortPaginate(Year)");
                this.currentPage = Number(pageNum);
                this.sortAscYear();
            } else if (this.isSortFarm === true) {
                console.log("Home: clickCallback -> sortPaginate(Farm)");
                this.currentPage = Number(pageNum);
                this.sortAscFarm();
            } else {
                console.log("Home: clickCallback -> searchPaginate");
                this.currentPage = Number(pageNum);
                this.searchDatas(false);
            }
        },
        //品種名取得
        find_varieties() {
            console.log("Home: find_varieties");
            if(this.selected1 !== null) {
                let value = {
                    id: this.selected1.id
                };
                axios
                    .post(`/api/varieties/find_varieties`, value)
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
                        this.options3 = values3;
                    })
                    .catch(error => {
                        console.log("Home: api find_varieties NG");
                    });
            } else {
                this.selected3 = "";
                this.options3 = [];
            }
        },
        //モーダルウインドウを開く
        showModal(item, type) {
            console.log("Home: showModal");
            if(type === 0) {
                this.modalGrowingCropId = item.id
                this.modalStartingDate = item.starting_date;
                this.modalfarmNumber = item.farm.farm_number;
                this.modalCropName = item.variety.crop.crop_name;
                this.modalVarietyName = item.variety.variety_name;
                this.$modal.show("modalWindow");
            } else {
                if(this.checkedItems.length > 0) {
                    this.$modal.show("modalWindow2");
                } else {
                    console.log("Home: editDatas notSelected");
                    alert("印刷する項目を選択してください。");
                }
            }
        },
        //モーダルウインドウを閉じる
        hideModal(type) {
            console.log("Home: hideModal");
            if(type === 0) {
                this.modalGrowingCropId = "";
                this.modalStartingDate = "";
                this.modalfarmNumber ="";
                this.modalCropName = "";
                this.modalVarietyName = "";
                this.$modal.hide("modalWindow");
            } else {
                this.$modal.hide("modalWindow2");
            }
        },
        //生産行程管理記録へ遷移
        production() {
            console.log("Home: production");
            this.$router.push({
                name: "production", 
                params: {
                    growing_crop_id: this.modalGrowingCropId,
                }
            })
        },
        //JASマーク使用報告書へ遷移
        jasControls() {
            console.log("Home: jasControls");
            this.$router.push({ 
                name: "jas",
                params: {
                    growing_crop_id: this.modalGrowingCropId,
                }
            });
            this.scrollToTop();
        },
        //現在ページのデータを表示
        getCurrentDatas() {
            console.log("Home: getCurrentDatas");
            axios
                .get(`/api/growing_crops?page=${this.currentPage}`)
                .then(response => {
                    console.log("Home: api growing_crops OK");
                    const datas = response.data.crops;
                    this.cropDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                })
                .catch(error => {
                    console.error("Home: api growing_crops NG");
                });
        },
        //並び替えたデータを表示
        postSortDatas() {
            console.log("Home: postSortDatas");
            axios
                .post(`/api/growing_crops?page=${this.currentPage}`)
                .then(response => {
                    console.log("Home: api growing_crops OK");
                    const datas = response.data.crops;
                    this.cropDatas = datas.data;
                })
                .catch(error => {
                    console.error("Home: api growing_crops NG");
                });
        },
        //編集
        editDatas() {
            console.log("Home: editDatas");
            if(this.checkedItems.length > 1) {
                console.log("Home: editDatas notMultiSelected");
                alert("一度に編集できる項目は一つまでです。");
                this.checkedItems = [];
            } else if(this.checkedItems.length > 0) {
                this.$router.push({
                    name: "homeEdit", 
                    params: {
                        growing_crop_id: this.growingCropId,
                    }
                })
                this.scrollToTop();
            } else {
                console.log("Home: editDatas notSelected");
                alert("編集する項目を選択してください。");
            }
        },
        //検索
        searchDatas(firstSearch) {
            console.log("Home: searchDatas");
            if(this.selected1 != null || this.selected2 != null || this.selected3 != null || this.selected4 != null || this.selected5 != null) {
                if (this.selected1 === null) {
                    this.selected1 = { id: "" };
                }
                if (this.selected2 === null) {
                    this.selected2 = { id: "" };
                }
                if (this.selected3 === null) {
                    this.selected3 = { id: "" };
                }
                if (this.selected4 === null) {
                    this.selected4 = "";
                }
                if (this.selected5 === null) {
                    this.selected5 = "";
                }
                let values = {
                    crop_id: this.selected1.id,
                    farm_id: this.selected2.id,
                    variety_id: this.selected3.id,
                    starting_date: this.selected4,
                    harvest_flg: this.selected5.id
                };
                if(firstSearch === true) {
                    console.log("Home: searchDatas -> firstSearch");
                    this.currentPage = 1;
                    this.isPaginate = false;
                }
                axios
                    .post(`/api/growing_crops/search?page=${this.currentPage}`, values)
                    .then(response => {
                        console.log("Home: api search OK");
                        const datas = response.data.search;
                        this.cropDatas = datas.data;
                        this.currentPage = datas.current_page;
                        this.lastPage = datas.last_page;
                        this.isSearchDatas = true;
                        this.isSortYear = false;
                        this.isPaginate = true;
                    })
                    .catch(error => {
                        console.log("Home: api search NG");
                    })
            } else {
                console.log("Home: searchDatas notSelected");
                alert("検索欄に値を入力してください。");
            }
        },
        //リセット
        resetDatas() {
            console.log("Home: resetDatas");
            this.selected1 = {};
            this.selected2 = {};
            this.selected3 = {};
            this.selected4 = "";
            this.selected5 = "";
            this.currentPage = 1;
            this.isSearchDatas = false;
            this.isSortYear = false;
            this.scrollToTop();
            this.getCurrentDatas();
        },
        //削除
        deleteDatas() {
            console.log("Home: deleteDatas");
            if (this.checkedItems.length > 0) {
                if (!confirm("選択した項目を削除してもよろしいですか？\n「収穫済み」の作物は削除対象には入りません。")) return;
                
                let items = [];
                this.checkedItems.forEach((data) => {
                    //収穫済み以外を取得
                    if (data.harvest_flg !== 1) {
                        items.push({ id: data.id });
                    }
                });

                axios
                    .post("/api/growing_crops/delete", { "id" : items })
                    .then(response => {
                        console.log("Home: api delete OK");
                        this.checkedAllItems = false;
                        this.getCurrentDatas();
                    })
                    .catch(error => {
                        console.error("Home: api delete NG");
                    });
            } else {
                console.log("Home: delete notSelected");
                alert("削除する項目を選択してください。");
            }
        },
        //チェックボックスのid取得
        getCheckId() {
            console.log("Home: getCheckId");
            if(this.checkedItems[0]) {
                this.growingCropId = this.checkedItems[0].id;
            }
        },
        //日付のフォーマット
        pickerClosed() {
            console.log("Home: pickerClosed");
            this.selected4 = moment(this.selected4).format("YYYY-MM");
        },
        //年月昇順ソート
        sortAscYear() {
            console.log("Home: sortAscYear");
            let values = {}; 
            if(this.isSearchDatas === false) {
                console.log("Home: sortAscYear -> normal");
                values = { type: "year", class: "asc" }; 
            } else {
                console.log("Home: sortAscYear -> search");
                if(this.selected1 != null || this.selected2 != null || this.selected3 != null || this.selected4 != null || this.selected5 != null) {
                    if (this.selected1 === null) {
                        this.selected1 = { id: "" };
                    }
                    if (this.selected2 === null) {
                        this.selected2 = { id: "" };
                    }
                    if (this.selected3 === null) {
                        this.selected3 = { id: "" };
                    }
                    if (this.selected4 === null) {
                        this.selected4 = "";
                    }
                    if (this.selected5 === null) {
                        this.selected5 = { id: "" };
                    }
                    values = {
                        type:"year",
                        class: "searchAsc",
                        crop_id: this.selected1.id,
                        farm_id: this.selected2.id,
                        variety_id: this.selected3.id,
                        starting_date: this.selected4,
                        harvest_flg: this.selected5.id
                    };
                }
            }

            axios
                .post(`/api/growing_crops/sort?page=${this.currentPage}`, values)
                .then(response => {
                    console.log("Home: api sort OK");
                    const datas = response.data.crops;
                    this.cropDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                    this.isSortYear = true;
                    this.isSortFarm = false;
                })
                .catch(error => {
                    console.error("Home: api sort NG");
                });
        },
        //圃場昇順ソート
        sortAscFarm() {
            console.log("Home: sortAscFarm");
            let values = {}; 
            if (this.isSearchDatas === false) {
                console.log("Home: sortAscFarm -> normal");
                values = { type: "farm", class: "asc" }; 
            } else {
                console.log("Home: sortAscFarm -> search");
                if (this.selected1 != null || this.selected2 != null || this.selected3 != null || this.selected4 != null || this.selected5 != null) {
                    if (this.selected1 === null) {
                        this.selected1 = { id: "" };
                    }
                    if (this.selected2 === null) {
                        this.selected2 = { id: "" };
                    }
                    if (this.selected3 === null) {
                        this.selected3 = { id: "" };
                    }
                    if (this.selected4 === null) {
                        this.selected4 = "";
                    }
                    if (this.selected5 === null) {
                        this.selected5 = { id: "" };
                    }
                    values = {
                        type:"farm",
                        class: "searchAsc",
                        crop_id: this.selected1.id,
                        farm_id: this.selected2.id,
                        variety_id: this.selected3.id,
                        starting_date: this.selected4,
                        harvest_flg: this.selected5.id
                    };
                }
            }

            axios
                .post(`/api/growing_crops/sort?page=${this.currentPage}`, values)
                .then(response => {
                    console.log("Home: api sort OK");
                    const datas = response.data.crops;
                    this.cropDatas = datas.data;
                    this.currentPage = datas.current_page;
                    this.lastPage = datas.last_page;
                    this.isSortFarm = true;
                    this.isSortYear = false;
                })
                .catch(error => {
                    console.error("Home: api sort NG");
                });
        },
        //年月降順ソート
        sortDescYear() {
            console.log("Home: sortDescYear");
            if(!this.isSearchDatas) {
                console.log("Home: sortDescYear -> normal");
                this.getCurrentDatas();
                this.isSortYear = false;
                this.isSearchDatas = false;
            } else {
                console.log("Home: sortDescYear -> search");
                this.searchDatas(true);
                this.isSortYear = false;
            }
        },
        //圃場降順ソート
        sortDescFarm() {
            console.log("Home: sortDescFarm");
            if(!this.isSearchDatas) {
                console.log("Home: sortDescFarm -> normal");
                this.getCurrentDatas();
                this.isSortFarm = false;
                this.isSearchDatas = false;
            } else {
                console.log("Home: sortDescFarm -> search");
                this.searchDatas(true);
                this.isSortFarm = false;
            }
        },
        //印刷へ遷移
        print(type) {
            console.log("Home: print");
            const items = [];
            let nothingDatas = [];
            let messages = [];
            let checkArray = {};
            
            if(type === 0) {
                checkArray.class = "production";
                checkArray.datas = [];

                this.checkedItems.forEach((data) => {
                    items.push({ id: data.id });
                    checkArray.datas.push({ id: data.id });
                });

                axios
                    .post("/api/growing_crops/nothing_print", checkArray)
                    .then(response => {
                        console.log("Home: api nothing_print OK");
                        const crops = response.data.crops;
                        
                        //生産行程管理記録が存在しないデータが含まれている場合
                        if (crops.length !== 0) {
                            crops.forEach((data) => {
                                const date = this.changeYearMonth(data[0].starting_date);
                                const crop_name = data[0].variety.crop.crop_name;
                                const variety_name = data[0].variety.variety_name;

                                nothingDatas.push(date + " " + crop_name + " " + variety_name);
                            });
                            
                            messages = nothingDatas.join("\n");
                            alert("以下の作物は印刷対象外です。\n\n" + messages);
                            
                            //生産行程管理記録が存在しないデータのみの場合は遷移しない
                            if (items.length !== nothingDatas.length) {
                                this.$router.push({
                                    name: "homePrintProduction", 
                                    params: {
                                        growing_crop_id: items,
                                    }
                                });
                            }

                            return;
                        }

                        //通常の遷移
                        this.$router.push({
                            name: "homePrintProduction", 
                            params: {
                                growing_crop_id: items,
                            }
                        });
                    })
                    .catch(error => {
                        console.log("Home: api nothing_print NG");
                    })
            } else {
                checkArray.class = "jas_controls";
                checkArray.datas = [];

                this.checkedItems.forEach((data) => {
                    items.push({ id: data.id });
                    checkArray.datas.push({ id: data.id });
                });

                axios
                    .post("/api/growing_crops/nothing_print", checkArray)
                    .then(response => {
                        console.log("Home: api nothing_print OK");
                        const crops = response.data.crops;
                        
                        //JASマーク使用報告書が存在しないデータが含まれている場合
                        if (crops.length !== 0) {
                            crops.forEach((data) => {
                                const date = this.changeYearMonth(data[0].starting_date);
                                const crop_name = data[0].variety.crop.crop_name;
                                const variety_name = data[0].variety.variety_name;

                                nothingDatas.push(date + " " + crop_name + " " + variety_name);
                            });
                            
                            messages = nothingDatas.join("\n");
                            alert("以下の作物は印刷対象外です。\n\n" + messages);
                            
                            //JASマーク使用報告書が存在しないデータのみの場合は遷移しない
                            if (items.length !== nothingDatas.length) {
                                this.$router.push({
                                    name: "homePrintJasControls", 
                                    params: {
                                        growing_crop_id: items,
                                    }
                                });
                            }

                            return;
                        }

                        //通常の遷移
                        this.$router.push({
                            name: "homePrintJasControls", 
                            params: {
                                growing_crop_id: items,
                            }
                        });
                    })
                    .catch(error => {
                        console.log("Home: api nothing_print NG");
                    })
            }
        },
    }
}
</script>
