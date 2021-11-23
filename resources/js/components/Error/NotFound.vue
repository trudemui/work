<template>
    <div class="flex justify-center pb-12 mleft-02" :class="{ 'is-disabled': isActiveSidebar }">
        <div class="w-11/12 mt-12 bg-white-normal">
            <div class="flex justify-center items-center h-14 bg-blue-normal">
                <h3 class="text-2xl font-bold text-white-normal">404 NotFound</h3>
            </div>
            <div class="flex flex-col justify-center items-center mt-20 px-5">
                <p class="text-2xl underline">お探しのページが見つかりません</p>
                <p class="mt-7 text-base">このページが常に表示される場合は、</p>
                <p class="text-base">開発者までご連絡ください。</p>
            </div>
            <div class="flex justify-center mt-20">
                <router-link to="/" @click.native="scrollToTop">
                    <button class="w-40 h-12 text-xl bg-white-dark text-white-normal focus:outline-none focus:shadow-outline">戻る</button>
                </router-link>
            </div>
            <div class="absolute inset-0 hidden" :class="{ 'is-disabled': isActiveSidebar }"></div>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {}
    },
    computed: {
        //ハンバーガーメニュー判定
        isActiveSidebar() {
            console.log("NotfFound: isActiveSidebar");
            return this.$store.state.isActiveSidebar;
        }
    },
    created() {
        console.log("NotfFound: created");
    },
    methods: {}
}
</script>
