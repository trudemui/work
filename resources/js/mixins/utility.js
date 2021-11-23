import moment from "moment";

export default { 
    methods: {
        //ページ遷移時、開始位置を最上部にする
        scrollToTop() {
            console.log("Mixin: scrollToTop");
            window.scrollTo(0,0);
        },
        //令和〇〇年〇〇月
        changeYearMonth(date) {
            console.log("Mixin: changeYearMonth");
            const value1 = moment(date).format("YYYY");
            const value2 = value1 - 2018;
            const result = "令和" + value2 + "年" + moment(date).format("M月");
            return result;
        },
    },
    filters: {
        //令和〇〇年〇〇月
        yearMonth(date) {
            console.log("Mixin: yearMonth");
            const value1 = moment(date).format("YYYY");
            const value2 = value1 - 2018;
            const result = "令和" + value2 + "年" + moment(date).format("M月");
            return result;
        },
        //〇〇月〇〇日
        monthDay(date) {
            console.log("Mixin: monthDay");
            const result = moment(date).format("M月D日");
            return result;
        },
        //令和〇〇年
        onlyYear(date) {
            console.log("Mixin: onlyYear");
            const value1 = moment(date).format("YYYY");
            const value2 = value1 - 2018;
            const result = "令和" + value2 + "年";
            return result;
        }
    }
};