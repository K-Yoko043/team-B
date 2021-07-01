<template>
<div class="container">
	<div class="card text-center mx-auto">
		<div class="card-body">

				<div class="form-inline">
					<div class="form-inline" style="overflow: hidden;">
						<div class="form-group">
							<input class="form-control" type="text" v-model="keyword" placeholder="キーワード検索">
						</div>
						<button type="button" class="btn btn-primary" @click="onSearch">
							<i class="fas fa-search"></i>
						</button>
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
							<button class="dropdown-item" type="button" @click="onTagSearch('')">トップに戻る</button>
					 		<button class="dropdown-item" type="button" @click="onTagSearch('フィロソフィー')">フィロソフィー勉強会</button>
							<button class="dropdown-item" type="button" @click="onTagSearch('NG')">NG勉強会</button>
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

		<table class="table table-striped" v-if="contents">
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
					<i v-if="content.is_bookmark == 0" class="far fa-bookmark" @click="addbook(content.id)" style="color:#04B4AE; float:right;"><p class="popin">ブックマークに追加する</p></i>
					<i v-else class="fas fa-bookmark" @click="deletebook(content.id)" v-cloak style="color:#04B4AE;float:right;"><p class="pop">ブックマークを外す</p></i>
				</h3>

				<div class="readmore">
					<div class="card-body">
						<h5 class="card-subtitle mb-2 text-muted">{{ content.tag }}</h5>
						<input id="check1" class="readmore-check" type="checkbox">
						<div class="readmore-content">
							<p class="card-text text-left" 
								style="white-space: pre-wrap;"
							>
							{{ content.content_text }}
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
					
					<button class="btn btn-outline-info btn-sm ml-10" @click="onComment(content)">
						<i class="far fa-lg fa-comment-dots"></i> コメントする
					</button>
				
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

		async onTagSearch(selected_tag) {
			this.isLoading = true;
			if (selected_tag != "") {
				alert(selected_tag +"勉強会の投稿のみ表示します。")
			} else {
				alert("トップに戻ります。")
			}
			const { data } = await axios.get('/api/tag', {
				params: {
					tag: selected_tag,
				},
			})
			this.contents = data.contents
			// this.tags = data.tags
			this.isLoading = false
		},

		onResume(content) {
			this.$router.push({ 
				name: 'content.resume', 
				params: { contentId: content.id }
			})
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

@media screen and (max-width: 600px) {
	.form-group > .form-control {
	margin-top :15px;
}
}
</style>