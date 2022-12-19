<template>
    <div class="container">
      <div v-if="users && !showUserTask">
        <div class="create-user-task">
             <table class="table table-success table-task">
                <tr>
                  <th class="task-header-column">Full Name</th>
                  <th class="task-header-column">Position</th>
                  <th class="task-header-column"> Create Task</th>
                  <th class="task-header-column">View User Schedule</th>
                </tr>
                <tr v-for="(user,index) in users" :key="index">
                   <td class="task-column">{{ user.name }}</td>
                   <td class="task-column">{{ user.type }}</td>
                   <td class="task-column">
                       <button class="btn btn-info button" @click="showModalForm(user)">Create User Tasks</button>
                   </td>
                    <td class="task-column">
                       <button class="btn btn-secondary button" @click="viewUserTask(user.id)">View User Tasks</button>
                   </td>
                </tr>
             </table>
              <div class="error">
                <div v-if="error">
                   <div class="alert alert-danger">
                      {{ error }}
                   </div>
                 </div>
            </div>
        </div>
     </div>
      <div v-if="users && showUserTask">
          <div class="user-task">
            <div class="error">
                <div v-if="error">
                   {{ error }}
                </div>
              </div>
              <div class="title">
                 <button class="btn btn-primary" @click="back()">Go Back </button>
              </div>
              <div v-if="!usersTask">
                  <h1>the user have no tasks</h1>
              </div>
              <div class="tasks" v-if="usersTask">
                 <h3>The user is not available on this dates</h3>
                   <table class="table table-bordered">
                     <tr>
                        <th class="task-header-column">User Name</th>
                        <th class="task-header-column">Task Name</th>
                        <th class="task-header-column">Task Date</th>
                        <th class="task-header-column"></th>
                        <th class="task-header-column"></th>
                     </tr>
                     <tr v-for="(usertask,index) in usersTask" :key="index">
                        <td class="task-column">{{ usertask.name }}</td>
                        <td class="task-column">{{ usertask.task_name }}</td>
                        <td class="task-column">{{ usertask.task_date }}</td>
                         <td class="task-column">
                          <button class="btn btn-warning button-edit" @click="editUserSupervisorTask(usertask)"><fontAwsomeIcon class="icon" icon="pen"/></button>
                         </td>
                         <td class="task-column">
                          <button class="btn btn-danger button-delete" @click=" removeUserSupervisorTask(usertask.id)"><fontAwsomeIcon class="icon" icon="trash"/></button>
                         </td>
                     </tr>
                   </table>
              </div>
          </div>
      </div>
      <!-- add user task modal -->
      <teleport to="#modal">
         <div class="modal-form" v-if="showModal">
             <h3>Create a task for this user</h3>
              <form @submit.prevent="createUserTask()">
                <div class="form-row">
                  <input type="text" class="form-input" name="task_name" v-model="task.task_name"  placeholder="Enter task name">
                </div>
                <div class="form-row">
                  <input type="date" class="form-input" name="task_date" v-model="task.task_date">
                </div>
                <div class="form-row">
                  <input type="time" class="form-input" name="task_time" v-model="task.task_time">
                </div>
                  <input type="hidden" name="user_id" v-model="user.id">
                <div class="form-row">
                  <button class="btn btn-secondary btn-block button-modal" type="submit">Add Task</button>
                </div>
                <div class="form-row">
                  <button class="btn btn-warning btn-block button-modal" type="button" @click="closeModalForm()">Close Modal</button>
                </div>
            </form>
            <div class="error">
               <div v-if="error">
                  {{ error }}
               </div>
            </div>
             <div class="session-error">
               <div v-if="sessionError">
                  {{ sessionError }}
               </div>
            </div>
             <div class="session-success">
               <div v-if="sessionSuccess">
                  {{ sessionSuccess }}
               </div>
            </div>
         </div>
      </teleport>
      <!-- end add user task modal -->
      <teleport to="#modal">
        <div class="edit-modal" v-if="editModal">
          <form @submit.prevent="updateUserSupervisortask()">
             <div class="form-row">
                  <input type="text" class="form-input" name="task_name" v-model="task.task_name"  placeholder="Enter task name">
                </div>
                <div class="form-row">
                  <input type="date" class="form-input" name="task_date" v-model="task.task_date">
                </div>
                <div class="form-row">
                  <input type="time" class="form-input" name="task_time" v-model="task.task_time">
                </div>
                <div class="form-row">
                  <button class="btn btn-secondary btn-block button-modal" type="submit">update task</button>
                </div>
                <div class="form-row">
                  <button class="btn btn-danger btn-block button-modal" type="button" @click="closeEdit()">Close Modal</button>
                </div>
           </form>
           <div class="error">
               <div v-if="error">
                  {{ error }}
               </div>
            </div>
             <div class="session-error">
               <div v-if="sessionError">
                  {{ sessionError }}
               </div>
            </div>
             <div class="session-success">
               <div v-if="sessionSuccess">
                  {{ sessionSuccess }}
               </div>
            </div>
        </div>
      </teleport>
    </div>
</template>


