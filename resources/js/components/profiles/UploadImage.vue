<template>
<div class="container">
	<div class="card text-center">
		<div class="card-body">
			<div class="d-flex flex-wrap justify-content-center mb-2">
				<back-button />
				<div class="form-group">
					<div class="form-group">
						<label class="font-weight-bold">プロフィール写真</label>
					</div>

					<div class="form-group">
						<input @change="fileSelect" type="file" name="file">
						<img class="img" v-if="confirmedImage" :src="confirmedImage" />
						<img class="img" v-else src="/image/noImage.jpg">
					</div>

					<div class="form-group">
						<button class="btn btn-success" @click="upload" type="submit">アップロード</button>
						<div class="alert alert-success" role="alert" v-if="invalid">
						　画像を選択してください。
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
export default {
	props: [
		'goriller_id'
	],
	data () {
		return {
			selected_file: null,
			confirmedImage: "",
			isLoading: false,
			invalid: false,
			view: true,
		}
		// 	profile: {
		// 		id: '',
		// 		path: '',
		// 	},
	},
	created() {
		// this.getImage()
	},
	watch: {
		//
	}, 
	computed: {
		own: function () {
			return this.$store.state.user
		},
	},
	methods: {
		fileSelect: function(e) {
			this.selected_file = e.target.files[0];
		},
		upload: function() {
			let formData = new FormData();
			formData.append('file', this.selected_file);

			let config = {
				headers: {
					'content-type': 'multipart/form-data'
				}
			};

			axios.post('/api/profile', formData, config)
				.then(function(response) {
					alert('アップロードしました')
				})
				.catch(function(error) {
					alert('アップロードに失敗しました')
				})
		}
		// getImage: function () {
		// 	this.isLoading = true;
		// 	const api = axios.create()
		// 	axios.all([
		// 		// ここでエラー出てる
		// 		api.get('/api/profile'),
		// 	])
		// 	.then(axios.spread((res1, res2) => {
		// 		this.profile = res1.data
		// 		this.profile.goriller_id = this.own.goriller_id
		// 		this.isLoading = false
		// 	}))
		// 	.catch(function (resp) {
		// 		console.log(resp)
		// 	});
		// },
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

// 画像の正円切り抜きは後から
.img {
	width: auto;
	height: auto;
	max-width: 100%;
	max-height: 100%;
	border-radius: 150%;
	border: 4px solid GREEN;
}
</style>
