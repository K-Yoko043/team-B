<template>
<div class="container">
	<div class="card text-center mx-auto" style="max-width: 800px">

        <div class="form-inline">
            <div class="form-inline text-left">
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="キーワード検索">
                </div>
                <button type="button" class="btn btn-primary">検索</button>
            </div>

            <div class="dropdown text-right">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sliders-h"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">新規作成</a>
                    <a class="dropdown-item" href="#">プロフィール編集</a>
                    <a class="dropdown-item" href="#">フィロソフィー勉強会</a>
                    <a class="dropdown-item" href="#">NG勉強会</a>
                    <a class="dropdown-item" href="#">キーワード検索</a>
                    <a class="dropdown-item" href="#" v-show="own.is_admin">
                        <router-link
                            :to="{ name: 'setting' }"
                            >設定管理</router-link
                        >
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h3 class="title-margin mt-3 mb-5">トップページ</h3>
            <div class="d-flex flex-wrap justify-content-center mb-2">
                <router-link
                    :to="{ name: 'content' }"
                    class="btn btn-outline-primary btn-lg mr-3 mb-3"
                    >
                    <i class="fas fa-plus-circle"></i><br>新規投稿</router-link
                >

                <router-link
                    :to="{ name: 'profile' }"
                    class="btn btn-outline-success btn-lg mb-3 mr-3 text-nowrap"
                    >
                    <i class="fas fa-user-edit"></i><br>Myプロフィール</router-link
                >
            </div>
        </div>

        <table class="table table-striped">
            <div v-for="content in sortContents"
                :key="content.id" 
                class="card bg-white border-info"
                @click="onResume(content)"
            >
                <h3 class="card-header">
                    <img src="/storage/1623712333.柴犬.jpg" class="img-thumbnail" alt="">
                    {{ content.user_name }}
                </h3>
                <div class="card-body clickable">
                    <h5 class="card-subtitle mb-2 text-muted">{{ content.tag }}</h5>
                    <p class="card-text text-left" style="white-space: pre-wrap;">{{ content.content_text }}</p>
                </div>
                <p>投稿日時：{{ content.created_at }}</p>
                <div class="card-footer btn-group" role="group">                   
                    <button class="btn btn-outline-success btn-sm" @click="good">
                        <i class="far fa-lg fa-thumbs-up"></i> いいね
                    </button>
                    <button class="btn btn-outline-info btn-sm" data-toggle="collapse" data-target="#reply" @click="onReply">
                        <i class="far fa-lg fa-comment"></i> コメントする
                    </button>
                    <div id="reply" class="collapse">aaaa</div>
                </div>
            </div>
        </table>

	</div>
    <!-- <p id="page-top"><a href="#">Page Top</a></p> -->
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
            gorillers: [],
            content_text: '',
            isLoading: false,

            sort: {
                key: 'id',
                isAsc: true,
            }
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
        async getInit() {
            if (this.$store.state.barcode) {
                this.content_text = ''
                this.getItems()
            }
        },

        async getItems () {
            this.isLoading = true;
            const { data } = await axios.get('/api/content', {
                params: {
                    sort: this.sort,
                    content_text: this.content_text,
                },
            })
            this.content_text = data.content_text
            this.totalItems = data.total_items
            this.contents = data.contents
            this.isLoading = false

            const api = axios.create()
            axios.all([
                api.get('/api/goriller'),
            ]).then(axios.spread((res1, res2) => {
                this.gorillers = res1.data
                this.isLoading = false
            }))
        },


        onResume(content) {
            this.$router.push({ name: 'content.resume', params: { contentId: content.id } })
        },
        good() {
            alert('いいね！')
        },
        onReply: function () {
            this.$router.push({ name: 'reply' })
        },
        comment: function () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get
            ])
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
.img-thumbnail {
    width: 15%;
    height: 15%;
    max-width: 100%;
    max-height: 100%;
    border-radius: 100%;
}

.card {
    margin-bottom: 50px;
}
</style>