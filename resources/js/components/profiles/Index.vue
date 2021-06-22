<template>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card" key="admin">
				<div class="card-body">
					<div class="d-flex flex-wrap justify-content-center mb-2">
						<div class="mr-auto">
							<h3>{{ own.goriller_name }}</h3>
						</div>
						<div class="align-self-center">
							<button type="button" class="btn btn-dark" @click="onBack">戻る</button>
						</div>
					</div>

					<div class="form-group text-center">
						<img class="img" v-if="profile.path" src="/storage/1623910044柴犬.jpg">
						<img class="img" v-else src="/storage/1623910044柴犬.jpg">
						<router-link
							:to="{ name: 'profile.upload'}"
							class="btn btn-secondary"
						>
							<i class="fas fa-pencil-alt"></i>
						</router-link>
					</div>

					<div class="form-inline justify-content-center">    
						<div class="form-group text-center">
							<h5>誕生日</h5>
							<input class="form-control" type="text">
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
<loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
</div>
</template>

<script>
import moment from 'moment';
export default {
	props: [
		'profile_id',
	],
	data () {
		return {
			profile: {
				id: '',
				path: '',
				goriller_id: '',
			},

			invalid: false,
			errorMessage: '',

			isLoading: false,
			fullPage: false,
		}
	},
	created () {
		this.getItems()
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
		getItems: function () {
			this.isLoading = true;
			const api = axios.create()
			axios.all([
				api.get('/api/profile'),
			]).then(axios.spread((res1, res2, res3) => {
				this.profile = res1.data
				this.isLoading = false
			}))
		},
		onStore: function () {
			let _this = this
			if (this.mode == 'create') {
				axios.post('/api/goriller', {
					goriller: this.goriller,
				})
				.then(function (resp) {
					if (resp.data.result) {
						alert('登録しました。')
						_this.$router.go(-1)
					} else {
						_this.errorMessage = resp.data.errorMessage
						_this.invalid = true
					}
				})
				.catch(function (resp) {
					console.log(resp)
				});
			} else {
				axios.put('/api/goriller/'+this.goriller.id, {
					goriller: this.goriller,
				})
				.then(function (resp) {
					if (resp.data.result) {
						alert('更新しました。')
						_this.$router.go(-1)
					} else {
						_this.errorMessage = resp.data.errorMessage
						_this.invalid = true
					}
				})
				.catch(function (resp) {
					console.log(resp)
				});
			}
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
.row-line {
	padding-left: 1.5rem;
	padding-right: 1.5rem;
	padding-bottom: 1rem;
}
.form-content {
	width: 12rem;
	padding-left: 1rem;
	padding-right: 1rem;
	display: inline-block;
}
.form-content-lg {
	width: 15rem;
	padding-left: 1rem;
	padding-right: 1rem;
	display: inline-block;
}
.required-label label:after {
	display: inline-block;
	margin-left: 5px;
	padding: 2px 4px;
	border-radius: 3px;
	background-color: #ec5d53;
	color: #fff;
	content: "必須";
	font-size: 9px;
	line-height: 10px;
}
.img {
	width: auto;
	height: auto;
	max-width: 100%;
	max-height: 100%;
	text-align: center;
	border-radius: 150%;
	border: 3px solid greenyellow;
}
</style>
