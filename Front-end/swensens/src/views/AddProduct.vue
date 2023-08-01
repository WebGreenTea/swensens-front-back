<template>
    <loading-screen :loadscreen="loadscreen"></loading-screen>
  <div class="container">
    <router-link to="/products"><button>Back to list of Products Page</button></router-link>
    <form style="margin-top: 50px;" v-on:submit.prevent="addProduct()">
        <div style="display: flex; justify-content: center;">
            <h2>Add New Product</h2>
        </div>
        <div style="display: flex; justify-content: center;">
            <label for="title" style="margin-right: 4px;">Product Name</label>
            <input type="text" required id="title" v-model="title">
        </div>
        <div style="display: flex; justify-content: center;">
            <label for="price" style="margin-right: 4px;">Price</label>
            <input type="number" min="0" step=".01" required id="price" v-model="price">
        </div>
        <div style="display: flex; justify-content: center;">
            <label for="type" style="margin-right: 4px;">Type of Product</label>
            <select id="type" v-model="type_id" required>
                <option v-for="type_ in types" :key="type_.type_product_id" :value="type_.type_product_id">{{ type_.title }}</option>
            </select>
        </div>
        <div style="display: flex; justify-content: center;">
            <button type="submit">Add</button>
        </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
import LoadScreen from '../components/Loading.vue'

export default {
    name:'AddProduct',
    components: {
        "loading-screen": LoadScreen,
        },
    data() {
        return {
            types:null,
            loadscreen:false,

            title:"",
            price:"",
            type_id:""
        }
    },
    methods: {
        addProduct(){
            this.loadscreen = true;
            axios.post(`${process.env.VUE_APP_API_URL}/product/add`,{
                title:this.title,
                price:this.price,
                type_product_id:this.type_id
            }).then(()=>{
                this.$router
              .push({ path: '/products' })
              .then(() => { this.$router.go() })
            }).catch(()=>{
                alert('Something went wrong')
                this.$router
              .push({ path: '/products' })
              .then(() => { this.$router.go() })
            }).finally(()=>{
                this.loadscreen = false;
            });
        }
    },
    created() {
        this.loadscreen = true;
        axios.get(`${process.env.VUE_APP_API_URL}/product/type/all`).then((res)=>{
            if(res.data.data.length > 0){
                this.types = res.data.data;
            }
        }).catch(()=>{
            alert('Something went wrong')
            this.$router
              .push({ path: '/products' })
              .then(() => { this.$router.go() })
        }).finally(()=>{
            this.loadscreen = false;
        });
    },
}
</script>

<style>

</style>