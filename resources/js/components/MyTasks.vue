<template>
    <div class="container">
       <div class="all-tasks">
         <router-link to="/create_user_task" class="btn btn-primary">Create For Task User</router-link>
           <div class="errors">
               <div v-if="error">
                   <div class="alert alert-danger">
                       {{  error }}
                   </div>
               </div>
          </div>
         <div v-if="mytasks.length">
           <h3>My Tasks</h3>
           <hr>
          <table class="table table-striped table-tasks">
            <tr>
               <th class="task-header-column-id">Task Id</th>
               <th class="task-header-column">Task Name</th>
               <th class="task-header-column">Task Date</th>
               <th class="task-header-column">Task Time</th>
               <th class="task-header-column"> </th>
               <th class="task-header-column"> </th>
            </tr>
            <tr   v-for="(task,index) in mytasks" :key="index">
              <td class="task-column">
                 <div class="counter">
                     {{ index + 1 }}
                 </div>
              </td>
              <td class="task-column-name">{{ task.task_name }}</td>
              <td class="task-column">{{ task.task_date }}</td>
              <td class="task-column">{{ task.task_time }}</td>
              <td class="task-column">
                 <button class="btn btn-warning button-edit" @click="editTask(task)"><fontAwsomeIcon class="icon" icon="pen"/></button>
              </td>
              <td class="task-column">
                 <button class="btn btn-danger button-delete" @click="deleteTask(task.id)"><fontAwsomeIcon class="icon" icon="trash"/></button>
              </td>
           </tr>
         </table>
        </div>
         <div v-else>
             <h1>There No Tasks Available For This User</h1>
         </div>
        <div class="my-assigned-tasks" v-if="assignedTasks.length">
            <h3>My Assigned Tasks</h3>
            <hr />
            <table class="table table-active table-tasks">
                 <tr>
                   <th class="task-header-column-id">Task Id</th>
                   <th class="task-header-column">Task Name</th>
                   <th class="task-header-column">Task Date</th>
                   <th class="task-header-column">Task Time</th>
                   <th class="task-header-column">Supervisor</th>
                 </tr>
                 <tr  v-for="(t,index) in assignedTasks" :key="index">
                    <td class="task-column">
                      <div class="count">
                        {{ index + 1 }}
                      </div>
                   </td>
                   <td class="task-column-name">{{ t.task_name }}</td>
                   <td class="task-column">{{ t.task_date }}</td>
                   <td class="task-column">{{ t.task_time }}</td>
                   <td class="task-column">{{ t.name }}</td>
                 </tr>
            </table>
        </div>
      </div>
      <teleport to="#modal">
         <div class="update-modal" v-if="editModal">
           <h3>edit task</h3>
           <form @submit.prevent="updateUserTask()">
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
               <div v-if="sessionUpdate">
                  {{ sessionUpdate }}
               </div>
            </div>
         </div>
      </teleport>
    </div>
</template>

<script>
export default {
  name: "MyTasks",
  data() {
    return {
      mytasks: [],
      assignedTasks: [],
      error: "",
      editModal: false,
      task: {
        id: "",
        task_date: "",
        task_time: "",
        task_name: ""
      },
      sessionError: "",
      error: "",
      sessionSuccess: "",
      sessionUpdate: ""
    };
  },
  created() {
    this.getAllMyTasks();
    this.getAllMyAssignedTask();
  },
  methods: {
    // return all tasks associated with specific user
    getAllMyTasks() {
      this.$store
        .dispatch("allMyTasks")
        .then(response => {
          this.mytasks = response.data.tasks;
          console.log(this.mytasks);
          this.error = "";
        })
        .catch(error => {
          this.error = error.response.data.message;
          console.log(error);
        });
    },
    // opens the edit modal
    editTask(task) {
      this.editModal = !this.editModal;
      this.task = task;
    },
    // closes the modal form
    closeModalForm() {
      this.editModal = false;
    },
    // returns all task assigned by a supervisor
    getAllMyAssignedTask() {
      this.$store
        .dispatch("allMyAssignedTasks")
        .then(response => {
          this.assignedTasks = response.data.tasks;
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
    },
    // deletes task
    deleteTask(id) {
      let confirmation = confirm("Are sure you want to delete this task?");
      if (confirmation) {
        this.$store
          .dispatch("removeMyTask", id)
          .then(() => {
            this.mytasks = this.mytasks.filter(t => t.id !== id);
          })
          .catch(error => {
            this.error = error.response.data.message;
            console.log(error);
          });
      }
    },
    updateUserTask() {
      this.$store
        .dispatch("editUserTask", this.task)
        .then(response => {
          console.log(response);
          this.sessionUpdate = "task was updated successfully";
        })
        .catch(error => {
          this.error = error.response.data.message;
          this.sessionError = error.response.data.errors;
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.all-tasks {
  margin-top: 20px;
}
.all-tasks h1 {
  text-align: center;
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
.task-column-name {
  text-align: center;
  margin-left: 0px;
  padding-left: 0px;
  font-size: 15px;
  font-weight: 500;
}
.task-header-column-id {
  text-align: left;
}
.task-column .counter {
  height: 50px;
  width: 50px;
  background-color: coral;
  color: #fff;
  font-weight: bold;
  font-size: 20px;
  padding: 10px auto;
  border-radius: 50%;
  text-align: center;
  margin-left: 10px;
}
.task-column .count {
  height: 50px;
  width: 50px;
  background-color: purple;
  color: #fff;
  font-weight: bold;
  font-size: 20px;
  padding: 10px auto;
  border-radius: 50%;
  text-align: center;
  margin-left: 20px;
}
.button-delete {
  background-color: #ff0000;
  border: none;
}
.button-delete:hover {
  background-color: #fff;
  color: #111;
  border: 1px solid #ff0000;
}
.button-edit {
  background-color: #ffff00;
  border: none;
}
.button-edit:hover {
  background-color: #fff;
  color: #111;
  border: 1px solid #ffff00;
}
.errors {
  margin-top: 20px;
  font-size: 17px;
  font-weight: 500;
}
.update-modal {
  margin: 30px;
  width: 500px;
  height: 500px;
  background-color: #111;
  position: fixed;
  border-radius: 2%;
  color: #fff;
  padding: 20px 5px;
  z-index: 10000;
  text-align: center;
  opacity: 1;
}
.update-modal h3 {
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

@media (max-width: 778px) {
  .table-tasks {
    width: 470px;
  }
  .task-header-column {
    text-align: center;
    font-size: 14px;
  }
  .task-column {
    height: 70px;
    width: auto;
    text-align: center;
    font-size: 12px;
    font-weight: 500;
  }
  .task-column .counter {
    height: 25px;
    width: 25px;
    background-color: #fff;
    color: #111;
    font-weight: bold;
    font-size: 12px;
    padding: auto;
    text-align: center;
    margin-left: 20px;
  }
  .task-column-name {
    text-align: left;
    margin-left: 0px;
    padding-left: 0px;
    font-size: 12px;
    font-weight: 500;
    width: 100px;
  }
  .icon {
    font-size: 12px;
  }
  .errors {
    margin-top: 20px;
    font-size: 14px;
    font-weight: 500;
  }
  .form-input {
    width: 150px;
  }
  .button-modal {
    width: 150px;
    font-size: 15px;
  }
  .update-modal h3 {
    font-size: 15px;
  }
  .update-modal {
    width: 300px;
  }
}
</style>
