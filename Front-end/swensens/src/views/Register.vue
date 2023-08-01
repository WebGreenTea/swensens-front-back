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
            <h2 class="box-herder">สร้างบัญชี</h2>
            <p class="box-p">สมัครสมาชิกและเริ่มใช้งาน</p>
          </div>
        </div>
        <div class="box-body">
          <div class="login-form">
            <form v-on:submit.prevent="register()">
              <div class="input-row">
                <div class="input-col-controll">
                  <div style="margin-right: 8px">
                    <div>
                      <div class="form-label">
                        <label title="ชื่อ">ชื่อ</label>
                      </div>
                    </div>
                    <div>
                      <input
                        v-model="firstname"
                        type="text"
                        placeholder="กรอกชื่อ"
                        class="form-input"
                      />
                    </div>
                  </div>
                  <div style="margin-left: 8px">
                    <div>
                      <div class="form-label">
                        <label title="นามสกุล">นามสกุล</label>
                      </div>
                    </div>
                    <div>
                      <input
                        v-model="lastname"
                        type="text"
                        placeholder="กรอกนามสกุล"
                        class="form-input"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="input-row">
                <div>
                  <div class="form-label">
                    <label title="เบอร์โทรศัพท์">เบอร์โทรศัพท์</label>
                  </div>
                </div>
                <div>
                  <input
                    v-model="phone"
                    @input="checkPhoneError()"
                    @blur="checkPhoneError()"
                    type="tel"
                    placeholder="กรอกเบอร์โทรศัพท์"
                    class="form-input"
                    maxlength="10"
                    required
                  />
                </div>
                <span v-if="showPhoneError" class="error-text">{{
                  PhoneErrorContent
                }}</span>
              </div>
              <div class="input-row">
                <div>
                  <div class="form-label">
                    <label title="อีเมล">อีเมล</label>
                  </div>
                </div>
                <div>
                  <input
                    v-model="email"
                    @input="checkEmailError()"
                    @blur="checkEmailError()"
                    type="email"
                    placeholder="กรอกอีเมล"
                    class="form-input"
                  />
                </div>
                <span v-if="showEmailError" class="error-text">{{
                  EmailErrorContent
                }}</span>
              </div>
              <div class="input-row">
                <span class="error-text">
                  กรุณาตรวจสอบอีเมลที่ระบุให้ถูกต้อง
                </span>
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
                    @input="checkPasswordError()"
                    @blur="checkPasswordError()"
                    type="password"
                    placeholder="กรอกรหัสผ่าน"
                    class="form-input"
                  />
                </div>
                <span v-if="showPasswordError" class="error-text">{{
                  PasswordErrorContent
                }}</span>
              </div>
              <div class="input-row">
                <div>
                  <div class="form-label">
                    <label title="เพศ (ไม่ระบุได้)">เพศ (ไม่ระบุได้)</label>
                  </div>
                </div>
                <div class="gender-radio-btn">
                  <input
                    type="radio"
                    name="gender"
                    value="Male"
                    id="gender1"
                    v-model="gender"
                  />
                  <label for="gender1">ชาย</label>

                  <input
                    type="radio"
                    name="gender"
                    value="Female"
                    id="gender2"
                    v-model="gender"
                  />
                  <label for="gender2">หญิง</label>

                  <input
                    type="radio"
                    name="gender"
                    value="Not Specified"
                    id="gender3"
                    v-model="gender"
                    checked="checked"
                  />
                  <label for="gender3">ไม่ระบุ</label>
                </div>
              </div>
              <div class="input-row">
                <div>
                  <div class="form-label">
                    <label title="ของขวัญวันเกิดรอคุณอยู่"
                      >ของขวัญวันเกิดรอคุณอยู่</label
                    >
                  </div>
                </div>
                <div>
                  <input
                    ref="birthdayinput"
                    @click="showPicker()"
                    v-model="birthDate"
                    type="date"
                    placeholder="dd/mm/yyyy"
                    class="form-input"
                  />
                </div>
              </div>
              <div class="input-row">
                <div class="input-col">
                  <div class="round">
                    <input
                      type="checkbox"
                      id="acceptrule"
                      name="acceptrule"
                      v-model="acceptrule"
                    />
                    <label for="acceptrule"></label>
                  </div>
                  <div style="margin-left: 5px">
                    <span>
                      <label for="acceptrule" class="accept-text"
                        >ฉันได้อ่านและยอมรับ
                        <a href="">ข้อกำหนดการใช้งาน</a> และ
                        <a href="">นโยบายความเป็นส่วนตัว</a> ของสเวนเซ่นส์
                      </label>
                    </span>
                  </div>
                </div>
              </div>
              <div class="input-row">
                <div class="input-col">
                  <div class="round">
                    <input
                      type="checkbox"
                      id="acceptnoti"
                      name="acceptnoti"
                      v-model="acceptnoti"
                    />
                    <label for="acceptnoti"></label>
                  </div>
                  <div style="margin-left: 5px">
                    <span>
                      <label for="acceptnoti" class="accept-text">
                        ฉันยินยอมรับข้อมูลข่าวสาร กิจกรรมส่งเสริมการขายต่างๆ
                        จากสเวนเซ่นส์และ<a href="">บริษัทในเครือ</a>
                        โดยเราจะเก็บข้อมูลของท่านไว้เป็นความลับ
                        สามารถศึกษาเงื่อนไขหรือข้อตกลง
                        <a href="">นโยบายความเป็นส่วนตัว</a>
                        เพิ่มเติมได้ที่เว็บไซต์ของบริษัทฯ
                      </label>
                    </span>
                  </div>
                </div>
              </div>
              <div class="input-row">
                <div>
                  <button
                    type="submit"
                    v-bind:disabled="!isReadyRegister()"
                    class="login-form-btn"
                  >
                    สมัครสมาชิก
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
import axios from "axios";
import { mapGetters } from 'vuex';

