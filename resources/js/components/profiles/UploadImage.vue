<template>
<div class="container">
	<div class="card text-center">
		<div class="card-body">
			<div class="d-flex flex-wrap justify-content-center mb-2">
				<back-button />
				<div class="form-group">
						<div class="form-group">
						<label class="font-weight-bold">プロフィール写真</label>
						<input type="file" class="form-control-file" @change="confirmImage" v-if="view">
					</div>

					<div class="form-group">
						<p v-if="confirmImage">
							<img class="img" v-if="confirmedImage" :src="confirmedImage" />
							<img class="img" v-else src="/image/noImage.jpg">
						</p>
						<button class="btn btn-success form-control-btn" @click="uploadImage">アップロード</button>
					</div>

					<div class="alert alert-success" role="alert" v-if="invalid">
					　画像を選択してください。
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
export default {
	props: {
			//
	},
	data () {
		return {
			image: {
				id: '',
				user_name: '',
				goriller_id: '',
			},
			view: true,

			confirmedImage: "",
			isLoading: false,
			invalid: false,
		};
	},
	created() {
		this.getImage()
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
		getImage: function () {
			this.isLoading = true;
			const api = axios.create()
			axios.all([
				api.get('/api/profile'),
			]).then(axios.spread((res1, res2) => {
				this.image = res1.data
				this.isLoading = false
			}))
		},
		confirmImage(e) {
			this.message = "";
			this.file = e.target.files[0];
			if (!this.file.type.match("image.*")) {
				this.message = "画像ファイルを選択してください";
				this.confirmedImage = "";
				this.invalid = true;
				return;
			}
			this.createImage(this.file);
		},
		createImage(file) {
			let reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = e => {
				this.confirmedImage = e.target.result;
			};
		},
		uploadImage() {
			this.invalid = false
			this.errorMessage = ''
			if(!this.file) {
				this.invalid = true
				return
			}

			let data = new FormData();
			data.append("file", this.file);
			data.append("title", this.title);

			axios.post('/api/profile', data)
				.then(response => {
					this.getImage()
					this.message = response.data.success
					this.confirmedImage = ""
					alert(this.message)

					this.view = false;
					this.$nextTick(function() {
							this.view = true;
					});

					// this.$router.go(-1)
				})
				.catch(err => {
					this.message = err.response.data.errors;
				});
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
