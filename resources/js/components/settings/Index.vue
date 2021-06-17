<template>
<div class="container">
	<div class="card text-center">
        <div class="card-body">
            <back-button />
            <h3 class="title-margin mt-3 mb-5">ゴリラー情報管理</h3>
            <div class="d-flex justify-content-center mb-2">
                <router-link :to="{ name: 'goriller' }" class="btn btn-primary btn-menu mr-3 mb-3">ゴリラー</router-link>
            </div>
        </div>
	</div>
</div>
</template>

<script>
export default {
    props: [
        //
    ],
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
