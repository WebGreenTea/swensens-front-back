import { createRouter, createWebHistory } from 'vue-router'
// import HomeView from '../views/HomeView.vue'
import LoginPage from '../views/Login.vue'
import RegisterPage from '../views/Register.vue'
import MainPage from '../views/MainPage.vue'
import ProductManage from '../views/ProductManage.vue'
import EditProduct from '../views/EditProduct.vue'
import AddProduct from '../views/AddProduct.vue'
import TypeManage from '../views/TypeManage.vue'

const routes = [
  // {
  //   path: '/',
  //   name: 'home',
  //   component: HomeView
  // },
  // {
  //   path: '/about',
  //   name: 'about',
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  // },
  {
    path: '/login',
    name: 'login',
    component: LoginPage
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterPage
  },
  {
    path: '/th',
    name: 'main',
    component: MainPage
  },
  {
    path: '/products',
    name: 'products',
    component: ProductManage
  },
  {
    path: '/products/edit/:id',
    name: 'edit_products',
    component: EditProduct
  },
  {
    path: '/products/add',
    name: 'add_products',
    component: AddProduct
  },

  {
    path: '/products/types',
    name: 'types',
    component: TypeManage
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
