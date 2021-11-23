<template>
    <div class="flex justify-center pb-12" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="flex flex-col items-stretch sm:items-center w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center w-full h-14 bg-blue-normal">
                <h3 class="text-xl sm:text-2xl font-bold text-white-normal">テンプレート登録</h3>
            </div>
            <div class="flex justify-center mt-16 mx-5 sm:mx-8 text-lg">
                <ul class="flex flex-wrap justify-center items-center sm:items-stretch">
                    <li class="w-full sm:w-auto mb-8">
                        <p>分類</p>
                        <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" :options="options1" v-model="selected1">
                            <span slot="no-options">検索候補がありません。</span>
                        </v-select>
                    </li>
                    <li class="w-full sm:w-auto mb-8">
                        <p>項目</p>
                        <v-select class="w-full sm:w-110 md:w-120 lg:w-130 h-10 py-0.5 shadow-md" label="category_name" :options="options2" v-model="selected2">
                            <span slot="no-options">検索候補がありません。</span>
                        </v-select>
                    </li>
                    <li class="w-full sm:w-auto mb-10">
                        <button class="w-full sm:w-110 md:w-120 lg:w-130 h-12 text-lg sm:text-xl bg-white-dark bg-blue-sky text-white-normal focus:outline-none focus:shadow-outline" @click="searchDatas(true)">検索</button>
                    </li>
                    <li class="w-full sm:w-auto mb-12">
                        <ul class="flex justify-between">
                            <li class="w-full mr-2 sm:mr-0">
                                <button class="w-full sm:w-56 h-12 mr-0 sm:mr-12 -mb-9 text-lg sm:text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline" @click="deleteDatas">削除</button>
                            </li>
                            <li class="w-full ml-2 sm:ml-0">
                                <button class="w-full sm:w-56 h-12 text-lg sm:text-xl -mb-9 bg-red-normal text-white-normal focus:outline-none focus:shadow-outline" @click="addDatas">新規登録</button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="flex justify-center mx-8 mb-14">
                <table v-if="searchId === 0" class="table-auto text-base lg:text-lg text-center table-scroll-custom">
                    <thead>
                        <tr class="h-10">
                            <th class="border">
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium mr-2">作物名</p>
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium mr-2">品種名</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="cursor-pointer">
                        <tr v-for="(item, key) in varietyDatas" :key="key" class="h-14 hover:bg-blue-light">
                            <td class="border">
                                <label class="ECM_CheckboxInput">
                                    <input class="ECM_CheckboxInput-Input" type="checkbox" :id="item.id" :value="item.id" v-model="checkedItems" @change="getCheckId">
                                    <span class="ECM_CheckboxInput-DummyInput"></span>
                                </label>
                            </td>
                            <td class="border px-4 py-2 table-width" @click="editDatas(item)">{{item.crop.crop_name}}</td>
                            <td class="border px-4 py-2 table-width" @click="editDatas(item)">{{item.variety_name}}</td>
                        </tr>
                    </tbody>
                </table>
                <table v-else-if="searchId === 1" class="table-auto text-base lg:text-lg text-center table-scroll-custom">
                    <thead>
                        <tr class="h-10">
                            <th class="border">
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium mr-2">名前</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, key) in templateDatas" :key="key" class="h-14 hover:bg-blue-light">
                            <td class="border">
                                <label class="ECM_CheckboxInput">
                                    <input class="ECM_CheckboxInput-Input" type="checkbox" :id="item.id" :value="item.id" v-model="checkedItems" @change="getCheckId">
                                    <span class="ECM_CheckboxInput-DummyInput"></span>
                                </label>
                            </td>
                            <td class="border px-4 py-2 w-full" @click="editDatas(item)">{{item.body}}</td>
                        </tr>
                    </tbody>
                </table>
                <table v-else class="table-auto text-lg text-center">
                    <thead>
                        <tr class="h-10">
                            <th class="border px-4 py-2">
                                <p class="font-medium">&nbsp;</p>
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium">名前</p>
                            </th>
                            <th class="border px-4 py-2">
                                <p class="font-medium">編集</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="h-20 mini:h-14">
                            <td class="border"></td>
                            <td class="border px-4 py-2"></td>
                            <td class="border px-4 py-2 relative"></td>
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
        "v-paginate": vPaginate,
    },
    props:{
        "selectedDatas": {
            required: false,
            default: null
        },
        "selectedId": {
            required: false,
            default: null
        }
    },
    data: function() {
        return {
            options1: ["生産行程管理記録"],
            options2: [],
            resultTableItems: [],
            checkedItems: [],
            varietyDatas: {},
            cropDatas: {},
            templateDatas: {},
            parPage: 10,
            currentPage: 1,
            lastPage: 0,
            selected1: null,
            selected2: null,
            selected3: null,
            selected4: null,
            isPaginate: true,
            isTemplateEdit: false,
            searchId: null,
            checkedId: null
        }
    },
    computed: {
        //ページネーション
        getTableItems: function() {
            console.log("Template: getTableItems");
            let current = this.currentPage * this.parPage;
            let start = current - this.parPage;
            this.isPageCount = true;
            this.resultTableItems = resultTableItems;
            return resultTableItems.slice(start, current);
        },
        //ページネーション
        getPageCount: function() {
            console.log("Template: getPageCount");
            return this.lastPage;
        },
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("Template: created");
        this.selected2 = {id: 0, category_name: ""};
    },
    mounted() {
        console.log("Template: mounted");
        if(this.selectedDatas) {
            //新規登録や編集から戻ってきたときに、検索結果を保持する
            if(this.selectedDatas.article.category_name === "作物名・品種名") {
                axios
                    .get(`/api/templates?page=${this.currentPage}`)
                    .then(response => {
                        console.log("Template: api templates OK");
                        const crops = response.data.crops;
                        const varieties = response.data.varieties;
                        this.varietyDatas = varieties.data;
                        this.cropDatas = crops.data;
                        this.currentPage = varieties.current_page;
                        this.lastPage = varieties.last_page;
                        this.selected1 = this.selectedDatas.class;
                        this.selected2 = {
                            id: this.selectedDatas.article.id,
                            category_name: this.selectedDatas.article.category_name
                        };
                        this.searchId = 0;
                    })
                    .catch(error => {
                        console.log("Template: api templates NG");
                    });
            } else if(this.selectedDatas.article.category_name !== "作物名・品種名") {
                console.log(this.selectedDatas.article.id);
                let value = {id: this.selectedDatas.article.id};
                axios
                    .post(`/api/templates/select_table?page=${this.currentPage}`, value)
                    .then(response => {
                        console.log("Template: api select_table OK");
                        const templates = response.data.template;
                        this.templateDatas = templates.data;
                        this.currentPage = templates.current_page;
                        this.lastPage = templates.last_page;
                        this.selected1 = this.selectedDatas.class;
                        this.selected2 = {
                            id: this.selectedDatas.article.id,
                            category_name: this.selectedDatas.article.category_name
                        };
                        this.searchId = 1;
                    })
                    .catch(error => {
                        console.log("Template: api select_table NG");
                    });
            }
        } else {
            //通常時の処理
            this.selected1 = "生産行程管理記録";
            //DBによってid変更しないといけないかも
            this.selected2 = {id: 7, category_name: "作物名・品種名"};
            axios
                .get(`/api/templates?page=${this.currentPage}`)
                .then(response => {
                    console.log("Template: api templates OK");
                    const crops = response.data.crops;
                    const varieties = response.data.varieties;
                    this.varietyDatas = varieties.data;
                    this.cropDatas = crops.data;
                    this.currentPage = varieties.current_page;
                    this.lastPage = varieties.last_page;
                    this.searchId = 0;
                })
                .catch(error => {
                    console.log("Template: api templates NG");
                });
        }
        //v-selectオプション取得
        axios
            .get("api/categories")
            .then(response => {
                console.log("Template: api categories OK");
                //作業内容
                const categories = response.data.categories;
                let values = [];
                    categories.forEach(function(data) {
                        values.push({
                            id: data.id,
                            category_name: data.category_name
                        })
                    })
                this.options2 = values;
            })
            .catch(error => {
                console.log("Template: api categories NG");
            })
    },
    methods: {
        //ページネーションをクリック
        clickCallback: function (pageNum) {
            console.log("Template: clickCallback");
            this.currentPage = Number(pageNum);
            if(this.searchId === 0) {
                this.getCustomDatas();
            } else if (this.searchId === 1) {
                this.getNormalDatas();
            }
        },
        //現在ページ（作物名・品種名）のデータを表示
        getCustomDatas() {
            console.log("Template: getCustomDatas");
            axios
                .get(`/api/templates?page=${this.currentPage}`)
                .then(response => {
                    console.log("Template: api templates OK");
                    const crops = response.data.crops;
                    const varieties = response.data.varieties;
                    this.varietyDatas = varieties.data;
                    this.cropDatas = crops.data;
                    this.currentPage = varieties.current_page;
                    this.lastPage = varieties.last_page;
                })
                .catch(error => {
                    console.log("Template: api templates NG");
                });
        },
        //現在ページ（作物名・品種名以外）のデータを表示
        getNormalDatas() {
            console.log("Template: getNormalDatas");
            let value = {id: this.selected2.id};
            axios
                .post(`/api/templates/select_table?page=${this.currentPage}`, value)
                .then(response => {
                    console.log("Template: api select_table OK");
                    const templates = response.data.template;
                    this.templateDatas = templates.data;
                    this.currentPage = templates.current_page;
                    this.lastPage = templates.last_page;
                    this.searchId = 1;
                    this.checkedItems = [];
                })
                .catch(error => {
                    console.log("Template: api select_table NG");
                });
        },
        //新規登録
        addDatas(item) {
            console.log("Template: addDatas");
            if(this.selected1 && this.selected2 && this.selected2.id !== 0) {
                let selectedDatas = {};
                let selectedId = "";
                selectedDatas = {
                    class: this.selected1,
                    article: this.selected2
                };
                selectedId = item.id;
                console.log(selectedId);
                this.$router.push({
                    name: "templateAdd", 
                    params: { 
                        selectedDatas: selectedDatas,
                        selectedId: selectedId
                    }
                });
                this.scrollToTop();
            } else {
                console.log("Template: addDatas notSelected");
                alert("「分類」と「項目」を選択してください。");
            }
        },
        //編集
        editDatas(item) {
            console.log("Template: editDatas");
            let selectedDatas = {};
            let selectedId = "";
            if(this.selected2.id === 7) {
                selectedDatas = {
                    id: item.id,
                    class: this.selected1,
                    article: this.selected2,
                    name1: item.body,
                    name2: item.variety_name ? item.variety_name : "",
                    name3: item.crop.crop_name ? item.crop.crop_name : "",
                };
            } else if(this.selected2.id !== 7) {
                selectedDatas = {
                    id: item.id,
                    class: this.selected1,
                    article: this.selected2,
                    name1: item.body,
                    name2: item.variety_name ? item.variety_name : "",
                };
            }
            selectedId = item.id;
            this.$router.push({
                name: "templateEdit", 
                params: { 
                    selectedDatas: selectedDatas,
                    selectedId: selectedId
                }
            });
            this.scrollToTop();
        },
        //削除
        deleteDatas() {
            console.log(this.checkedItems.length);
            if(this.checkedItems.length > 0) {
                if(!confirm("選択した項目を削除してもよろしいですか？")) {
                    return;
                } else {
                    axios
                        .post(`/api/templates/delete`, {id: this.checkedItems[0], number: this.searchId})
                        .then(response => {
                            console.log("Templates: api delete OK");
                            if(this.searchId === 0) {
                                this.getCustomDatas();
                            } else if (this.searchId === 1) {
                                this.getNormalDatas();
                            }
                        })
                        .catch(error => {
                            console.error("Templates: api delete NG");
                        });
                }
            } else {
                console.log("Home: delete notSelected");
                alert("削除する項目を選択してください。");
            }
        },
        //検索
        searchDatas(firstSearch) {
            console.log("Template: searchDatas");
            if(this.selected1 && this.selected2 && this.selected2.id !== 0) {
                if(firstSearch === true) {
                    console.log("Template: searchDatas -> firstSearch");
                    this.currentPage = 1;
                    this.isPaginate = false;
                }
                if(this.selected2.category_name === "作物名・品種名") {
                    this.getCustomDatas();
                    this.searchId = 0;
                    this.checkedItems = [];
                    this.isPaginate = true;
                    return;
                } else {
                    let value = {id: this.selected2.id};
                    axios
                        .post(`/api/templates/select_table?page=${this.currentPage}`, value)
                        .then(response => {
                            console.log("Template: api select_table OK");
                            const templates = response.data.template;
                            this.templateDatas = templates.data;
                            this.currentPage = templates.current_page;
                            this.lastPage = templates.last_page;
                            this.searchId = 1;
                            this.checkedItems = [];
                            this.isPaginate = true;
                        })
                        .catch(error => {
                            console.log("Template: api select_table NG");
                        });
                }
            } else {
                console.log("Template: addDatas notSelected");
                alert("「分類」と「項目」を選択してください。");
            }
        },
        //チェックボックスのid取得
        getCheckId() {
            console.log("Template: getCheckId");
            if(this.checkedItems.length > 1) {
                this.checkedItems.shift();
            }
            if(this.checkedItems[0]) {
                this.checkedId = this.checkedItems[0];
            }
        }
    }
}
</script>

<style scoped>
.vs__clear {
    display: none;
}
</style>