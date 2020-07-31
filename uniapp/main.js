import Vue from 'vue'
import App from './App'

import request from './common/js/request.js'
Vue.prototype.$H = request;

// 挂载Vuex
import store from './store';  
Vue.prototype.$store = store;

import uView from "uview-ui";
Vue.use(uView);

Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
	store,
	...App
})
app.$mount()