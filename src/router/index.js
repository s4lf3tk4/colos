import { createRouter, createWebHistory } from 'vue-router'
import AuthCard from '../views/AuthCard.vue'

const routes = [
  {
    path: '/authcard',
    name: 'AuthCard',
     component: () => import('../views/AuthCard.vue'),
      meta: {
       transition: 'slide',
      transitionDelay: 100
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/RegisterPage.vue'),
     meta: {
       transition: 'slide',
      transitionDelay: 100
    }
  },
  {
    path: '/workpage',
    name: 'WorkPage',
    component: () => import('../views/WorkPage.vue'),
     meta: {
       transition: 'slide',
      transitionDelay: 100
    }
  },
    {
    path: '/mainpage',
    name: 'MainPage',
     component: () => import('../views/MainPage.vue'),
      meta: {
       transition: 'slide',
      transitionDelay: 100
    }
  },
  {
    path: '/errorpage',
    name: 'ErrorPage',
    component: () => import('../views/ErrorPage.vue'),
     meta: {
       transition: 'slide',
      transitionDelay: 100
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router