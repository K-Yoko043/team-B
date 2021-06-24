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
		</div>

		<table class="table table-striped" 	v-if="contents">
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

				<div class="readmore">
					<div class="card-body">
						<h5 class="card-subtitle mb-2 text-muted">{{ content.tag }}</h5>
						<h6>{{content.content_text.length}}文字</h6>
						<input id="check1" class="readmore-check" type="checkbox">
						<div class="readmore-content">
							<p class="card-text text-left" 
								style="white-space: pre-wrap;"
							>{{ content.content_text }}
							</p>
						</div>
						<label class="readmore-label" for="check1" v-if="content.content_text.length > 100"></label>
					</div>
				</div>

				<p>投稿日時：{{ content.created_at }}</p>
				<div class="card-footer btn-group" role="group"> 
										                  
					<button class="btn btn-outline-success btn-sm" v-if="is_liked" @click="unlike(content)">
						<i class="far fa-lg fa-thumbs-up"></i> いいねを取り消す
					</button>
					<button class="btn btn-outline-success btn-sm" v-else @click="like(content)">
						<i class="far fa-lg fa-thumbs-up"></i> いいね
					</button>
					
					<button class="btn btn-outline-info btn-sm" data-toggle="collapse" data-target="#reply">
						<i class="far fa-lg fa-comment"></i> コメントする
					</button>
					<div id="reply" class="collapse">
 						<div class="form-group">
							<label>返信者</label>
							<input v-model="own.goriller_name" class="text-center form-control" readonly="readonly">
						</div>
						<div class="form-group">
							<div class="form-group">
								<textarea class="form-control" v-model="content.comments" style="height: 300px;" placeholder="コメントする..."></textarea>
							</div>
						</div>
						<div class="form-group">
							<button v-show="content.comments != ''" @click="onComment(content.id)" 
								type="button" class="btn btn-sm btn-info">
								コメントする
							</button>
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
			content_text: '',
			tag: '',
			good_count: '',
			keyword: '',
			is_liked: false,
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
		
	},
	computed: {
		own() {
			return this.$store.state.user
		},
		sortContents() {
			return this.contents.reverse();
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
		},

		onSearch() {
			this.$store.state.barcode = ''
      this.offset = 0
      this.currentPage = 0
      this.getItems()
		},

		onResume(content) {
			this.$router.push({ 
				name: 'content.resume', 
				params: { contentId: content.id }
			})
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

.readmore {
	position: relative;
	box-sizing: border-box;
	padding: 10px;
	border: 1px solid #CCC;
}
.readmore-content {
	position: relative;
	overflow: hidden;
	height: 100px;
}
.readmore-content::before {
	display: block;
	position: absolute;
	bottom: 0;
	left: 0;
	width: 100%;
	content: "";
	height: 50px;
	background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.8) 50%, #fff 100%);
}
.readmore-label {
	display: table;
	bottom: 5px;
	position: absolute;
	bottom: 5px;
	left: 50%;
	transform: translateX(-50%);
	-webkit-transform: translateX(-50%);
	margin: 0 auto;
	z-index: 2;
	padding:	0 10px;
	background-color: #4283cca6;
	border-radius: 10px;
	color: #FFF;
}
.readmore-label::before {
	content: "続きを読む";
}
.readmore-check {
	display: none;
}
.readmore-check:checked ~ .readmore-label {
	position: static;
	transform: translateX(0);
	-webkit-transform: translateX(0);
}
.readmore-check:checked ~ .readmore-label:before {
	content: "閉じる";
}
.readmore-check:checked ~ .readmore-content {
	height: auto;
}
.readmore-check:checked ~ .readmore-content::before {
	display: none;
}
</style>