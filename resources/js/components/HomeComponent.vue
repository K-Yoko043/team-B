<template>
<div class="container">
	<div class="card text-center">
        <div class="card-body">
            <h3 class="title-margin mt-3 mb-5">メニュー</h3>
            <div class="d-flex flex-wrap justify-content-center mb-2">
                <!-- <router-link :to="{ name: 'lesson1' }" class="btn btn-primary btn-menu mr-3 mb-3">レッスン1</router-link>
                <router-link :to="{ name: 'lesson2' }" class="btn btn-primary btn-menu mr-3 mb-3">レッスン2</router-link> -->
                <router-link :to="{ name: 'setting' }" class="btn btn-secondary btn-menu mr-3 mb-3">設定管理</router-link>
            </div>
        </div>
	</div>
</div>
</template>

<script>
export default {
    props: {
        //
    },
    data () {
        return {
            //
        }
    },
    mounted () {
        //
    },
    watch: {
        //
    },
    computed: {
        // own() {
        //     return this.$store.state.user
        // },
    },
    methods: {
        //
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
.btn-menu {
    height: 6rem;
    width: 9rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>