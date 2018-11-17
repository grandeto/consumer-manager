
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import { Table, TableColumn, Row, Col, Input, Select, Option, Button, Message } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

window.Vue = require('vue');

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Row);
Vue.use(Col);
Vue.use(Input);
Vue.use(Select);
Vue.use(Option);
Vue.use(Button);
Vue.prototype.$message = Message;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from './components/App.vue';

if(document.getElementById("consumers")){
    const app = new Vue({
        el: '#consumers',
        components: {
            App
          },
        render: h => h(App)
    });
}
