<template>
    <div class="container">
     <div class="search-area">
        <form @submit.prevent="searchTask()">
           <input type="search" name="query" v-model="search_query" class="search-input" placeholder="search...">
           <button type="submit" class="search-button"><fontAwsomeIcon icon="search" /></button>
        </form>
        <div v-if="search">
          <ul v-for="(s,index) in search_results" :key="index">
            <li>{{ s.task_name }} {{ s.task_date}} {{ s.task_time }}</li>
          </ul>
          <button class="btn btn-info btn-go-back" @click="hideSearch()">Go back</button>
        </div>
     </div>
     <div v-if="tasks">
      <div class="all-tasks">
         <div class="pages-to-display">
           <h6>SELECT NUMBER OF TASKS TO DISPLAY </h6>
           <select name="noOfPages" @change="selectNoPages($event)" v-model="pagesToDisplay">
              <option value="">please select an option</option>
              <option value="5">5</option>
              <option value="10">10</option>
           </select>
         </div>
         <div class="pagination">
          <!-- pagination component -->
            <Pagination :totalRecords="tasks.length" 
                        :perPage ="pagesToDisplay"
                         v-on:changePage="changePage($event)"/>
           <!-- end pagination  component -->
         </div>
         <table class="table table-bordered table-tasks">
            <tr>
               <th class="task-header-column-id">Task Id</th>
               <th class="task-header-column">Task Name</th>
               <th class="task-header-column">Task Date</th>
               <th class="task-header-column">Task Time</th>
            </tr>
            <tr   v-for="(task,index) in paginatedTasks" :key="index">
              <td class="task-column">
                 <div class="counter">
                     {{ task.id }}
                 </div>
              </td>
              <td class="task-column-name">{{ task.task_name }}</td>
              <td class="task-column">{{ task.task_date }}</td>
              <td class="task-column">{{ task.task_time }}</td>
            </tr>
         </table>
         <div class="errors">
            <div v-if="error">
              <div class="alert alert-danger">
                 {{  error }}
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import Pagination from "./Pagination.vue";
export default {
  name: "Home",
  components: {
    Pagination
  },
  computed: {
    // returns the modified array
    paginatedTasks() {
      let firstIndex = (this.page - 1) * this.pagesToDisplay;
      let lastIndex = this.page * this.pagesToDisplay;
      console.log(firstIndex, lastIndex);
      return this.tasks.slice(firstIndex, lastIndex);
    }
  },
  data() {
    return {
      tasks: [],
      error: "",
      // paginate object
      page: 1,
      totalRecords: "",
      search: false,
      search_query: "",
      search_results: [],
      pagesToDisplay: 5
    };
  },
  created() {
    this.getAllUserTasks();
  },
  methods: {
    // display all the tasks
    getAllUserTasks() {
      this.$store
        .dispatch("allUserTasks")
        .then(tasks => {
          this.tasks = tasks.data.tasks;
          console.log(this.tasks);
        })
        .catch(error => {
          this.error = error;
          console.log(error);
        });
    },
    changePage(page) {
      this.page = page;
    },
    searchTask() {
      if (this.search_query == "") {
        alert("The search question is required");
        return;
      }
      this.$store
        .dispatch("search", this.search_query)
        .then(response => {
          this.search_results = response.data.task;
          console.log(this.search_results);
          this.search_query = "";
          this.search = true;
        })
        .catch(error => {
          alert(error.response.data.message);
          console.log(error.response.data.message);
        });
    },
    hideSearch() {
      this.search = false;
    },
    selectNoPages(event) {
      this.pagesToDisplay = event.target.value;
      console.log(this.pagesToDisplay);
    }
  }
};
</script>

<style scoped>
.search-area {
  margin-top: 15px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
}
.all-tasks {
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
}
.pagination {
  margin: 20px 0px;
}
.all-tasks h4 {
  color: purple;
  margin: 5px auto;
  font-weight: bold;
}
.task-header-column {
  text-align: center;
  font-size: 17px;
}
.task-header-column-id {
  text-align: left;
  padding-left: 45px;
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
  margin-left: 30px;
  padding-left: 60px;
  font-size: 15px;
  font-weight: 500;
}
.task-column .counter {
  height: 50px;
  width: 50px;
  background-color: purple;
  color: #fff;
  font-weight: bold;
  font-size: 20px;
  margin-left: 45px;
  padding: 10px auto;
  border-radius: 50%;
  text-align: center;
}
.search-input {
  width: 500px;
  height: 35px;
  border: none;
  outline: none;
  border-bottom: 1px solid #111;
  padding-left: 10px;
}
.search-button {
  width: 100px;
  height: 35px;
  margin: auto 7px;
  border: none;
  background-color: purple;
  color: #fff;
  outline: none;
  cursor: pointer;
}
.search-button:hover {
  background-color: #fff;
  color: purple;
  border: 2px solid purple;
}
.errors {
  margin-top: 20px;
  font-size: 17px;
  font-weight: 500;
  text-align: center;
}
.tasks-pagination span {
  margin: auto 15px;
  font-weight: 500;
}
.btn-go-back {
  margin-top: 10px;
  margin-left: 25px;
  width: 350px;
  text-align: center;
}
.icon {
  color: #111;
  cursor: pointer;
}
.icon:hover {
  color: coral;
}
.pages-to-display h6 {
  font-weight: 700;
}
.pages-to-display select {
  margin-left: 25px;
}
@media (max-width: 778px) {
  .all-tasks {
    font-size: 14px;
  }
  .table-tasks {
    width: 400px;
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
  }
  .task-header-column-id {
    text-align: left;
  }
  .task-column-name {
    text-align: center;
    margin-left: 0px;
    padding-left: 0px;
    font-size: 12px;
    font-weight: 500;
    width: 100px;
  }
  .search-input {
    width: 170px;
    height: 25px;
    font-size: 12px;
  }
  .search-button {
    width: 50px;
    height: 25px;
    font-size: 12px;
  }
  .pages-to-display select {
    margin-left: 60px;
  }
}
</style>