export default {
  name: "LoginPage",
  computed: {
    ...mapGetters(['user'])
  },
  data() {
    return {
      firstname: "",
      lastname: "",
      phone: "",
      email: "",
      password: "",
      gender: "Not Specified",
      birthDate: "",
      acceptrule: false,
      acceptnoti: false,

      showPhoneError: false,
      showEmailError: false,
      showPasswordError: false,

      PhoneErrorContent: "",
      EmailErrorContent: "",
      PasswordErrorContent: "",

      isAlreadyPhone: false,
      isAlreadyEmail: false,

      loadscreen: false,
    };
  },
  methods: {
    showPicker() {
      this.$refs.birthdayinput.showPicker();
    },
    register() {
      let postData = {
        firstname: this.firstname,
        surname: this.lastname,
        phone: this.phone,
        email: this.email,
        password: this.password,
        gender: this.gender,
        birhday: this.birthDate == "" ? null : this.birthDate + " 00:00:00",
        rule_accept: this.acceptrule,
        noti_accept: this.acceptnoti,
      };
      console.log(postData);
      this.loadscreen = true
      axios
        .post(`${process.env.VUE_APP_API_URL}/register`, postData)
        .then((res) => {
          if (
            res.data.code == "CREATED" &&
            res.data.success
          ) {
            // console.log("token", res.response.data.data.access_token);
            // console.log("refresh_token", res.response.data.data.refresh_token);

            //keep token and to go /th
            localStorage.setItem("token", res.data.data.token.access_token);
            localStorage.setItem(
              "refresh_token",
              res.data.data.token.refresh_token
            );
            this.$store.dispatch('user',res.data.data.user);
            this.$router.push("/th");
          }
        })
        .catch((error) => {
          if (error.response.data.code == "PHONE_ERROR") {
            // console.log(this.loadscreen)
            alert("already phone number");
            this.isAlreadyPhone = true;
          } else if (error.response.data.code == "EMAIL_ERROR") {
            alert("already email");
            this.isAlreadyEmail = true;
          } else if (error.response.data.code == "ERROR") {
            //refresh
            alert("something went wrong");
            window.location.reload();
          }
          // console.log('error',error)
          // console.log('error',error.response.data.code)
        })
        .finally(() => {
          // console.log('finally')
          this.loadscreen = false
        });
    },
    isReadyRegister() {
      if (
        this.firstname.length == 0 ||
        this.lastname.length == 0 ||
        (this.email.length == 0 && !this.isEmail(this.email)) ||
        (this.phone.length == 0 && !this.isPhone(this.phone)) ||
        (this.password.length == 0 && !this.isPassword(this.password)) ||
        !this.acceptrule
      ) {
        return false;
      }
      return true;
    },
    isEmail(emailAdress) {
      let regex = /^\w+([-]?\w+)*@\w+([-]?\w+)*(\.\w{2,3})+$/;
      if (emailAdress.match(regex)) return true;
      else return false;
    },
    isPhone(phoneNumber) {
      let regex = /^0\d{9}$/;
      if (phoneNumber.match(regex)) return true;
      else return false;
    },
    isPassword(password) {
      let regex = /.{8,}/;
      if (password.match(regex)) return true;
      else return false;
    },
    checkPhoneError() {
      if (this.phone.length == 0) {
        this.showPhoneError = true;
        this.PhoneErrorContent = "กรุณากรอกเบอร์โทรศัพท์ 10 หลัก";
      } else {
        if (!this.isPhone(this.phone)) {
          this.showPhoneError = true;
          this.PhoneErrorContent = "กรุณากรอกเบอร์โทรศัพท์ 10 หลัก";
        } else {
          this.showPhoneError = false;
        }
      }
    },
    checkEmailError() {
      if (this.email.length == 0) {
        this.showEmailError = true;
        this.EmailErrorContent = "กรุณากรอกอีเมล";
      } else {
        if (!this.isEmail(this.email)) {
          this.showEmailError = true;
          this.EmailErrorContent = "อีเมลไม่ถูกต้อง";
        } else {
          this.showEmailError = false;
        }
      }
    },
    checkPasswordError() {
      if (this.password.length == 0) {
        this.showPasswordError = true;
        this.PasswordErrorContent = "ตั้งรหัสผ่านอย่างน้อย 8 ตัว";
      } else {
        if (!this.isPassword(this.password)) {
          this.showPasswordError = true;
          this.PasswordErrorContent = "ตั้งรหัสผ่านอย่างน้อย 8 ตัว";
        } else {
          this.showPasswordError = false;
        }
      }
    },
    convertDate(date) {
      console.log(date);
      date.toString();
      let [day, month, year] = date.split("-");
      return `${year}-${month}-${day} 00:00:00`;
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