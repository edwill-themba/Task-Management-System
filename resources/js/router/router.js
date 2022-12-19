import {
    createRouter,
    createWebHistory
} from 'vue-router';
import store from '../store/index';
import Home from '../components/Home.vue';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import Logout from '../components/Logout.vue';
import AddTask from '../components/AddTask.vue';
import MyTasks from '../components/MyTasks.vue';
import CreateUserTask from '../components/CreateUserTask.vue';
// create an array of routes
const routes = [{
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/logout',
        name: 'Logout',
        component: Logout,
    },
    {
        path: '/task',
        name: 'AddTask',
        component: AddTask,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/mytasks',
        name: 'MyTasks',
        component: MyTasks,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/create_user_task',
        name: 'CreateUserTask',
        component: CreateUserTask,
        meta: {
            requiresAuth: true,
        },
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters.isLoggedIn) {
            next({ path: '/login' })
        } else {
            next();
        }
    } else {
        next();
    }
})

export default router