<script>
export default {
  name: "CreateUserTasks",
  data() {
    return {
      users: [],
      usersTask: [],
      error: "",
      showUserTask: false,
      // show add user task modal
      showModal: false,
      // show edit user task modal
      editModal:false,
      user: {
        id: "",
        name: "",
        type: "",
        email: ""
      },
      task: {
        task_date: "",
        task_name: "",
        task_time: "",
        user_id: ""
      },
      sessionSuccess: "",
      sessionError: "",
      error: ""
    };
  },
  created() {
    this.getAllUsers();
  },
  methods: {
    // gets all the users
    getAllUsers() {
      this.$store
        .dispatch("allUsers")
        .then(response => {
          this.users = response.data.users;
          console.log(response);
        })
        .catch(error => {
          this.error = error.response.data.message;
          console.log(error);
        });
    },
    //  view user tasks
    viewUserTask(user_id) {
      this.showUserTask = true;
      this.$store
        .dispatch("supervisorUserTasks", user_id)
        .then(response => {
          this.usersTask = response.data.tasks;
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
    },
    // goes back
    back() {
      this.showUserTask = false;
    },
    // hides and show modal form
    showModalForm(user) {
      this.showModal = !this.showModal;
      this.user = user;
    },
    // close a modal form
    closeModalForm() {
      this.showModal = false;
    },
    // close edit modal form
    closeEdit(){
      this.editModal = false;
    },
    // shows the edit modal
    editUserSupervisorTask(usertask){
      this.editModal = !this.editModal;
      this.task = usertask;
    },
    // supervisor creates a task for a user
    createUserTask() {
      this.task.user_id = this.user.id;
      this.$store
        .dispatch("createUserTask", this.task)
        .then(response => {
          console.log(response);
          this.sessionSuccess = "task was saved successfully view user tasks";
          this.error = "";
          this.sessionError = "";
        })
        .catch(error => {
          this.sessionError = error.response.data.errors;
          this.error = error.response.data.message;
          console.log(error);
        });
    },
    removeUserSupervisorTask(id) {
      this.$store
        .dispatch("removeUserSupervisorTask", id)
        .then(() => {
          this.usersTask = this.usersTask.filter(t => t.id !== id);
          console.log("task was removed successfully");
        })
        .catch(error => {
          this.error = error.response.data.message;
          console.log(error);
        });
    },
    updateUserSupervisortask(){
      this.$store.dispatch("editSupervisorUserTask",this.task)
      .then((response) => {
         console.log(response);
         this.editModal = false;
      })
      .catch((error) =>{
         this.error = error.response.data.message;
         this.sessionError = error.response.data.errors;
         console.log(error);
      })
  }
  },
  
};
</script>

<style scoped>
.create-user-task {
  margin-top: 20px;
}
.task-header-column {
  text-align: center;
  font-size: 17px;
}
.task-column {
  height: 70px;
  width: auto;
  text-align: center;
  font-size: 15px;
  font-weight: 500;
}
.button {
  color: #111;
  font-weight: 500;
}
.user-task {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}
.title {
  display: flex;
  justify-content: space-between;
  flex-direction: row;
}

.edit-modal {
  margin: 30px;
  width: 500px;
  height: 500px;
  background-color: coral;
  position: fixed;
  border-radius: 2%;
  color: #fff;
  padding: 20px 5px;
  z-index: 200000;
  text-align: center;
  opacity: 1;
}

.modal-form {
  margin: 30px;
  width: 500px;
  height: 500px;
  background-color: #111;
  position: fixed;
  border-radius: 2%;
  color: #fff;
  padding: 20px 5px;
  z-index: 100000;
  text-align: center;
  opacity: 1;
}
.modal-form h3 {
  text-align: center;
}
.form-row {
  margin-top: 15px;
}
.form-input {
  width: 350px;
  height: 40px;
  border: none;
  border-bottom: 1px solid #fff;
  outline: none;
  font-size: 17px;
  font-weight: 500;
  padding-left: 10px;
  background-color: #111;
  color: #fff;
}
.button-modal {
  font-size: 17px;
  font-weight: 500;
  width: 400px;
}
.button-modal:hover {
  border: 2px solid #ccc;
}
.error {
  margin-top: 20px;
  color: red;
  text-align: center;
}
.session-error {
  margin-top: 20px;
  color: red;
}
.session-success {
  margin-top: 20px;
  color: green;
}
.button-delete {
  background-color: #ff0000;
  border: none;
  margin-left: 15px;
}
.button-delete:hover {
  background-color: #fff;
  color: #111;
  border: 1px solid #ff0000;
}
.button-edit {
  background-color: #ffff00;
  border: none;
  margin-left: 15px;
}
.button-edit:hover {
  background-color: #fff;
  color: #111;
  border: 1px solid #ffff00;
}

@media (max-width: 778px) {
  .task-header-column {
    text-align: center;
    font-size: 12px;
  }
  .task-column {
    height: 70px;
    width: auto;
    text-align: center;
    font-size: 12px;
    font-weight: 500;
  }
  .modal-form {
    margin: 30px;
    width: 300px;
    height: 500px;
    background-color: #111;
    position: fixed;
    border-radius: 2%;
    color: #fff;
    padding: 20px 5px;
    z-index: 100000;
    text-align: center;
  }
  .edit-modal{
    width:300px;
    height: 500px;
  }
  .edit-modal h3{
    font-size: 15px;
  }
  .form-input {
    width: 150px;
  }
  .button-modal {
    width: 150px;
    font-size: 15px;
  }
  .modal-form h3 {
    font-size: 15px;
  }
  .button {
    width: 100px;
    font-size: 12px;
  }
  .icon {
    font-size: 12px;
  }
}
</style>
