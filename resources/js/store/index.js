import {
    createStore
} from 'vuex';
import axios from 'axios';

const store = createStore({

    state: {
        accessToken: localStorage.getItem('accessToken') || null,
        username: localStorage.getItem('username') || null,
        tasks: [],
        myTasks: [], // tasks associated with specific user
        assignedTasks: [],
        allusers: [],
        supervisorTasks: [],
    },
    getters: {
        // get the access token status
        isLoggedIn(state) {
            if (state.accessToken !== null) {
                return true;
            } else {
                return false;
            }
        },
        // get the current user name
        getCurrentUser() {
            return localStorage.getItem('username');
        },
        // all users tasks
        allTheUsersTasks(state) {
            return state.tasks;
        },
        userSpecificTasks(state) {
            return state.myTasks;
        }
    },
    actions: {
        /**
         * Creates a new user  
         * @param {*} param0 
         * @param {*} userData 
         */
        register({
            commit
        }, userData) {
            return new Promise((resolve, reject) => {
                axios.post('http://localhost:8000/api/register', {
                        user_type: userData.user_type,
                        name: userData.name,
                        email: userData.email,
                        password: userData.password,
                        password_confirmation: userData.password_confirmation
                    })
                    .then((response) => {
                        resolve(response);
                        commit('register', response.data);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },
        /**
         * logs the user in
         * @param {*} param0 
         * @param {*} userData 
         * @returns access_token
         */
        login({
                commit
            },
            userData) {
            return new Promise((resolve, reject) => {
                axios.post('http://localhost:8000/api/login', {
                        user_type: userData.user_type,
                        email: userData.email,
                        password: userData.password,
                        device_name: userData.device_name
                    })
                    .then((response) => {
                        resolve(response);
                        console.log(response.data);
                        localStorage.setItem('accessToken', response.data);
                        commit('loginUser', response.data);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },
        /**
         * Get the current user name who is logged in
         * @param {*} { commit }
         */
        getCurrentUserLogin({
            commit
        }) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.get('http://127.0.0.1:8000/api/user')
                    .then((response) => {
                        localStorage.setItem('username', response.data.name);
                        resolve(response);
                        commit('currentUserLogin', response.data);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },
        /**
         * logs the user out and delete the token
         * @param {*} param0 
         */
        logout({
            commit
        }) {
            return new Promise((resolve, reject) => {
                // sets auth headers
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
                axios.post('http://localhost:8000/api/logout', {})
                    .then((response) => {
                        localStorage.removeItem('accessToken');
                        localStorage.removeItem('username');
                        resolve(response);
                        commit('logoutUser', null);
                    })
                    .catch((error) => {
                        localStorage.removeItem('accessToken');
                        localStorage.removeItem('username');
                        reject(error);
                    })
            })

        },
        /**
         * Gets all the users tasks on the database
         * @param {*} param0 
         */
        allUserTasks({
            commit
        }) {
            return new Promise((resolve, reject) => {
                axios.get('http://127.0.0.1:8000/api/tasks')
                    .then((response) => {
                        resolve(response);
                        commit('allUserTasks', response.data);
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },
        /**
         *
         * Creates a new task for a supervisor or subordinate
         * @param {*} { commit }
         * @param {*} task
         */
        addNewTask({
            commit
        }, task) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.post('http://localhost:8000/api/task', {
                        task_name: task.task_name,
                        task_date: task.task_date,
                        task_time: task.task_time
                    })
                    .then((response) => {
                        commit('newUserTask', response.data);
                        console.log(response);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },

        /**
         * Get all tasks associated with specific user
         * @param {*} param0 
         */
        allMyTasks({
            commit
        }) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.get('http://localhost:8000/api/user_tasks')
                    .then((response) => {
                        commit('allMyTasks', response.data);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },

        /**
         * Gets all task assigned by a supervisor
         * @param {*} 
         */
        allMyAssignedTasks({
            commit
        }) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.get('http://localhost:8000/api/assigned_tasks')
                    .then((response) => {
                        commit('allMyAssignedTasks', response.data);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },

        /**
         * Gets all the users
         * @param {*} param0 
         */
        allUsers({
            commit
        }) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.get('http://localhost:8000/api/user_lists')
                    .then((response) => {
                        commit('allMyUsers', response.data);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },

        /**
         * Helps supervisor to see free users schedule
         * @param {*} param0 
         */
        supervisorUserTasks({
            commit
        }, id) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.get('http://localhost:8000/api/user_task/' + id)
                    .then((response) => {
                        commit('supervisorUserTasks', response.data);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },

        /**
         * Supervisor creates a task for a user
         * @param {*} param0 
         * @param {*} task 
         * @param {*} user_id 
         */
        createUserTask({
            commit
        }, task) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.post('http://localhost:8000/api/user_task', {
                        task_name: task.task_name,
                        task_date: task.task_date,
                        task_time: task.task_time,
                        user_id: task.user_id,
                    })
                    .then((response) => {
                        commit('supervisorCreateUserTask', response.data);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },

        /**
         * Removes user own task
         * @param {*} param0 
         * @param {*}  
         */
        removeMyTask({
            commit
        }, id) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.delete('http://localhost:8000/api/task/' + id)
                    .then((response) => {
                        resolve(response);
                        commit('removeUserTask', id);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },

        /**
         * Removes user created task by a supervisor
         * @param {*} param0 
         * @param {*}  
         */
        removeUserSupervisorTask({
            commit
        }, id) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.delete('http://localhost:8000/api/user_supervisor_task/' + id)
                    .then((response) => {
                        resolve(response);
                        commit('deleteUserSupervisorTask', id);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },
        /**
         * Edit User task
         * @param {*} param0 
         * @param {*}  
         */
        editUserTask({
            commit
        }, task) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.patch('http://localhost:8000/api/task/' + task.id, {
                        task_name: task.task_name,
                        task_date: task.task_date,
                        task_time: task.task_time
                    })
                    .then((response) => {
                        resolve(response);
                        commit('updateTask', response.data);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },
        /**
         * Edit User Supervisor task
         * @param {*} param0 
         * @param {*}  
         */
        editSupervisorUserTask({
            commit
        }, task) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
            return new Promise((resolve, reject) => {
                axios.patch('http://localhost:8000/api/user_supervisor_task/' + task.id, {
                        task_name: task.task_name,
                        task_date: task.task_date,
                        task_time: task.task_time
                    })
                    .then((response) => {
                        resolve(response);
                        commit('updateSupervisorTask', response.data);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            })
        },
        /**
         * Searches tasks on the database
         * @param {*} param0 
         */
        search({
            commit
        }, name) {
            return new Promise((resolve, reject) => {
                axios.get('http://localhost:8000/api/task/search/' + name)
                    .then((response) => {
                        resolve(response);
                        commit('searchUserTasks', response.data);
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },



    },
    mutations: {
        // updates the access token on the state
        loginUser(state, access_token) {
            state.accessToken = access_token;
        },
        // remove the access tokn from local storage 
        logoutUser(state) {
            state.accessToken = null;
        },
        // updates the state and put the current user name
        currentUserLogin(state, username) {
            state.username = username;
        },
        // gets the users task
        allUserTasks(state, tasks) {
            state.tasks = tasks;
        },
        // add new task and updates tasks array
        newUserTask(state, task) {
            state.myTasks = task;
            state.tasks = task;
        },
        allMyTasks(state, mytasks) {
            state.myTasks = mytasks.tasks;
        },
        allMyAssignedTasks(state, myassignedTasks) {
            state.assignedTasks = myassignedTasks;
        },
        allMyUsers(state, users) {
            state.allusers = users;
        },
        supervisorUserTasks(state, users_task) {
            state.supervisorTasks = users_task;
        },
        supervisorCreateUserTask(state, task) {
            state.tasks = task;
        },
        // removes user created own task  
        removeUserTask(state, id) {
            state.tasks = state.tasks.filter(t => t.id !== id);
            state.myTasks = state.myTasks.filter(t => t.id !== id);
        },
        // deletes user supervisor task
        deleteUserSupervisorTask(state, id) {
            state.tasks = state.tasks.filter(t => t.id !== id);
            state.myTasks = state.myTasks.filter(t => t.id !== id);
        },
        // updates user task
        updateTask(state, task) {
            state.tasks = task;
        },
        updateSupervisorTask(state, task) {
            state.tasks = task;
        },
        searchUserTasks(state, task) {
            state.tasks = task;
        }
    },
})

export default store;