import Vue from 'vue'
import VueRouter from 'vue-router'
import store from "../store";

Vue.use(VueRouter);

const routes = [
  {
    path: '/paises',
    component: () => import('../components/paises/container'),
    children: [
      {
        path: '',
        component: () => import('../components/paises/Tabla'),
        name: 'Pac'
      },
      {
        path: 'contactosDirectos/:attention_id',
        component: () => import('../components/paises/DirectContacts'),
        name: 'DC',
        beforeEnter: (to, from ,next) => {
          if (store.getters["atenciones/getAttentionId"]) next()
          else next('/paciente')
        }
      },
    ]
  },
  {
    path: '/atenciones',
    component: () => import('../components/atenciones_admin/container'),
    beforeEnter: (to, from, next) => {
      if (store.getters["currentUser/isAdmin"]) next()
      else next('/paciente')
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../components/LoginForm')
  },
  {
    path: '/planilla',
    component: () => import('../components/planilla/container'),
    children: [
      {
        path: '',
        component: () => import('../components/planilla/Tabla'),
        name: 'Planilla'
      }
    ]
  },
  {
    path: '/equipos',
    component: () => import('../components/equipos/container'),
    children: [
      {
        path: '',
        component: () => import('../components/equipos/Tabla'),
        name: 'Seguimientos'
      }
    ]
  },
  {
    path: '/otros',
    component: () => import('../components/otros/container'),
    children: [
      {
        path: '',
        component: () => import('../components/otros/Tabla'),
        name: 'Seguimientos'
      }
    ]
  },
  {
    path: '/presupuestos',
    component: () => import('../components/presupuestos/container'),
    children: [
      {
        path: '',
        component: () => import('../components/presupuestos/Tabla'),
        name: 'Seguimientos'
      },
      {
        path: 'bitacora/:tracing_id',
        name: 'Bitacora',
        component: () => import ('../components/presupuestos/Bitacora'),
      },
    ]
  }
]

const router = new VueRouter({
  routes
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'Login' && !window.localStorage.hasOwnProperty('user')) next ('/login')
  else next ()
})

export default router