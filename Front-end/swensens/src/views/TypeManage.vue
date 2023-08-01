<template>
    <loading-screen :loadscreen="loadscreen"></loading-screen>
    <div class="container">
        <router-link to="/products"><button>Back to list of Products Page</button></router-link>
      <table v-if="types">
          <tr>
              <th>Type<button style="margin-left: 3px;" @click="clickAddtype()">Add type of product</button></th>
              <th></th>
          </tr>
          <tr v-for="type_ in types" :key="type_.type_product_id">
              <td>{{ type_.title}}</td>
              <td style="width: 40%;"><button @click="clickEdittype(type_.title,type_.type_product_id)">Edit</button><button style="margin-left: 4px;" @click="clickDeletetype(type_.title,type_.type_product_id)">Delete</button></td>
              
          </tr>
      </table>
      <div v-else style="padding: 10%; display: flex; justify-content: center;">
          <h2>There are currently no type of products listed.</h2>
          <button style="margin-left: 3px;" @click="clickAddtype()">Add type of product</button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios'
  import LoadScreen from '../components/Loading.vue'

  export default {
      name: "TypeManage",
      components: {
        "loading-screen": LoadScreen,
        },
      data() {
          return {
              types:null,
              loadscreen: false,
          }
      },
      methods: {
          clickAddtype(){
              let type = prompt("Please enter new type of product:");
              if (!(type == null || type == "")) {
                  this.addNewType(type)
              }
          },
          addNewType(typeName){
            this.loadscreen = true;
              axios.post(`${process.env.VUE_APP_API_URL}/product/type/add`,{
                  title:typeName
              }).then(()=>{}).catch((err)=>{
                  console.log(err.response)
                  alert('Something went wrong')
              }).finally(()=>{
                this.loadscreen = false;
                this.$router
              .push({ path: '/products/types' })
              .then(() => { this.$router.go() })
              });
          },

          clickDeletetype(type_title,id){
            let proceed = confirm(`Are you sure you want to delete "${type_title}" ? All products of this type will be deleted. `);
            if(proceed){
                this.delType(id);
            }
          },
          delType(id){
            this.loadscreen = true;
            axios.delete(`${process.env.VUE_APP_API_URL}/product/type/del/${id}`).then(()=>{}).catch((err)=>{
                  console.log(err.response)
                  alert('Something went wrong')
              }).finally(()=>{
                this.loadscreen = false;
                this.$router
              .push({ path: '/products/types' })
              .then(() => { this.$router.go() })
              });
          },

          clickEdittype(oldTitle,id){
            let type = prompt("Please enter new title of this type:",oldTitle);
              if ((!(type == null || type == "")) && type.trim() !== oldTitle) {
                  this.editType(type.trim(),id)
              }
          },
          editType(typeName,id){
            this.loadscreen = true;
            axios.put(`${process.env.VUE_APP_API_URL}/product/type/edit/${id}`,{
                title:typeName
            }).then(()=>{}).catch((err)=>{
                  console.log(err.response)
                  alert('Something went wrong')
              }).finally(()=>{
                this.loadscreen = false;
                this.$router
              .push({ path: '/products/types' })
              .then(() => { this.$router.go() })
              });
          }
      },
      created() {
        this.loadscreen = true;
          axios.get(`${process.env.VUE_APP_API_URL}/product/type/all`).then((res)=>{
              if(res.data.data.length > 0){
                  this.types = res.data.data;
              }
          }).catch((err)=>{
                console.log(err)
              alert('Something went wrong')
          }).finally(()=>{
            this.loadscreen = false;
          });
      },
  }
  </script>
  
  <style>
  
  </style>