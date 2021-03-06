/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);


import Posts from './components/PostsComponent.vue';
import CreatePost from './components/CreatePostComponent.vue';
import ShowPost from './components/ShowPostComponent.vue';
import EditPost from './components/EditPostComponent.vue';
import Comments from './components/PostCommentsComponent.vue';
import Users from './components/UsersComponent.vue';
import EditUser from './components/EditUserComponent.vue';


Vue.component('posts', Posts);
Vue.component('create', CreatePost);
Vue.component('posts', ShowPost);
Vue.component('posts', EditPost);
Vue.component('comments', Comments);
Vue.component('users', Users);




/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const routes = [
    {
        name: 'posts',
        path: '/',
        component: Posts
    },
    {
        name: 'post/show',
        path: '/post/show/:id',
        component: ShowPost
    },
    {
        name: 'post/create',
        path: '/post/create',
        component: CreatePost
    },
    {
        name: 'post/edit',
        path: '/post/edit/:id',
        component: EditPost
    },
    {
        name: 'users',
        path: '/users',
        component: Users
    },
    {
        name: 'user/edit',
        path: '/user/edit/:id',
        component: EditUser
    },
];

// const router = new VueRouter({ mode: 'history', routes: routes});
// const app = new Vue(Vue.util.extend({ router }, Posts));
const router = new VueRouter({ routes })

const app = new Vue({ router }).$mount('#app')

// const app = new Vue({
//     el: '#app',
//     // template: '<notification/>',
//     // router,
//     // render: h => h(App)
// });
