<template>
<div class="container">
	<div class="card text-center mx-auto">
		<div class="card-body">
			<div class="form-inline">
				<div class="form-inline top" style="overflow: hidden;">
					<div class="form-group">
						<input class="form-control" type="text" v-model="keyword" placeholder="ユーザ検索">
					</div>
					<button type="button" class="btn btn-primary" @click="onSearch">
						<i class="fas fa-search"></i>
					</button>
				</div>

				<div class="dropdown dropleft col text-right">
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
					 	<button class="dropdown-item" type="button" @click="onTagSearch('フィロソフィー')">フィロソフィー勉強会</button>
						<button class="dropdown-item" type="button" @click="onTagSearch('NG')">NG勉強会</button>
						<button class="dropdown-item" type="button" @click="onTagSearch('')" v-if="search">絞り込み解除</button>
						<a class="dropdown-item" type="button" href="./bookmark" @click="onSearch">ブックマーク</a>
						<a class="dropdown-item" href="#" v-show="own.is_admin">
							<router-link
								:to="{ name: 'setting' }"
								>設定管理</router-link
							>
						</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<img class="img mt-3" src="/image/logo.jpg">
			</div>
		</div>
		<div class="card-body">
			<div class="notices">
				<h3 class="title-margin mt-3 mb-5">トップページ
					<i class="fas fa-bell fa-1x" @click="onPush" style="float:right">
				  		<span v-if="push_count !== 0" class="count">{{ push_count }}</span>	
					</i>
				</h3>
    		</div>
			<div class="d-flex flex-wrap justify-content-center mb-2">
				<router-link
					:to="{ name: 'content' }"
					class="btn btn-outline-primary btn-lg mr-3 mb-3"
					>
					<i class="fas fa-pencil-alt"></i><br>投稿する</router-link
				>
			</div>
		</div>
		<table class="table table-striped" v-if="contents">
			<div v-for="(content,index) in sortContents"
				:key="content.id" 
				class="card bg-white border-info"
			>
				<h3 class="card-header">
					<img src="/storage/1623910044.柴犬.jpg" class="img-thumbnail" alt="">
					{{ content.user_name }}
					<i v-if="content.user_name === own.name"
						 class="far fa-edit clickable" @click="onResume(content)">
					</i>
					<i v-if="content.is_bookmark == 0" class="far fa-bookmark" @click="addbook(content.id)" style="color:#04B4AE; float:right;"><p class="popin">ブックマークに追加する</p></i>
					<i v-else class="fas fa-bookmark" @click="deletebook(content.id)" v-cloak style="color:#04B4AE;float:right;"><p class="pop">ブックマークを外す</p></i>
				</h3>

					<div class="card-body">
						<h5 class="card-subtitle mb-2 text-muted">{{ content.tag }}</h5>
						<div v-if="content.moreFlag == false">
							<p class="card-text text-left" 
								style="white-space: pre-wrap;"
							>
							{{ content.content_text | truncate }}<span
								@click="content.moreFlag=true; contents.splice()"
								v-if="content.content_text.length > 100"
								class="clickable"
								style="color: gray;"
								> ...続きを読む</span>
							</p>
						</div>
						<div v-else>
							<p @click="content.moreFlag=false; contents.splice()"
								class="card-text text-left"
								style="white-space: pre-wrap;"
							>
							 {{ content.content_text }}
							</p>
						</div>
					</div>

				<p>
					投稿日時：{{ content.created_at }}
				</p>
				<div class="card-footer btn-group checkparent" role="group"> 
					<button v-if="content.own_like_good === 0" class="btn btn-outline-primary checkchild1" @click="onAddgood(index,content.id, 1)">
            			<a slot="icon" class="fas fa-thumbs-up fa-lg" style="color: #c0c0c0" outline="none"> </a>
            			{{ content.count_good }}
						<p v-if="content.count_good !== 0" class="fukidashi1">{{ content.member_good }}がいいねを押しました。</p>
          			</button>
          			<button v-else class="btn btn-outline-primary checkchild1" @click="onDeletegood(index,content.id, 1)">
            			<a slot="icon" class="fas fa-thumbs-up fa-lg" style="color: #00bfff" outline="none"> </a>
            			{{ content.count_good }}
						<p v-if="content.count_good !== 0" class="fukidashi1">{{ content.member_good }}がいいねを押しました。</p>
          			</button>
          			<button v-if="content.own_like_heart === 0" class="btn btn-outline-danger checkchild2" @click="onAddgood(index,content.id, 2)">
            			<a slot="icon" class="far fa-heart fa-lg" style="color: #ff0000" outline="none"> </a>
            			{{ content.count_heart }}
						<p v-if="content.count_heart !== 0" class="fukidashi2">{{ content.member_heart }}がハートを押しました。</p>
          			</button>
          			<button v-else class="btn btn-outline-danger checkchild2" @click="onDeletegood(index,content.id, 2)">
            			<a slot="icon" class="fas fa-heart fa-lg" style="color: #ff0000" outline="none"> </a>
            			{{ content.count_heart }}
						<p v-if="content.count_heart !== 0" class="fukidashi2">{{ content.member_heart }}がハートを押しました。</p>
          			</button>
          			<button v-if="content.own_like_check === 0" class="btn btn-outline-success checkchild3" @click="onAddgood(index,content.id, 3)">
            			<a slot="icon" class="far fa-check-square fa-lg" style="color: #c0c0c0" outline="none"> </a>
            			{{ content.count_check }}
						<p v-if="content.count_check !== 0" class="fukidashi3">{{ content.member_check }}がチェックを押しました。</p>
          			</button>
          			<button v-else class="btn btn-outline-success checkchild3" @click="onDeletegood(index,content.id, 3)">
            			<a slot="icon" class="fas fa-check-square fa-lg" style="color: #00ff00" outline="none"> </a>
            			{{ content.count_check }}
						<p v-if="content.count_check !== 0" class="fukidashi3">{{ content.member_check }}がチェックを押しました。</p>
          			</button>
          			<button class="btn btn-outline-info btn-sm" data-toggle="collapse" @click="onAddrespond(content.id)">
            			<i class="far fa-lg fa-comment"></i> コメントする
          			</button>
				</div>

				<div v-show="content.comment_count" class="btn-group">
					<div v-if="content.comment_visusal === true" class="btn btn-outline-info btn-sm" @click="visual(content.comment_visusal, content.id)">
						&#9650; 閉じる
					</div>
					<div v-if="content.comment_visusal === false" class="btn btn-outline-info btn-sm" @click="visual(content.comment_visusal, content.id)">
						&#9660; {{ content.comment_count }}件の返信
					</div>
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

		<div class="fixed-bottom text-right mb-4 mr-4">
			<transition name="button">
				<button class="btn btn-dark" v-show="buttonActive" @click="returnTop">
					<i class="far fa-hand-point-up"></i>
						PageTop
				</button>
			</transition>
		</div>

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
			likes: [],
			content_text: '',
			search: false,
			buttonActive: false,
			scroll: 0,
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
			today: '',
      		deleteid: 0,
		}
	},
	mounted () {
		this.inspected_on = new moment().format('YYYY-MM-DD')
		this.getItems()
		window.addEventListener('scroll', this.scrollWindow)
	},
	watch: {
		//
	},
	filters: {
		truncate: function(value) {
			const length = 150;
			const ommision = '';
			if (value.length <= length) {
				return value;
			}
			return value.substring(0, length) + ommision;
		}
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
				alert("「" + this.keyword +"」を含むユーザを検索しました。")
			}
			const { data } = await axios.get('/api/content', {
				params: {
					sort: this.sort,
					keyword: this.keyword,
				},
			})
			this.content_text = data.content_text
			this.totalItems = data.total_items
			this.contents = data.contents
			this.isLoading = false
			this.contents.forEach(element => {
				element.moreFlag = false
			})
			this.responds.forEach(element => {
				element.moreFlag = false
			})

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
					if (moment().diff(this.notices[i].created_at, 'days') >= 14) {
              			this.deleteid = i + 1
              			axios.delete('/api/notice/' + this.deleteid)
            		} else if(this.notices[i].content_userid === this.userid &&this.notices[i].content_userid !== this.notices[i].respond_userid){
                		this.push +=this.notices[i].created_at +' ' +this.notices[i].content_id +'のツイートに対して' +this.notices[i].respond_username +'から返信が来ました。\n'
                		this.push_count += 1
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
			const api6 = axios.create()
      			axios.all([api6.get('/api/like')]).then(
        			axios.spread((res1, res2, res3, res4) => {
          			this.likes = res1.data
					for (let i = 0; i < this.likes.length; i++) {
            			if (this.likes[i].reaction_no === 1) {
              				this.contents[this.likes[i].content_id - 1].member_good += this.likes[i].user_name + 'さん,'
            			} else if (this.likes[i].reaction_no === 2) {
              				this.contents[this.likes[i].content_id - 1].member_heart += this.likes[i].user_name + 'さん,'
            			} else {
              				this.contents[this.likes[i].content_id - 1].member_check += this.likes[i].user_name + 'さん,'
            			}
          			}
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
		async onTagSearch(selected_tag) {
			this.isLoading = true;
			this.search = true;
			if (selected_tag != "") {
				alert(selected_tag +"勉強会の投稿のみ表示します。")
			} else {
				alert("絞り込みを解除します。")
				this.search = false
			}
			const { data } = await axios.get('/api/tag', {
				params: {
					tag: selected_tag,
				},
			})
			this.contents = data.contents
			this.isLoading = false
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
		onAddgood: function(Index,tweetId, mark) {
      		this.invalid = false
      		this.errorMessage = ''
      		const _this = this
			axios
        		.get('/api/content/addgood/' + tweetId + '/' + mark)
        		.then(function(resp) {			
				})
        		.catch(function(resp) {
          			console.log(resp)
        		})
			if (mark === 1) {
        		this.contents[this.contents.length-Index-1].own_like_good = 1
        		this.contents[this.contents.length-Index-1].count_good += 1
				this.contents[this.contents.length-Index-1].member_good += this.username + 'さん,'
				alert('いいねを押しました。')
			}
      		if (mark === 2) {
        		this.contents[this.contents.length-Index-1].own_like_heart = 1
        		this.contents[this.contents.length-Index-1].count_heart += 1
				this.contents[this.contents.length-Index-1].member_heart += this.username + 'さん,'
				alert('ハートを押しました。')
			}
      		if (mark === 3) {
        		this.contents[this.contents.length-Index-1].own_like_check = 1
        		this.contents[this.contents.length-Index-1].count_check += 1
				this.contents[this.contents.length-Index-1].member_check += this.username + 'さん,'
				alert('チェックを押しました。')
			}
    	},
		onDeletegood: function(Index,tweetId, mark) {
			const _this = this
			axios
        		.delete('/api/content/deletegood/' + tweetId + '/' + mark)
        		.then(function(resp) {
				})
        		.catch(function(resp) {
          			console.log(resp)
        		})
        		.finally(function() {
        		})
			if (mark === 1) {
        		this.contents[this.contents.length-Index-1].own_like_good = 0
        		this.contents[this.contents.length-Index-1].count_good -= 1
				this.contents[this.contents.length-Index-1].member_good = this.contents[this.contents.length-Index-1].member_good.replace(this.username + 'さん,','',)
				alert('いいねを外しました。')
			}
      		if (mark === 2) {
        		this.contents[this.contents.length-Index-1].own_like_heart = 0
        		this.contents[this.contents.length-Index-1].count_heart -= 1
				this.contents[this.contents.length-Index-1].member_heart = this.contents[this.contents.length-Index-1].member_heart.replace(this.username + 'さん,','',)
				alert('ハートを外しました。')
			}  
      		if (mark === 3) {
        		this.contents[this.contents.length-Index-1].own_like_check = 0
        		this.contents[this.contents.length-Index-1].count_check -= 1
				this.contents[this.contents.length-Index-1].member_check = this.contents[this.contents.length-Index-1].member_check.replace(this.username + 'さん,','',)
				alert('チェックを外しました。')
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
        		this.isLoading = false
      		}
    	},
		onComment(content) {
			this.$router.push({
				name: 'comment',
				params: { contentId: content.id }
			})
		},

		addbook: function(contentid){
            axios.get('/api/content/add/' + contentid)
            location.reload()
        },

    deletebook: function(contentid){
            axios.delete('/api/content/delete/' + contentid)
            location.reload()
        },

		returnTop() {
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			})
		},
		scrollWindow() {
			const top = 300
			this.scroll = window.scrollY
			if (top <= this.scroll) {
				this.buttonActive = true
			} else {
				this.buttonActive = false
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
.img {
	width: auto;
	height: auto;
	max-width: 100%;
	max-height: 100%;
	// border-radius: 150%;
	// border:	2px solid rgb(170, 170, 255)
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
.fukidashi1 {
  /*表示位置を指定します*/
  position: absolute;
  bottom: 25px;
  left: -15px;

  /*非表示にしておきます*/
  display: none;
  opacity: 0;

  /*表示スタイルを指定します*/
  padding: 5px;
  border-radius: 5px;
  color: white;
  width:300px;
  font-size:12px;
  background-color: blue;
  /*影をつけて見栄えを良くします*/
  box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.8),
    inset 1px 0 0 rgba(255, 255, 255, 0.3), inset -1px 0 0 rgba(255, 255, 255, 0.3),
    inset 0 -1px 0 rgba(255, 255, 255, 0.2);

  /*アニメーションを指定します*/
  animation-duration: 0.3s;
  animation-name: show-balloon;
}
.fukidashi2 {
  /*表示位置を指定します*/
  position: absolute;
  bottom: 25px;
  left: -40px;

  /*非表示にしておきます*/
  display: none;
  opacity: 0;

  /*表示スタイルを指定します*/
  padding: 5px;
  border-radius: 5px;
  color: white;
  width:300px;
  font-size:12px;
  background-color: red;
  /*影をつけて見栄えを良くします*/
  box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.8),
    inset 1px 0 0 rgba(255, 255, 255, 0.3), inset -1px 0 0 rgba(255, 255, 255, 0.3),
    inset 0 -1px 0 rgba(255, 255, 255, 0.2);

  /*アニメーションを指定します*/
  animation-duration: 0.3s;
  animation-name: show-balloon;
}
.fukidashi3 {
  /*表示位置を指定します*/
  position: absolute;
  bottom: 25px;
  left: -70px;

  /*非表示にしておきます*/
  display: none;
  opacity: 0;

  /*表示スタイルを指定します*/
  padding: 5px;
  border-radius: 5px;
  color: white;
  width:300px;
  font-size:12px;
  background-color: green;
  /*影をつけて見栄えを良くします*/
  box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.8),
    inset 1px 0 0 rgba(255, 255, 255, 0.3), inset -1px 0 0 rgba(255, 255, 255, 0.3),
    inset 0 -1px 0 rgba(255, 255, 255, 0.2);

  /*アニメーションを指定します*/
  animation-duration: 0.3s;
  animation-name: show-balloon;
}
@media screen and (min-width: 640px) {
	.checkparent .checkchild1:hover .fukidashi1 {
  	/*表示するようにします*/
  		display: inline-block;
  		opacity: 1;
  		bottom: 25px;
	}
	.fukidashi1::before {
  		/*吹き出し部分の三角形を表示します*/
  		content: "";
  		position: absolute;
  		top: 97%;
  		left: 80px;
  		border: 6px solid transparent;
  		border-top: 6px solid blue;
	}
	.checkparent .checkchild2:hover .fukidashi2 {
  	/*表示するようにします*/
  		display: inline-block;
  		opacity: 1;
  		bottom: 25px;
	}
	.fukidashi2::before {
  		/*吹き出し部分の三角形を表示します*/
  		content: "";
  		position: absolute;
  		top: 97%;
  		left: 100px;
  		border: 6px solid transparent;
  		border-top: 6px solid red;
	}
	.checkparent .checkchild3:hover .fukidashi3 {
  		/*表示するようにします*/
  		display: inline-block;
  		opacity: 1;
  		bottom:25px;
	}
	.fukidashi3::before {
  		/*吹き出し部分の三角形を表示します*/
  		content: "";
  		position: absolute;
  		top: 97%;
  		left: 100px;
  		border: 6px solid transparent;
  		border-top: 6px solid green;
	}
}
.popin {
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

.fa-bookmark:hover .popin {
    display: inline-block;
    opacity: 1;
    top: -40px;
}

.popin::before {
    /*吹き出し部分の三角形を表示します*/
    content: "";
    position: absolute;
    top: 97%;
    right: 40px;
    border: 6px solid transparent;
    border-top: 6px solid #04B4AE;
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

.button-enter-active,
.button-leave-active {
	transition: opacity 0.5s;
}
.button-enter,
.button-leave-to {
	opacity: 0;
}

</style>