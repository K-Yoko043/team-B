<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">投稿を作成</span>
                        </div>
                        <div class="align-self-center mr-3">
                            <button type="button" class="btn btn-primary" @click="onStore"><i class="fas fa-plus"></i> 投稿</button>
                            <!-- ボタンの表示・非表示は後からやる -->
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>タイトル</label>
                        <input v-model="content.title" type="text" class="form-control" placeholder="タイトルを入力してください。">
                    </div>

                    <div class="form-group">
                        <label>タグ</label>
                        <select v-model="content.tag" class="form-control">
                            <option>フィロソフィー勉強</option>
                            <option>NG勉強会</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <textarea v-model="content.content_text" class="form-control" style="height: 300px;" placeholder="本文内容"></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import moment from 'moment';
export default {
    props: [
        
    ],
    data () {
        return {
            content: {
              goriller_id: '',
              tag: '',
              title: '',
              content_text: '',  
            },
            isLoading: false,
            is_text: '',
        }
    },
    created () {
      this.getItems()  
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        getItems: function () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get('/api/content'),
            ]).then(axios.spread((res1, res2, res3, res4) => {
                this.contents = res1.data
                
                this.isLoading = false
            }))
        },

        onStore: function () {
            let _this = this
            axios.post('/api/content', {
                content: this.content,
            })
            .then(function (resp) {
                if (resp.data.result) {
                    alert('投稿しました。')
                    _this.$router.go(-1)
                } else {
                    _this.errorMessage = resp.data.errorMessage
                    _this.invalid = true
                }
            })
            .catch(function (resp) {
                console.log(resp)
            });
        },

        onCreate: function () {
            this.$router.push({ name: 'content.create' })
        },
        onShow: function (goriller_id) {
            this.$router.push({ name: 'goriller.show', params: {goriller_id: goriller_id} })
        },
        onBack: function () {
            this.$router.go(-1)
        },
    },
    components: {
        // Loading
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>