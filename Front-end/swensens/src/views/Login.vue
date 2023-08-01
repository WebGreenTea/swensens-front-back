<template>
    <div class="modal-loading" v-if="loadscreen">
      <div class="load-icon-container">
    <div class="load"></div>
  </div>
  </div>
  <section class="site-body">
    <div class="layout-content">
      <div class="box-login">
        <div class="box-header">
          <div class="box-header-content">
            <h2 class="box-herder">ยินดีต้อนรับ</h2>
            <p class="box-p">เข้าสู่ระบบเพื่อใช้งาน</p>
          </div>
        </div>
        <div class="box-body">
          <div class="login-form">
            <form v-on:submit.prevent="login()">
              <div class="input-row">
                <div>
                  <div class="form-label">
                    <label title="อีเมล">อีเมล</label>
                  </div>
                </div>
                <div>
                  <input
                    v-model="email"
                    type="email"
                    placeholder="กรอกอีเมล"
                    class="form-input"
                  />
                </div>
              </div>
              <div class="input-row">
                <div>
                  <div class="form-label">
                    <label title="รหัสผ่าน">รหัสผ่าน</label>
                  </div>
                </div>
                <div>
                  <input
                  v-model="password"
                    type="password"
                    placeholder="กรอกรหัสผ่าน"
                    class="form-input"
                  />
                </div>
              </div>
              <div class="input-row">
                <div>
                  <div class="forgot-password"><a>ลืมรหัสผ่าน</a></div>
                </div>
                <div>
                  <button type="submit" v-bind:disabled="!isReadyLogin()" class="login-form-btn">
                    เข้าสู่ระบบ
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
  name: "LoginPage",
  computed: {
    ...mapGetters(['user'])
  },
  data() {
    return {
      email:"",
      password:"",

      loadscreen: false,
    }
  },
  methods: {
    login(){
      this.loadscreen = true;
      axios.post(`${process.env.VUE_APP_API_URL}/login`, {email:this.email,password:this.password}).then((res)=>{
        console.log(res)
        if(res.data.code == "OK" && res.data.success){
          localStorage.setItem("token", res.data.data.token.access_token);
          localStorage.setItem("refresh_token",res.data.data.token.refresh_token);
          this.$store.dispatch('user',res.data.data.user);
          console.log(localStorage.getItem('token'))
          console.log('000000')
          this.$router.push("/th");
        }
      }).catch((error)=>{
        console.log(error)
        alert('Sorry, The username or password is incorrect.')
      }).finally(()=>{
        this.loadscreen = false;
      })
    },
    isReadyLogin() {
      if (this.email.length == 0 || this.password.length == 0) {
        return false;
      }
      return true;
    },
  },
  created() {
    if(this.user){
      this.$router.push({ path: '/th' }).then(() => { this.$router.go() })
    }
  },
  
};
</script>

<style>
</style>