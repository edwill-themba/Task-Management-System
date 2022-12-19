<template>
    <div class="container">
       <div class="logout">
          <h3>You Have Logged Out Successfully</h3>
          <div class="error">
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
  name: "Logout",
  data() {
    return {
      error: ""
    };
  },
  created() {
      this.logoutUser();
  },
  methods:{
  // logs then user out
  logoutUser() {
    this.$store
      .dispatch("logout")
      .then(() => {
        this.$router.push({ path: "/login" });
        console.log("you have successfully logged out");
      })
      .catch(error => {
        this.error = error;
      });
  },
  },
}
</script>


<style scoped>
.logout {
  margin-top: 35px;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.errors {
  margin-top: 20px;
  font-size: 17px;
  font-weight: 500;
}
</style>
