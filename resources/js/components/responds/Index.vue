<template>
<div class="container">
	<div class="row justify-content-center  mx-auto">
		<div class="col-md-8">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-start mb-3">
						<!-- <div class="mr-auto">
							<span class="span-header">コメント: @{{ content.user_name }}</span>
						</div> -->
						<div class="float-right align-self-center">
							<button type="button" class="btn btn-dark" @click="onBack">戻る</button>
						</div>
					</div>

					<div class="form-group">
						<label>【 タグ 】 {{ content.tag }}</label>
					</div>

					<div class="form-group">
						<label>【 投稿内容 】</label>
						<div class="readmore">							
							<div class="overflow-auto" v-if="content.content_text.length > 200" style="height: 250px">
								<p style="white-space: pre-wrap;">
									{{ content.content_text }}
								</p>
							</div>
							<div class="overflow-hidden" v-else style="height: 200px">
								<p style="white-space: pre-wrap;">
									{{ content.content_text }}
								</p>
							</div>
						</div>
					</div>

					<div class="form-group alert alert-info" id="comment-area">

					<div class="form-inline">
						<p class="mr-1">返信先: </p>
						<p class="font-weight-bold text-primary">@{{ content.user_name }}</p>
						<div class="align-self-center col text-right">
							<button type="button" class="btn btn-primary form-control-btn" @click="onStore" style="margin-bottom: 10px;">
								<i class="far fa-lg fa-comments"></i> 返信
							</button>
						</div>
					</div>

						<textarea id="textarea" class="form-control" style="height: 200px;" v-model="respond.text" placeholder="コメントする..."></textarea>
						<input type="=text" class="form-control" v-model="respond.url" placeholder="URLを入力">
					</div>

					<div class="row-line">
						<transition name="fade" mode="out-in">
						<div class="alert alert-info" role="alert" v-if="invalid">
							{{ errorMessage }}
						</div>
						</transition>
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
		'contentId',
	],
	data () {
		return {
			content: {
				id: '',
				tag: '',
				content_text: '', 
				user_name: '', 
			},
			respond: {
				id: null,
				text: '',
				url: '',
				content_id: null,
			},

			isLoading: false,
			invalid: false,
			errorMessage: '',
		}
	},
	created () {
		this.getItems()  
	},
	watch: {
			//
	},
	computed: {
		own() {
			return this.$store.state.user
		},
			
	},
	methods: {
		getItems: function () {
			this.isLoading = true;
			const api = axios.create()
			axios.all([
				api.get('/api/content/' + this.contentId),
			]).then(axios.spread((res1, res2) => {
				this.content = res1.data
				this.isLoading = false
			}))
		},
		onStore: function () {
			// alert('返信しました');
			this.respond.content_id = this.contentId
			this.invalid = false
			this.errorMessage = ''

			const _this = this
			axios.post('/api/respond', {
				respond: this.respond
			})
			.then(function (resp) {
				console.log(resp)
				if (resp.data.result) {
					alert('返信しました。')
					_this.$router.go(-1);
				} else {
					_this.errorMessage = resp.data.errorMessage
					_this.invalid = true
				}
			})
			.catch(function(resp) {
				console.log(resp)
			})
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
.readmore {
	position: relative;
	box-sizing: border-box;
	padding: 10px;
	border: 1px solid #CCC;
}
</style>