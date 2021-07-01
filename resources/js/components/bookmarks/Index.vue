<template>
    <div class="container">
        <div class="card text-center mx-auto" style="max-width: 800px">
            <div class="fixed-top">
                <div class="form-inline justify-content-center"></div>
            </div>
            <div class="card-body">
                <div
                    class="d-flex flex-wrap justify-content-center mb-2"
                    style="float:left"
                >
                    <button
                        class="btn btn-outline-dark"
                        onclick="location.href='./'"
                    >
                        戻る
                    </button>
                </div>

                <div class="dropdown" style="float:right">
                    <button
                        class="btn btn-outline-dark dropdown-toggle"
                        type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-sliders-h"></i>
                    </button>
                    <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                    >
                        <a class="dropdown-item" href="#">
                            <router-link :to="{ name: 'content' }"
                                >新規投稿</router-link
                            >
                        </a>
                        <a class="dropdown-item" href="./">トップに戻る</a>
                    </div>
                </div>
            </div>

            <table class="table table-striped" v-if="check === 1">
                <div v-for="content in contents" :key="content.id">
                    <div v-if="content.is_bookmark === 1" class="card bg-white border-info">
                        <h3 class="card-header">
                            <img src="/storage/1623910044.柴犬.jpg" class="img-thumbnail" alt=""/>
                                {{ content.user_name }}
                            <i v-if="content.is_bookmark == 0" class="far fa-bookmark" style="color:#04B4AE; float:right;"></i>
                            <i v-else class="fas fa-bookmark" @click="deletebook(content.id)" v-cloak style="color:#04B4AE; float:right;"><p class="pop">ブックマークを外す</p></i>
                        </h3>
                        <div class="card-body">
                            <h5 class="card-subtitle mb-2 text-muted">
                                {{ content.tag }}
                            </h5>
                            <p class="card-text text-left" style="white-space: pre-wrap;">
                                {{ content.content_text }}
                            </p>
                        </div>
                        <p>投稿日時：{{ content.created_at }}</p>
                    </div>
                </div>
            </table>
            <div v-else v-cloak>
                <p v-cloak>{{ error_message }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: {
        //
    },
    data() {
        return {
            contents: [],
            test: "",
            name: "",
            check: 0,
            error_message: "ブックマークに追加してみましょう！"
        };
    },
    mounted() {
        this.getItems();
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        getItems: function() {
            this.isLoading = true;
            const api = axios.create();
            axios.all([api.get("/api/Bookmark/")]).then(
                axios.spread((res1, res2, res3, res4) => {
                    this.contents = res1.data;

                    for (let i in this.contents) {
                        if (this.contents[i].is_bookmark === 1) {
                            this.check = 1;
                        }
                    }

                    this.isLoading = false;
                })
            );
            const api2 = axios.create();
            axios.all([api2.get("/api/Bookmark/getuser")]).then(
                axios.spread((res1, res2, res3, res4) => {
                    this.name = res1.data;

                    this.isLoading = false;
                })
            );
        },
        deletebook: function(contentid) {
            axios.delete("/api/content/delete/" + contentid);
            location.reload();
        },
        onBack: function() {
            this.$router.go(-1);
        }
    }
};
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


.pop {
    position: absolute;
    top: -30px;
    right: -10px;
    font-size:15px;

    /*非表示にしておきます*/
    display: none;
    opacity: 0;

    /*表示スタイルを指定します*/
    padding: 5px;
    border-radius: 5px;
    color: #ffffff;
    background-color: #04B4AE;
    /*影をつけて見栄えを良くします*/
    box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.5),
        inset 0 1px 0 rgba(255, 255, 255, 0.8),
        inset 1px 0 0 rgba(255, 255, 255, 0.3),
        inset -1px 0 0 rgba(255, 255, 255, 0.3),
        inset 0 -1px 0 rgba(255, 255, 255, 0.2);

    /*アニメーションを指定します*/
    animation-duration: 0.3s;
    animation-name: show-balloon;
}

.fa-bookmark:hover .pop {
    display: inline-block;
    opacity: 1;
    top: -40px;
}

.pop::before {
    /*吹き出し部分の三角形を表示します*/
    content: "";
    position: absolute;
    top: 97%;
    right: 40px;
    border: 6px solid transparent;
    border-top: 6px solid #04B4AE;
}

[v-cloak] {
    display: none;
}
</style>
