<template>
    <loading-screen :loadscreen="loadscreen"></loading-screen>
  <div class="container">
    <router-link to="/products"><button>Back to list of Products Page</button></router-link>
    <form style="margin-top: 50px;" v-on:submit.prevent="updateProduct()">
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

            product_id:null,
            title:null,
            price:null,
            type_id:null,

            product_type_old:null,
        }
    },
    methods: {
        updateProduct(){
            this.loadscreen = true;
            axios.put(`${process.env.VUE_APP_API_URL}/product/edit/${this.$route.params.id}`,{
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
        },
        findTypeProductIdByTitle(arr, title) {
            for (let i = 0; i < arr.length; i++) {
                if (arr[i].title === title) {
                    return arr[i].type_product_id;
                }
            }
            return null;
        }
    },
    created() {
        this.loadscreen = true;
        axios.get(`${process.env.VUE_APP_API_URL}/product/${this.$route.params.id}`).then((res)=>{
            // console.log(res.data)
            if(res.data.code === "OK" && res.data.success && !( typeof res.data.data  === 'undefined' || res.data.data === null || res.data.data === [])){
                this.product_id = res.data.product_id;
                this.title = res.data.data.title;
                this.price = res.data.data.price;
                this.product_type_old = res.data.data.product_type;
            }else{
                this.$router.push({ path: '/products' }).then(() => { this.$router.go() })
            }
        }).catch((err)=>{
            if(err.response.data.code == "NOT_FOUND"){
                this.$router.push({ path: '/products' }).then(() => { this.$router.go() })
            }else{
                alert('Something went wrong')
                this.$router.push({ path: '/products' }).then(() => { this.$router.go() })
            }
        }).finally(()=>{
            axios.get(`${process.env.VUE_APP_API_URL}/product/type/all`).then((res)=>{
                if(res.data.data.length > 0){
                    this.types = res.data.data;
                    let oldTypeid = this.findTypeProductIdByTitle(this.types,this.product_type_old);
                    if(!oldTypeid) {
                        alert('Something went wrong')
                        //this.$router.push({ path: '/products' }).then(() => { this.$router.go() })
                    }
                    this.type_id = oldTypeid;
                }
            }).catch(()=>{
                alert('Something went wrong')
                this.$router.push({ path: '/products' }).then(() => { this.$router.go() })
            }).finally(()=>{
                this.loadscreen = false;
            });
        });
    },
}
</script>

<style>

</style>