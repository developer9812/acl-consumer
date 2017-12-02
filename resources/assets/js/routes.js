require('./bootstrap');
import VueRouter from 'vue-router';
import Main from './components/Main.vue';
import ExerciseComponent from './components/ExerciseComponent.vue';
import Auth from './services/Auth';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      name: 'home',
      path: '/',
      component: Main,
      children: [
        {
          name: 'exercises',
          path: '/exercises',
          component: ExerciseComponent
        }
      ]
    }
  ]
});


router.beforeEach((to, from, next) => {
    Auth.isAuthenticated().then(() => next());
});

export default router;
