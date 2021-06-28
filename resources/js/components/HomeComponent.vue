<template>
<div class="container">
	<div class="card text-center mx-auto" style="max-width: 800px">

		<div class="fixed-top">
			<div class="form-inline justify-content-center">
				<div class="form-inline text-left">
					<div class="form-group">
						<input class="form-control" type="text" v-model="keyword" placeholder="キーワード検索">
					</div>
					<button type="button" class="btn btn-primary" @click="onSearch">検索</button>
				</div>

				<div class="dropdown text-right">
					<button class="btn btn-outline-dark dropdown-toggle" type="button"
						id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-sliders-h"></i>
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="#">
							<router-link
								:to="{ name: 'content' }"
								>新規投稿</router-link
							>
						</a>
						<a class="dropdown-item" href="#">
							<router-link
								:to="{ name: 'profile' }"
								>プロフィール編集</router-link
							>
						</a>
						<!-- クリックされたらonSearch? -->
					 	<a class="dropdown-item" href="#" @click="onSearch">フィロソフィー勉強会</a>
						<a class="dropdown-item" href="#" @click="onSearch">NG勉強会</a>
						<a class="dropdown-item" href="#" v-show="own.is_admin">
							<router-link
								:to="{ name: 'setting' }"
								>設定管理</router-link
							>
						</a>
					</div>
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
					<i class="fas fa-pencil-alt"></i><br>投稿する</router-link
				>

				<router-link
						:to="{ name: 'profile' }"
						class="btn btn-outline-success btn-lg mb-3 mr-3 text-nowrap"
						>
						<i class="fas fa-user-edit"></i><br>Myプロフィール</router-link
				>
			</div>
			<div style="float:right" class="notices">
      			<i class="fas fa-bell fa-2x" @click="onPush">
				  	<span v-if="push_count !== 0" class="count">{{ push_count }}</span>	
				</i>
    		</div>
		</div>
		<div>
		</div>
		<table class="table table-striped">
			<div v-for="content in sortContents"
				:key="content.id" 
				class="card bg-white border-info"
			>
				<h3 class="card-header">
					<img src="/storage/1623910044.柴犬.jpg" class="img-thumbnail" alt="">
					{{ content.user_name }}
					<i v-if="content.user_name === own.name"
						 class="far fa-edit clickable" @click="onResume(content)">
					</i>
				</h3>
				<div class="card-body">
					<h5 class="card-subtitle mb-2 text-muted">{{ content.tag }}</h5>
					<p class="card-text text-left" style="white-space: pre-wrap;">{{ content.content_text }}</p>
				</div>
				<p>
					投稿日時：{{ content.created_at }}<br>
					{{ content.comment_count }}件の返信。
				</p>
				<div class="card-footer btn-group" role="group"> 
					<button v-if="content.own_like_good === 0" class="btn btn-outline-primary" @click="onAddgood(content.id, 1)">
            			<a slot="icon" class="fas fa-thumbs-up fa-lg" style="color: #c0c0c0" outline="none"> </a>
            			{{ content.count_good }}
          			</button>
          			<button v-else class="btn btn-outline-primary" @click="onDeletegood(content.id, 1)">
            			<a slot="icon" class="fas fa-thumbs-up fa-lg" style="color: #00bfff" outline="none"> </a>
            			{{ content.count_good }}
          			</button>
          			<button v-if="content.own_like_heart === 0" class="btn btn-outline-danger" @click="onAddgood(content.id, 2)">
            			<a slot="icon" class="far fa-heart fa-lg" style="color: #ff0000" outline="none"> </a>
            			{{ content.count_heart }}
          			</button>
          			<button v-else class="btn btn-outline-danger" @click="onDeletegood(content.id, 2)">
            			<a slot="icon" class="fas fa-heart fa-lg" style="color: #ff0000" outline="none"> </a>
            			{{ content.count_heart }}
          			</button>
          			<button v-if="content.own_like_check === 0" class="btn btn-outline-success" @click="onAddgood(content.id, 3)">
            			<a slot="icon" class="far fa-check-square fa-lg" style="color: #c0c0c0" outline="none"> </a>
            			{{ content.count_check }}
          			</button>
          			<button v-else class="btn btn-outline-success" @click="onDeletegood(content.id, 3)">
            			<a slot="icon" class="fas fa-check-square fa-lg" style="color: #00ff00" outline="none"> </a>
            			{{ content.count_check }}
          			</button>
          			<button class="btn btn-outline-info btn-sm" data-toggle="collapse" @click="onAddrespond(content.id)">
            			<i class="far fa-lg fa-comment"></i> 返信する
          			</button>
				</div>
				<div v-if="content.comment_visusal === true" class="btn btn-outline-info btn-sm" @click="visual(content.comment_visusal, content.id)">
          			&#9650;返信を非表示。
        		</div>
        		<div v-if="content.comment_visusal === false" class="btn btn-outline-info btn-sm" @click="visual(content.comment_visusal, content.id)">
          			&#9660;返信を表示。
        		</div>
				<div v-if="content.comment_visusal === true">
					<div v-for="respond in responds" :key="respond.id">
						<div v-if="content.id === respond.content_id">
							<hr />
              				<p>返信者:{{ respond.user_name }}</p>
              				<p>本文:{{ respond.text }}</p>
              				<div v-if="respond.user_id === userid" class="comment">
								<button class="btn btn-outline-warning btn-sm" @click="onEditrespond(respond.id, content.id)">
                  					編集
                				</button>
                				<button class="btn btn-outline-danger btn-sm" @click="onDeleterespond(respond.id, content.id)">
                 	 				削除
                				</button>
							</div>
						</div>
					</div>
				</div>
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
			gorillers: [],
			responds:[],
			notices:[],
			content_text: '',
			tag: '',
			good_count: '',
			keyword: '',
			is_liked: false,
			isLoading: false,
			sort: {
					key: 'id',
					isAsc: true,
			},
			push: '',
      		push_count: 0,
      		check: 0,
			userid: null,
      		username: '',
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
			if (this.keyword != "") {
				alert("「" + this.keyword +"」を含むものを検索しました。")
			}
			const { data } = await axios.get('/api/content', {
				params: {
					sort: this.sort,
					keyword: this.keyword,
					content_text: this.content_text,
					tag: this.tag,
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
			const api2 = axios.create()
			axios.all([
				api2.get('/api/notice/userid'),
			]).then(axios.spread((res1, res2) => {
				this.userid = res1.data
				this.isLoading = false
			}))
			const api3 = axios.create()
			axios.all([
				api3.get('/api/notice/username'),
			]).then(axios.spread((res1, res2) => {
				this.username = res1.data
				this.isLoading = false
			}))
			const api4 = axios.create()
			axios.all([
				api4.get('/api/notice'),
			]).then(axios.spread((res1, res2) => {
				this.notices = res1.data
				this.isLoading = false
				for (let i = 0; i < this.notices.length; i++){
					if(this.notices[i].content_userid === this.userid &&this.notices[i].content_userid !== this.notices[i].respond_userid){
						for (let j = 0; j < i; j++) {
							if(this.notices[i].content_id === this.notices[j].content_id &&this.notices[i].respond_username === this.notices[j].respond_username){
								this.check = 1
							}
						}
						if (this.check === 0) {
                			this.push +=this.notices[i].content_id +'のツイートに対して' +this.notices[i].respond_username +'から返信が来ました。\n'
                			this.push_count += 1
              			}
              			this.check = 0
					}
				}
			}))
			const api5 = axios.create()
      			axios.all([api5.get('/api/respond')]).then(
        			axios.spread((res1, res2, res3, res4) => {
          			this.responds = res1.data
          			this.isLoading = false
        		}),
      		)
		},
		onSearch() {
			this.$store.state.barcode = ''
      		this.offset = 0
      		this.currentPage = 0
      		this.getItems()
		},
		onAddrespond:function(contentId){
			this.$router.push({ name: 'respond.create', params: { contentId: contentId } })
		},
		onEditrespond:function(respondId,contentId){
			this.$router.push({ name: 'respond.show', params: { contentId: contentId,respondId:respondId} })
		},
		onDeleterespond: function(respondId, contentId) {
      		if (!confirm('削除してもよろしいですか？')) {
        		return
      		}
      		const _this = this
      		axios.delete('/api/notice/' + this.userid + '/' + contentId)
      		axios
        		.delete('/api/respond/' + respondId)
        		.then(function(resp) {
          		alert('削除しました。')
        		})
        		.catch(function(resp) {
          			console.log(resp)
        		})
        		.finally(function() {
          		//
        		})
      		location.reload()
    	},
		onAddgood: function(tweetId, mark) {
      		this.invalid = false
      		this.errorMessage = ''
      		const _this = this
      		axios
        		.get('/api/content/addgood/' + tweetId + '/' + mark)
        		.then(function(resp) {})
        		.catch(function(resp) {
          			console.log(resp)
        	})
      		if (mark === 1) {
        		this.contents[tweetId - 1].own_like_good = 1
        		this.contents[tweetId - 1].count_good += 1
      		}
      		if (mark === 2) {
        		this.contents[tweetId - 1].own_like_heart = 1
        		this.contents[tweetId - 1].count_heart += 1
      		}
      		if (mark === 3) {
        		this.contents[tweetId - 1].own_like_check = 1
        		this.contents[tweetId - 1].count_check += 1
      		}
    	},
		onDeletegood: function(tweetId, mark) {
      		const _this = this
      		axios
        		.delete('/api/content/deletegood/' + tweetId + '/' + mark)
        		.then(function(resp) {})
        		.catch(function(resp) {
          			console.log(resp)
        	})
        	.finally(function() {
          		//
        	})
      		if (mark === 1) {
        		this.contents[tweetId - 1].own_like_good = 0
        		this.contents[tweetId - 1].count_good -= 1
      		}
      		if (mark === 2) {
        		this.contents[tweetId- 1].own_like_heart = 0
        		this.contents[tweetId - 1].count_heart -= 1
      		}
      		if (mark === 3) {
        		this.contents[tweetId - 1].own_like_check = 0
        		this.contents[tweetId - 1].count_check -= 1
      		}
    	},
		onResume(content) {
			this.$router.push({ 
				name: 'content.resume', 
				params: { contentId: content.id }
			})
		},
		visual: function(visable, number) {
      		visable = !visable
      		this.contents[number - 1].comment_visusal = visable
		},
		onPush: function() {
      		if (this.push === '') {
        		alert('通知はありません。')
      		} else {
        		alert(this.push)
        		axios.delete('/api/notice/' + this.userid)
        		this.isLoading = false
      		}
    	},
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
.comment {
  padding: 10px;
}
.count {
  	font-size:16px;
}
.notices {
  position: relative; /* 基準値とする */
}
.notices span {
  position: absolute; /* 相対位置に指定 */
  top: -10px; /* 表示位置を上から-10pxの位置にする */
  left: calc(100% - 10px); /* 表示位置を右から内側に10pxの位置 */
  color: #fff; /* 文字色を白に指定 */
  //font-weight: bold; /* 太文字にする */
  line-height: 20px; /* 行の高さを指定 */
  font-size: 1px;
  text-align: center; /* 文字を中央揃えにする */
  background: #ff0000; /* 背景色を赤色に指定 */
  border-radius: 10px; /* line-heightの半分の角丸を指定 */
  min-width: 20px; /* 最低幅を指定 */
  padding: 0 3px; /* 左右に少しだけ余白を設定 */
  box-sizing: border-box; /* 計算しやすいように */
}
</style>