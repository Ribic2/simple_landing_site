require('./bootstrap');
window.Vue = require('vue');

import vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'

import about from './components/about.vue'
import contact from  './components/contact.vue'
import root from './components/home.vue'
import App from './components/app.vue'
import video from './components/video.vue'
import page404 from './components/Errors/page404.vue'

vue.use(VueRouter, axios, VueAxios);

const routes = [
    {path: '/', component: root},
    {path: '/about', component: about},
    {path: '/contact', component: contact},
    {path: '/posnetek/:id', component: video},
    {path: '/*', component: page404}
]

const router = new VueRouter({
    routes,
    mode: 'history'
});

const app = new Vue({
    router,
    el: '#app',
    render: h => h(App)
});