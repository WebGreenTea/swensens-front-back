<template>
  <div class="modal-loading" v-if="loadscreen">
    <div class="load-icon-container">
        <div class="load"></div>
    </div>
  </div>
  <div class="site-herder">
    <div class="qr-btn">
      <div>
        <img src="@/assets/qr.svg" alt="">
      </div>
    </div>
    <div class="right-menu-btn">
      <div class="right-btn">
        <img src="@/assets/bag.svg" alt="">
      </div>
      <div class="right-btn" @click="openDrawer()">
        <img src="@/assets/menu.svg" alt="">
      </div>
    </div>
    <div class="logo">
      <a href=""><img src="@/assets/logo.svg" /></a>
    </div>
    <div class="header-right">
      <div class="hearder-btn-block">
        <a class="header-delivery" href="">
          <div class="herder-menu-warp">
            <img src="@/assets/map-icon.svg" alt="" />กรุณาเลือกที่อยู่จัดส่ง
            <img src="@/assets/down.svg" style="margin-left: 65px;" />
          </div>
        </a>
      </div>
      <div class="hearder-btn-block" v-if="!user">
        <router-link to="/register"><a href="" class="herder-btn">สมัครสมาชิก</a></router-link>
      </div>
      <div class="hearder-btn-block" v-if="!user">
        <router-link to="/login"><a class="herder-btn herder-btn-primary">เข้าสู่ระบบ</a></router-link>
      </div>
      <div class="hearder-btn-block" v-if="user">
        <button href="" class="header-big-qr-btn"><span class="header-span-qr-icon"><img src="@/assets/qr-red.svg" alt=""></span>สแกน</button>
      </div>
        <div class="hearder-btn-block block-language-btn" v-if="user">
          <div class="header-language">
            <span class="header-span-icon" style="margin-top: 0px;"><img src="@/assets/bag.svg" alt="" /></span>
          </div>
        </div>
        <div class="hearder-btn-block block-language-btn" v-if="user">
          <div class="header-language">
            <span class="header-span-icon"><img src="@/assets/love.svg" alt="" /></span>
          </div>
        </div>
        <div class="hearder-btn-block block-language-btn" v-if="user">
          <div class="header-language">
            <span class="header-span-icon"><img src="@/assets/inbox.svg" alt="" /></span>
          </div>
        </div>
        <div class="hearder-btn-block block-language-btn">
          <div class="header-language">
            <span>TH <img src="@/assets/down.svg" alt="" /></span>
          </div>
        </div>
        <div class="hearder-btn-block block-language-btn" v-if="user" @click="openDrawer()">
          <div class="header-language">
            <span class="header-span-icon"><img src="@/assets/menu.svg" alt="" /></span>
          </div>
        </div>
      
    </div>
  </div>
  <div class="side-drawer-mask" v-if="showDrawer" @click="closeDrawer()">
  </div>
  <div class="side-drawer" ref="sideDrawer" v-bind:style="{width: showDrawer ? '320px' : '0px'}">
      <div class="side-menu-content-box">
        <h2 class="heading">ยินดีต้อนรับ</h2>
        <div v-if="user">
          <h3  class="name-user">{{ user.firstname }} {{ user.lastname }}</h3>
          <p>Birth Day : {{ user.birth_day }}</p>
          <p>gender : {{ user.gender }}</p>
          <p>email : {{ user.email }}</p>
          <p>Tel. : {{ user.phone }}</p>
        </div>
        <div v-else>
          <div class="side-btn-field">
            <div>
              <a class="side-menu-btn" @click="gotoRegister()"><span>สมัครสมาชิก</span></a>
            </div>
            <div style="margin-top: 20px;">
              <a class="side-menu-btn side-menu-btn-primary" @click="gotoLogin()"><span>เข้าสู่ระบบ</span></a>
            </div>
          </div>
        </div>
      </div>
      <div class="logout-field" @click="logout()" v-if="user">
        <a class="logout-btn"><span style="margin-right: 10px;"><img src="@/assets/logout.svg" style="margin-top: 8px;"></span>ออกจากระบบ</a>
      </div>
    </div> 
  
  
  <!-- <v-navigation-drawer v-model="drawer" right app ></v-navigation-drawer> -->
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';

export default {
  name: "header-menu",
  computed: {
    ...mapGetters(['user'])
  },
  data() {
    return {
      showDrawer : false,
      loadscreen: false,
    }
  },
  methods: {
    closeDrawer() {
      if(this.showDrawer){
        this.showDrawer = false
      }
    },
    openDrawer(){
      this.showDrawer = true
    },
    logout(){
      this.loadscreen = true;
      axios.delete(`${process.env.VUE_APP_API_URL}/logout`,{ headers: { Authorization: localStorage.getItem('token') } })
            .then().catch().finally(()=>{
              localStorage.removeItem('token');
              localStorage.removeItem('refresh_token');
              // this.$router.push('/login');
              this.$router
              .push({ path: '/login' })
              .then(() => { this.$router.go() })
            })
    },
    gotoLogin(){
      this.$router
              .push({ path: '/login' })
              .then(() => { this.$router.go() })
    },
    gotoRegister(){
      this.$router
              .push({ path: '/register' })
              .then(() => { this.$router.go() })
    }
  },
};
</script>

<style></style>
