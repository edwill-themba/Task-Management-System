<template>
    <div class="sticky-top header">
        <nav class="navbar navbar-collapse bg-dark">
            <div class="container">
               <router-link to="/" class="brand">Task Management</router-link>
               <ul class="menu"  @click="hideNavigation()">
                   <li>
                       <router-link to="/">Tasks</router-link>
                   </li>
                    <li v-if="!isLoggedIn">
                       <router-link to="/register">Register</router-link>
                   </li>
                    <li v-if="!isLoggedIn">
                       <router-link to="/login">Login</router-link>
                   </li>
                    <li v-if="isLoggedIn">
                       <router-link to="/mytasks">My Task</router-link>
                   </li>
                   <li v-if="isLoggedIn">
                       <router-link to="/task">Add Task</router-link>
                   </li>
                   <li v-if="isLoggedIn" class="username">
                       <router-link to="#">{{ currentUserName }}</router-link>
                   </li>
                    <li v-if="isLoggedIn">
                       <router-link to="/logout">Logout</router-link>
                   </li>
               </ul>
           </div>
            <div class="show-hide-navigation">
            <fontAwsomeIcon icon="bars" class="bars"  @click="showNavigation()"/>
            <fontAwsomeIcon icon="times" class="close" @click="hideNavigation()" />
          </div>
          </nav>
         <router-view></router-view>
     </div>
</template>

<script>
export default {
  name: "Navbar",
  computed: {
    // check whether if the user is logged in
    isLoggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    // get the current user name
    currentUserName() {
      return this.$store.getters.getCurrentUser;
    }
  },
  methods: {
    // show navigation on small screens
    showNavigation() {
      const menu = document.querySelector(".menu");
      const bars = document.querySelector(".bars");
      const close = document.querySelector(".close");

      menu.classList.toggle("active");
      bars.classList.toggle("inactive");
      close.classList.toggle("active");
    },
    // hides navigations on small screens
    hideNavigation() {
      const menu = document.querySelector(".menu");
      const bars = document.querySelector(".bars");
      const close = document.querySelector(".close");

      menu.classList.remove("active");
      bars.classList.remove("inactive");
      close.classList.remove("active");
    }
  }
};
</script>

<style scoped>
.header {
  width: 100%;
  height: 70px;
  top: 0;
  left: 0;
  right: 0;
}
.brand {
  color: #fff;
  font-weight: 500;
  font-size: 23px;
  text-decoration: none;
}
.brand:hover {
  color: coral;
}

.menu {
  margin-top: 10px;
}

.menu li {
  display: inline;
  list-style-type: none;
  margin-left: 10px;
}
.menu li a {
  color: #fff;
  font-size: 18px;
  font-weight: 500;
  text-decoration: none;
  text-transform: capitalize;
}
.menu li a:hover {
  color: coral;
}
.menu .username a {
  color: coral;
}
.show-hide-navigation {
  color: #fff;
  display: flex;
  /*position: absolute; */
}
.show-hide-navigation .bars {
  display: none;
}
.show-hide-navigation .close {
  display: none;
}

@media (max-width: 778px) {
  .header {
    width: 100%;
  }
  .menu {
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background-color: #212529;
    left: -100%;
    right: 0;
    bottom: 0;
    margin-top: 40px;
  }
  .menu.active {
    left: 0;
  }
  .menu li {
    display: block;
    margin: 10px auto;
    text-align: center;
  }
  .menu li a {
    font-size: 14px;
  }
  .brand {
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    text-decoration: none;
  }
  .show-hide-navigation .bars {
    display: block;
    color: white;
    position: absolute;
    margin: -5px 10px 20px 0;
    left: 92%;
    font-size: 14px;
  }
  .show-hide-navigation .bars:hover {
    color: coral;
  }
  .show-hide-navigation .bars.inactive {
    display: none;
  }
  .show-hide-navigation .close.active {
    display: block;
    color: white;
    position: absolute;
    margin: -10px 15px 10px 0;
    left: 95%;
    font-size: 14px;
  }
  .show-hide-navigation .close.active:hover {
    color: crimson;
  }
}
</style>
