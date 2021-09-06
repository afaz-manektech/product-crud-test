import './bootstrap';
import Vue from 'vue'

import ProductsComponent from './components/ProductsComponent.vue';

Vue.component('products-component', ProductsComponent);

const app = new Vue({
    el: '#app',
});
