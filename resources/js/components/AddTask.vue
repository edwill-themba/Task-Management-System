<template>
     <div class="container">
          <div class="add-task">
              <h3>Add My Task</h3>
              <form @submit.prevent="addTask()">
                  <div class="form-row">
                      <input type="text" class="form-control" name="task_name" v-model="task.task_name"  placeholder="Enter task name">
                  </div>
                   <div class="form-row">
                      <input type="date" class="form-control" name="task_date" v-model="task.task_date">
                    
                   </div>
                   <div class="form-row">
                      <input type="time" class="form-control" name="task_time" v-model="task.task_time">
                    
                  </div>
                  <div class="form-row">
                       <button class="btn btn-secondary btn-block button" type="submit">Add Task</button>
                  </div>
               </form>
               <div class="errors">
                 <div v-if="error">
                   <div class="alert alert-danger">
                       {{  error }}
                   </div>
                 </div>
               </div>
               <div class="session-errors">
                 <div v-if="sessionError">
                   <div class="alert alert-danger">
                       {{  sessionError }}
                   </div>
                 </div>
               </div>
          </div>
     </div>
</template>

<script>
export default {
  name: "AddTask",
  data() {
    return {
      task: {
        task_date: "",
        task_name: "",
        task_time: ""
      },
      error: "",
      sessionError: ""
    };
  },
  methods: {
    // add new task
    addTask() {
      this.$store
        .dispatch("addNewTask", this.task)
        .then(response => {
          this.$router.push({ path: "/mytasks" });
          this.error = "";
          this.sessionError = "";
        })
        .catch(error => {
          console.log(error);
          this.error = error.response.data.message;
          this.sessionError = error.response.data.errors;
        });
    }
  }
};
</script>


<style scoped>
.add-task {
  margin-top: 20px;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.form-row {
  margin-top: 15px;
}
.form-input {
  width: 400px;
  height: 40px;
  border: none;
  border-bottom: 1px solid #111;
  outline: none;
  font-size: 17px;
  font-weight: 500;
  padding-left: 10px;
}
.button {
  font-size: 17px;
  font-weight: 500;
  width: 400px;
}
.button:hover {
  border: 2px solid #ccc;
}
.errors {
  margin-top: 20px;
  font-size: 17px;
  font-weight: 500;
}
.session-errors {
  margin-top: 20px;
  font-size: 17px;
  font-weight: 500;
}
</style>

