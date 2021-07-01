
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import Vuex from 'vuex';
Vue.use(Vuex);
// Bootstrap-Vue
window.BootstrapVue = require('bootstrap-vue');
Vue.use(BootstrapVue);
import Loading from 'vue-loading-overlay';
Vue.component('loading', Loading)
import 'vue-loading-overlay/dist/vue-loading.min.css';
import 'vue-loading-overlay/dist/vue-loading.min.css';
// https://www.npmjs.com/package/vue-full-calendar
import FullCalendar from 'vue-full-calendar';
Vue.use(FullCalendar)
import 'fullcalendar/dist/fullcalendar.css';
import 'fullcalendar-scheduler/dist/scheduler.min.js';
import 'fullcalendar-scheduler/dist/scheduler.min.css';
// https://github.com/robinvdvleuten/vuex-persistedstate
import Multiselect from 'vue-multiselect'
Vue.component('multiselect', Multiselect)
import 'vue-multiselect/dist/vue-multiselect.min.css';

import createPersistedState from 'vuex-persistedstate';
import DatePickerStandard from 'vue-mg-date-picker-standard'
Vue.component('DatePickerStandard', DatePickerStandard)
import DatePickerFromTo from 'vue-mg-date-picker-from-to'
Vue.component('DatePickerFromTo', DatePickerFromTo)
import ModalKeypad from 'vue-mg-modal-keypad'
Vue.component('ModalKeypad', ModalKeypad)
import ImageFileUploader from 'vue-mg-image-file-uploader'
Vue.component('ImageFileUploader', ImageFileUploader)
import TimePickerSelector from 'vue-mg-time-picker-selector'
Vue.component('TimePickerSelector', TimePickerSelector)
import MgPaginate from 'vue-mg-paginate'
Vue.component('MgPaginate', MgPaginate)
import ToggleSelector from 'vue-mg-toggle-selector'
Vue.component('ToggleSelector', ToggleSelector)
import ModalDialog from "vue-mg-modal-dialog";
Vue.component("ModalDialog", ModalDialog);
// import DateTimeSelector from "vue-mg-date-time-selector";
// Vue.component("DateTimeSelector", DateTimeSelector);
import CsvImporter from 'vue-mg-csv-importer'
Vue.component('CsvImporter', CsvImporter)
import VueInputCalculator from "vue-input-calculator";
Vue.component('VueInputCalculator', VueInputCalculator);
import ModalSearchMaster from 'vue-mg-modal-search-master';
Vue.component('ModalSearchMaster', ModalSearchMaster);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('main-component', require('./components/MainComponent.vue').default);
Vue.component('back-button', require('./components/commons/buttons/BackButton.vue').default);
Vue.component('date-increment-picker', require('./components/commons/DateIncrementPicker.vue').default);
Vue.component('date-time-selector', require('./components/commons/DateTimeSelector.vue').default);
Vue.component('pagination', require('./components/commons/Pagination.vue').default);
Vue.component('barcode-component', require('./components/commons/BarcodeComponent.vue').default);
Vue.component('modal-employee-select', require('./components/commons/ModalEmployeeSelect.vue').default);
Vue.component('select-name', require('./components/commons/SelectName.vue').default);
Vue.component("number-input", require("./components/commons/NumberInput.vue").default);
// Vue.component("img-upload", require("./components/profiles/Index.vue.vue").default);


const router = new VueRouter({
	mode: 'history',
	routes: [
		// ホーム
		{ name: 'home', path: '/', component: require('./components/HomeComponent.vue').default },

		// プロフィール
		{ name: 'profile', path: '/profile', component: require('./components/profiles/Index.vue').default },
		{ 
			name: 'profile.upload',
		  path: '/profile/upload/:goriller_id', 
			component: require('./components/profiles/UploadImage.vue').default,
			props: true
		},

		// 設定
		{ name: 'setting', path: '/setting', component: require('./components/settings/Index.vue').default },

		// ゴリラー
		{ name: 'goriller', path: '/goriller', component: require('./components/gorillers/Index.vue').default },
		{ name: 'goriller.create', path: '/goriller/create', component: require('./components/gorillers/CreateUpdate.vue').default },
		{
			 name: 'goriller.show', 
			 path: '/goriller/show/:goriller_id', 
			 component: require('./components/gorillers/CreateUpdate.vue').default, 
			 props: true 
		},

		// 投稿内容
		{ name: 'content', path: '/content', component: require('./components/contents/Index.vue').default},
		{ name: 'content.create', path: '/content/create', component: require('./components/contents/CreateUpdate.vue').default},
		{ 
			name: 'content.resume',
			path: '/content/resume/:contentId',
			component: require('./components/contents/CreateUpdate.vue').default,
			props: true,
		},

		// コメント（返信）内容
		{ 
			name: 'comment', 
			path: '/comment/:contentId', 
			component: require('./components/comments/Index.vue').default,
			props: true,
		},


		// いいね
		{ name: 'content.like', path: '/content', component: require('./components/contents/Index.vue').default},
		//返信
		{ name: 'respond.create', path: '/respond/create', component: require('./components/responds/CreateUpdate.vue').default,props: true,},
		{ name: 'respond.show', path: '/respond/show/:respondId', component: require('./components/responds/CreateUpdate.vue').default,props: true,},

		// ブックマーク
        { name: 'bookmark', path:'/bookmark', component: require('./components/bookmarks/Index.vue').default},

		// not found
		{ path: '*', component: require('./components/commons/NotFoundComponent.vue').default },
	]
});

const store = new Vuex.Store({
	state: {
		user: '',
		barcode: '',
		sort: {
			key: '', // ソートキー
			isAsc: false // 昇順ならtrue,降順ならfalse
		},
	},
	mutations: {
		getUser (state, payload) {
			axios.get('/api/user/info').then(res => {
				// this.$store.commit('setLoginUser', res.data)
				state.user = res.data
			}).catch(error => {
				console.log(error)
			});
		},
	},
	actions: {
		getUser (context) {
			context.commit('getUser')
		}
	},
	plugins: [createPersistedState()],
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
	el: '#app',
	store,
	router,
});
