<template>
<div class="container">
	<div class="card text-center">
        <div class="card-body">
            <h3 class="title-margin mt-3 mb-5">トップページ</h3>
            <div class="d-flex flex-wrap justify-content-center mb-2">
                <router-link
                    v-show="own.is_admin || own.is_time_editor"
                    :to="{ name: 'setting' }"
                    class="btn btn-secondary btn-menu mr-3 mb-3"
                    dusk="setting"
                    >設定管理</router-link
                >
                <router-link
                    :to="{ name: 'content' }"
                    class="btn btn-outline-primary btn-menu mr-3 mb-3"
                    >新規投稿</router-link
                >

                <router-link
                    :to="{ name: 'profile' }"
                    class="btn btn-outline-warning btn-menu mb-3 mr-3 text-nowrap"
                    >プロフィール編集</router-link
                >
            </div>
        </div>

        <table class="table table-striped">
            <div v-for="(content, index) in sortContents"
                :key="index" 
                class="card bg-white clickable"
                @click="onResume(content)"
            >
                <h3 class="card-header">{{ content.title }}</h3>
                <div class="card-body">
                    <h5 class="card-subtitle mb-2 text-muted">{{ content.tag }}</h5>
                    <p class="card-text" style="white-space: pre-wrap;">{{ content.content_text }}</p>
                    <div class="btn-group" rorle="group">                    
                        <button class="btn btn-success btn-sm" @click="good">いいね</button>
                        <a href="#" class="card-link">コメントする</a>
                    </div>
                </div>
                <p>投稿日時：{{ content.created_at }}</p>
            </div>
        </table>

	</div>
</div>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        //
    },
    data () {
        return {
            contents: [],
            isLoading: false,
        }
    },
    mounted () {
        this.inspected_on = new moment().format('YYYY-MM-DD')
        this.getItems()
    },
    watch: {
        //
    },
    computed: {
        own() {
            return this.$store.state.user
        },
        sortContents() {
            return this.contents.slice().reverse();
        },
    },
    methods: {
        getItems: function () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get('/api/content'),
            ]).then(axios.spread((res1, res2) => {
                this.contents = res1.data
                this.isLoading = false
            }))
        },
        onResume(content) {
            this.$router.push({ name: 'content.resume', params: { contentId: content.id } })
        },
        good() {
            alert('いいね！')
        } 
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