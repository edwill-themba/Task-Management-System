<template>
  <div class="container">
      <div class="login">
         <h3>Enter Login Details</h3>
           <form @submit.prevent="loginUser()">
                <div class="form-row">
                   <input type="email" class="form-input" v-model="userData.email" name="email" placeholder="Enter your full email">
                   <p class="text-danger" v-text="sessionError.email"></p>
                </div>
                <div class="form-row">
                   <input type="password" class="form-input" v-model="userData.password" name="password" placeholder="Enter your password">
                      <p class="text-danger" v-text="sessionError.password"></p>
                </div>
                <div class="form-row">
                  <input type="radio" name="user_type1" value="subordinate" v-model="userData.user_type" > subordinate
                  <input type="radio" name="user_type2" value="supervisor" v-model="userData.user_type"> supervisor 
                  <p class="text-danger" v-text="sessionError.user_type"></p>
                </div>
                <div class="form-row">
                   <button class="btn btn-secondary btn-block button" type="submit">login</button>
               </div>
               <input type="hidden" name="device_name" v-model="userData.device_name">
               <p class="text-danger" v-text="sessionError.device_name"></p>
                <div class="form-row">
                   <router-link to="/register">Do you have an account? Register</router-link>
               </div>
           </form>
           <div class="errors">
               <div v-if="error">
                   <div class="alert alert-danger">
                       {{  error }}
                   </div>
               </div>
           </div>
      </div>
  </div>
</template>


<script>
export default {
  name: "Login",
  data() {
    return {
      userData: {
        email: "",
        password: "",
        user_type: "",
        device_name: "user_device"
      },
      //user errors
      sessionError: "",
      // server errors
      error: ""
    };
  },
  methods: {
    // logs the user in
    loginUser() {
      this.$store
        .dispatch("login", this.userData)
        .then(response => {
          this.getCurrentUser();
          this.$router.push({ path: "/mytasks" });
        })
        .catch(error => {
          this.sessionError = error.response.data.errors;
          this.error = error;
        });
    },
    // get the current user
    getCurrentUser() {
      this.$store
        .dispatch("getCurrentUserLogin")
        .then(response => {
          console.log("current user is" + response);
        })
        .catch(error => {
          this.error = error;
        });
    }
  }
};
</script>

<style scoped>
.login {
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
.form-row a {
  color: #111;
  text-decoration: none;
  text-align: center;
  padding-left: 55px;
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

@media (max-width: 778px) {
  .form-input {
    width: 300px;
    height: 20px;
    border: none;
    border-bottom: 1px solid #111;
    outline: none;
    font-size: 14px;
    font-weight: 500;
  }
  .button {
    font-size: 14px;
    font-weight: 500;
    width: 300px;
  }

  .form-row a {
    color: #111;
    text-decoration: none;
    text-align: center;
  }
}
</style>

