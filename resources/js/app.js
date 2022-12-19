import './bootstrap';
import {
    createApp
} from 'vue'
import {
    FontAwesomeIcon
} from '@fortawesome/vue-fontawesome'
import {
    library
} from '@fortawesome/fontawesome-svg-core';
import {
    fas
} from '@fortawesome/free-solid-svg-icons'
import bootstrap from 'bootstrap/dist/css/bootstrap.min.css'
import router from './router/router';
import store from './store/index';
import Home from './components/Home.vue';
import Navbar from './components/Navbar.vue'

library.add(fas)
createApp(Navbar)
    .component('fontAwsomeIcon', FontAwesomeIcon)
    .use(bootstrap)
    .use(router)
    .use(store)
    .mount('#app');