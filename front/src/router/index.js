import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import DashboardView from '../views/login/DashboardView.vue'
import ProjetoView from '../views/login/ProjetoView.vue'
import isAuthenticated from '@/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      children: [
        {
          path: '/dashboard',
          name: 'dashboard',
          component: DashboardView,
        },
        {
          path: '/projeto',
          name: 'projeto',
          component: ProjetoView,
        },
      ],
      beforeEnter: (to, from, next) => {
        const token = localStorage.getItem("token");
        if (token == null) {
          next("/");
          toastr.error("É preciso fazer login para acessar essa página");
          return;
        }

        isAuthenticated()
          .then((response) => {
            if (response) {
              next();
            } else {
              toastr.error("É preciso fazer login para acessar essa página");
              next("/");
            }
          })
          .catch(() => {
            toastr.error("É preciso fazer login para acessar essa página");
            next("/");
          });
      },

    }

  ],
})

export default router
