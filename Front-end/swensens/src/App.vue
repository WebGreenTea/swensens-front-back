<!-- <template>
  <nav>
    <router-link to="/">Home</router-link> |
    <router-link to="/about">About</router-link>
  </nav>
  <router-view/>
</template>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
</style> -->
<template>
  <section style="display: flex; flex-direction: column; min-height: 100vh">
    <header-menu></header-menu>
    <div class="content-wrapper">
      <router-view></router-view>
    </div>

    <footer-content></footer-content>
  </section>
</template>

<script>
import Header from "./components/Nav.vue";
import FooterContent from "./components/FooterContent.vue";
import axios from "axios";

export default {
  name: "app",
  components: {
    "header-menu": Header,
    "footer-content": FooterContent,
  },
  data() {
    return {
      dummy_user: {
        firstname: "loading...",
        lastname: "loading...",
        phone: "loading...",
        email: "loading...",
        birthday: "loading...",
        gender: "loading...",
      },
    };
  },
  async created() {
    // console.log("token",localStorage.getItem("token"));
    if (localStorage.getItem("token") !== null) {
      this.$store.dispatch("user", this.dummy_user);
      try{
        const res = await axios.get(`${process.env.VUE_APP_API_URL}/user`, {headers: { Authorization: localStorage.getItem("token") },});
        this.$store.dispatch("user", res.data.data);
      }catch(err){
        try{
          const resRefresh = await axios.get(`${process.env.VUE_APP_API_URL}/refresh`,{headers: { "Refresh-Token": localStorage.getItem("refresh_token") },});
          if (resRefresh.data.code == "OK" && resRefresh.data.success) {
            localStorage.setItem("token",resRefresh.data.data.token.access_token);
            localStorage.setItem("refresh_token",resRefresh.data.data.token.refresh_token);
            this.$store.dispatch("user", resRefresh.data.data);
          }
        }catch(err){
          this.$store.dispatch("user", null);
          localStorage.removeItem('token')
          localStorage.removeItem('refresh_token')
          alert('Session Expired')
          this.$router.push("/login");
        }
      }
    } else {
      this.$store.dispatch("user", null);
    }
  },
};
</script>

<style>
@import "./assets/css/mainStyle.css";
</style>
