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
							<a class="dropdown-item" href="#">
								<router-link
									:to="{ name: 'profile' }"
									>プロフィール編集</router-link
								>
							</a>
							<!-- クリックされたらonSearch? -->
							<button class="dropdown-item" type="button" @click="onTagSearch('フィロソフィー')">フィロソフィー勉強会</button>
							<button class="dropdown-item" type="button" @click="onTagSearch('NG')">NG勉強会</button>
							<button class="dropdown-item" type="button" @click="onTagSearch('')" v-if="search">絞り込みクリア</button>
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
				style="border-width: 4px;"
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
                    <h6>{{content.content_text.length}}文字</h6>
                   
                    <div v-if="content.moreFlag == false">
                        <p class="card-text text-left" 
                            style="white-space: pre-wrap;"
                        >
                        {{ content.content_text | truncate}}<span
                            @click="content.moreFlag=true; contents.splice()"
                            v-if="content.content_text.length > 100"
                            class="clickable"
                            style="color: gray;"
                        > ... 続きを読む</span>
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

			<div v-show="content.comment_count" class="btn-group">
				<div v-if="!show_comment" class="btn btn-outline-info btn-sm" @click="showComment(content)">
						&#9660; {{ content.comment_count }}件の返信
				</div>
				<div v-else class="btn btn-outline-info btn-sm" @click="showComment(content)">
						&#9650;閉じる
				</div>
			</div>

			<div v-if="show_comment">
				<div v-for="respond in responds" :key="respond.id">
					<div v-if="content.id === respond.content_id">
						<hr />
						<p>返信者：{{ respond.user_name }}</p>
                         <div v-if="respond.moreFlag == false">
                            <p class="card-text text-left mb-5"
                                style="white-space: pre-wrap;"
                            >
                                {{ respond.text | truncate }}<span
                                    @click="respond.moreFlag=true; responds.splice()"
                                    v-if="respond.text.length > 100"
                                    class="clickable"
                                    style="color: gray;"
                                > ... 続きを読む</span>
                            </p>
                            <a href="#" class="font-weight-bold" @click="linkToOtherWindow(respond)">{{ respond.url }}</a>
                        </div>
                        <div v-else class="mb-5">
                            <p @click="respond.moreFlag=false; responds.splice()"
                                class="card-text text-left"
                                style="white-space: pre-wrap;"
                            >
                                {{ respond.text }}
                            </p>
                            <a href="#" class="font-weight-bold" @click="linkToOtherWindow(respond)">{{ respond.url }}</a>
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
			responds: [],
			content_text: '',
			good_count: '',
			keyword: '',
			is_liked: false,
			isLoading: false,
			sort: {
					key: 'id',
					isAsc: true,
			},
			search: false,
			readmore: false,
			show_comment: false,
			totalComments: '',

			buttonActive: false,
			scroll: 0,
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
            this.contents.forEach(element => {
                element.moreFlag = false
            })
            this.responds.forEach(element => {
                element.moreFlag = false
            })

			const api = axios.create()
			axios.all([
				api.get('/api/goriller'),
				api.get('/api/respond'),
			]).then(axios.spread((res1, res2) => {
				this.gorillers = res1.data
				this.responds = res2.data
				this.totalComments = res2.data.total_comments
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
			this.search = true;
			if (selected_tag != "") {
				alert(selected_tag +"勉強会の投稿のみ表示します。")
			} else {
				alert("絞り込みを解除します。")
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
				name: 'respond',
				params: { contentId: content.id }
			})
		},

		readMore(content) {
			const { data } = axios.get('/api/readmore/'+content.id, {
				params: {
					content_id: content.id,
				},
			})
			this.readmore = readmore
			// this.tags = data.tags
			this.isLoading = false
		},

		showComment(content)
		{
			this.show_comment = !this.show_comment
		},

		returnTop()
		{
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			})
		},
		scrollWindow()
		{
			const top = 300
			this.scroll = window.scrollY
			if (top <= this.scroll) {
				this.buttonActive = true
			} else {
				this.buttonActive = false
			}
		},
		linkToOtherWindow(respond)
		{
			window.open(respond.url, '_blank')
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
.img {
	width: auto;
	height: auto;
	max-width: 100%;
	max-height: 100%;
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
	height: 150px;
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

.button-enter-active,
.button-leave-active {
	transition: opacity 0.5s;
}
.button-enter,
.button-leave-to {
	opacity: 0;
}
</style>