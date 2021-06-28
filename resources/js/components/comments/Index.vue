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
						<div class="align-self-center ml-5">
							<button type="button" class="btn btn-primary form-control-btn" @click="onStore" style="margin-bottom: 10px;">
								<i class="far fa-lg fa-comments"></i> 返信
							</button>
						</div>
					</div>

						<textarea id="textarea" class="form-control" style="height: 200px;" placeholder="コメントする..."></textarea>
					</div>

				</div>
				<!-- <div class="align-self-center mr-3">
					<button type="button" class="btn btn-primary form-control-btn" @click="onStore" style="margin-bottom: 20px;">
						<i class="far fa-lg fa-comments"></i> 返信
					</button>
				</div> -->
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
			]).then(axios.spread((res1, res2, res3, res4) => {
				this.content = res1.data
				this.isLoading = false
			}))
		},
		onStore: function () {
			alert('返信しました');
			// this.invalid = false
			// this.errorMessage = ''

			// if (!this.content.tag) {
			// 	this.errorMessage = 'タグを選択してください。'
			// 	this.invalid = true
			// 	return
			// }

			// let _this = this
			// axios.post('/api/content', {
			// 	content: this.content,
			// })
			// .then(function (resp) {
			// 	if (resp.data.result) {
			// 		alert('投稿しました。')
			// 		_this.$router.go(-1)
			// 	} else {
			// 		_this.errorMessage = resp.data.errorMessage
			// 		_this.invalid = true
			// 	}
			// })
			// .catch(function (resp) {
			// 	console.log(resp)
			// });
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
.readmore {
	position: relative;
	box-sizing: border-box;
	padding: 10px;
	border: 1px solid #CCC;
}
</style>