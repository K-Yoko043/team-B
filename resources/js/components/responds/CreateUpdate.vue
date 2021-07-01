/* eslint-disable */
<template>
  <div class="card text-center mx-auto" style="max-width: 800px">
    <div class="mr-auto">
      <nobr>
        <span class="span-header">{{ title }}</span>
        <div class="container bg-light">
          <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
        </div>
      </nobr>
    </div>
    <br />
    <table class="table table-striped">
      <div :key="content.id" class="card bg-white border-info">
        <h3 class="card-header">投稿者:{{ content.user_name }}</h3>
        <div class="card-body">
					<h5 class="card-subtitle mb-2 text-muted">{{ content.tag }}</h5>
					<p class="card-text text-left" style="white-space: pre-wrap;">{{ content.content_text }}</p>
				</div>
				<p>投稿日時：{{ content.created_at }}</p>
      </div>
    </table>
    <div class="form-group">
      <div class="form-group">
        <textarea v-model="respond.text" class="form-control" style="height: 200px;" placeholder="返信内容"></textarea>
      </div>
    </div>
    <div class="reply">
      <button type="button" class="btn btn-dark bg-left" @click="onBack">キャンセル</button>
      <button type="button" class="btn btn-primary bg-right" @click="onStore(content.user_id)">送信する</button>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
export default {
  components: {
    // Loading
  },
  props: ['contentId', 'respondId'],
  data() {
    return {
      respond: {
        id: null,
        content_id: null,
        text: '',
        user_id: null,
      },
      content: {
        id: null,
        text: '',
        user_id: null,
        user_name: '',
        own_like: null,
      },
      invalid: false,
      errorMessage: '',
      isLoading: false,
      fullPage: false,
      userid: null,
    }
  },
  computed: {
    title: function() {
      return this.mode === 'create' ? '返信の新規作成' : '返信の編集'
    },
    mode: function() {
      return this.respondId ? 'update' : 'create'
    },
  },
  watch: {
    //
  },
  created() {
    this.getItems()
  },
  methods: {
    getItems: function() {
      this.isLoading = true
      const api = axios.create()
      const api2 = axios.create()
      axios.all([api2.get('/api/content/' + this.contentId)]).then(
        axios.spread((res5, res6, res7, res8) => {
          this.content = res5.data
          this.isLoading = false
        }),
      )
      const api3 = axios.create()
      axios.all([api3.get('/api/notice/userid')]).then(
        axios.spread((res1, res2, res3, res4) => {
          this.userid = res1.data
          this.isLoading = false
        }),
      )
      if (this.mode === 'create') {
        this.isLoading = false
      } else {
        axios.all([api.get('/api/respond/' + this.respondId)]).then(
          axios.spread((res1, res2, res3, res4) => {
            this.respond = res1.data
            this.isLoading = false
          }),
        )
      }
    },
    onStore: function(content_userId) {
      this.respond.content_id = this.contentId
      this.invalid = false
      this.errorMessage = ''
      const _this = this
      if (this.mode === 'create') {
        if (this.respond.text === '') {
          alert('文字が入力されていません。')
          return
        }
        axios.post('/api/notice/' + this.contentId + '/' + content_userId)
        axios
          .post('/api/respond', {
            respond: this.respond,
          })
          .then(function(resp) {
            if (resp.data.result) {
              alert('登録しました。')
              _this.$router.go(-1)
            } else {
              _this.errorMessage = resp.data.errorMessage
              _this.invalid = true
            }
          })
          .catch(function(resp) {
            console.log(resp)
          })
        this.isLoading = false
      } else {
        axios
          .put('/api/respond/' + this.respond.id, {
            respond: this.respond,
          })
          .then(function(resp) {
            if (resp.data.result) {
              alert('更新しました。')
              _this.$router.go(-1)
            } else {
              _this.errorMessage = resp.data.errorMessage
              _this.invalid = true
            }
          })
          .catch(function(resp) {
            console.log(resp)
          })
      }
    },
    onBack() {
      this.$router.go(-1)
    },
  },
}
</script>

<style lang="scss" scoped>
@import 'resources/sass/variables';
.btn-menu {
	height: 6rem;
	width: 9rem;
	font-size: 1rem;
	display: flex;
	align-items: center;
	justify-content: center;
}
.reply {
  padding-top: 30px;
  display: flex;
  justify-content: space-evenly;
}
</style>
