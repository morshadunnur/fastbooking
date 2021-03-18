require('./bootstrap');
import { App, plugin } from '@inertiajs/inertia-vue';
import Vue from 'vue';
import { InertiaProgress } from "@inertiajs/progress";
import route from "ziggy-js";
import { Ziggy } from "./ziggy";

Vue.use(plugin);
InertiaProgress.init();
Vue.mixin({
   methods: {
       route: (name, params, absolute) => route(name, params, absolute, Ziggy),
   }
});

const el = document.getElementById('app');
new Vue({
    render: h => h(App, {
        props: {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name => import(`./Pages/${name}`).then(module => module.default)
        }
    })
}).$mount(el)
