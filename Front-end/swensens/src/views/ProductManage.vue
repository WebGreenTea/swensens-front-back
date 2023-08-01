<template>
    <loading-screen :loadscreen="loadscreen"></loading-screen>
  <div class="container">
    <table v-if="products">
        <tr>
            <!-- <router-link to="/products/add"><button style="margin-left: 2px;">Add Product</button></router-link> -->
            <th>Product Name </th>
            <th>Price</th>
            <th><div>Product Type</div><router-link to="/products/types"><button style="margin-left: 2px;">Manage type of product</button></router-link></th>
            <th><router-link to="/products/add"><button style="margin-right: 2px;">Add Product</button></router-link></th>
        </tr>
        <tr v-for="product in products" :key="product.product_id">
            <td>{{ product.title}}</td>
            <td>{{ product.price}}</td>
            <td>{{ product.product_type}}</td>
            
            <td><router-link :to="{ name: 'edit_products', params: { id: product.product_id }}"><button>Edit</button></router-link>
                <button @click="delProduct(product.title,product.product_id)">Delete</button></td>
        </tr>
    </table>
    <div v-else style="padding: 10%; display: flex; justify-content: center;">
        <h2>There are currently no products listed.</h2>
        <router-link to="/products/add"><button style="margin-left: 4px;">Add Product</button></router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import LoadScreen from '../components/Loading.vue'

export default {
    name: "ProductManage",
    components: {
        "loading-screen": LoadScreen,
    },
    data() {
        return {
            products:null,
            loadscreen: false,
        }
    },
    methods: {
        delProduct(title,id){
            let proceed = confirm(`Are you sure you want to delete product "${title}" `);
            if(proceed){
                this.loadscreen = true;
                axios.delete(`${process.env.VUE_APP_API_URL}/product/del/${id}`).then((res)=>{
                    if(res.data.success && res.data.code === "OK"){
                        this.$router.go()	
                    }
                }).catch(()=>{
                    alert('Something went wrong')
                }).finally(()=>{
                    this.loadscreen = false;
                });
            }
            
        }
    },
    created() {
        this.loadscreen = true;
        axios.get(`${process.env.VUE_APP_API_URL}/product`).then((res)=>{
            if(res.data.data.length > 0){
                this.products = res.data.data;
                console.log(res.data.data)
            }
            
        }).catch(()=>{
            alert('Something went wrong')
        }).finally(()=>{
            this.loadscreen = false;
        });
    },
}
</script>

<style>

</style>