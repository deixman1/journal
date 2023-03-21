import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Enroll from '../views/Enroll.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/enroll',
      name: 'enroll',
      component: Enroll
    }
  ]
})

export default router
