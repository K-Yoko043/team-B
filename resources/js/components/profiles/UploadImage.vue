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
						<input @change="fileSelect" type="file" name="file" class="form-control-file">
						<img class="img" v-if="selected_file" :src="confirmedImage" />
						<img class="img" v-else src="/image/gorilla.png">
					</div>

					<div class="form-group">
						<button class="btn btn-success" @click="upload" type="submit">アップロード</button>
					</div>

					<div class="row-line">
						<transition name="fade" mode="out-in">
						<div class="alert alert-success" role="alert" v-if="invalid">
							画像を選択してください。
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
export default {
	props: [
		'goriller_id'
	],
	data () {
		return {
			profile: {
				id: '',
				path: '',
			},

			selected_file: null,
			confirmedImage: "",
			isLoading: false,
			invalid: false,
			view: true,
			message: "",
		}
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
		getImage: function () {
			this.isLoading = true;
			const api = axios.create()
			axios.all([
				api.get('/api/profile/'+this.goriller_id),
			]).then(axios.spread((response) => {
				this.profile = response.data
				this.isLoading = false
			}))
		},

		fileSelect: function(e) {
			this.selected_file = e.target.files[0];
			this.createImage(this.selected_file);
		},
		createImage(file) {
			let reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = e => {
				this.confirmedImage = e.target.result;
			};
		},
		async upload () {
			let formData = new FormData();
			formData.append('file', this.selected_file);
			this.profile.goriller_id = this.goriller_id

			let config = {
				headers: {
					'content-type': 'multipart/form-data'
				}
			};

			if (!this.selected_file) {
				this.errorMessage = '画像を選択してください'
				this.invalid = true;
			}

			let _this = this
			axios.post('/api/profile/'+this.goriller_id, formData, config, {
				profile: this.profile
			})
			.then(function(response) {
				if (response.data.result) {
					alert('アップロードしました')
				_this.$router.go(-1)
				} else {
					_this.errorMessage = response.data.errorMessage
					_this.invalid = true
				}
			})
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

// 画像の正円切り抜きは後から
.img {
	width: auto;
	height: auto;
	max-width: 100%;
	max-height: 100%;
	border-radius: 150%;
	border: 4px solid GREEN;
}

.form-control-file {
	padding-bottom: 10px;
}
</style>
