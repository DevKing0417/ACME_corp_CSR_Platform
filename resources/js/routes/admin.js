import Dashboard from '@/components/admin/Dashboard.vue';
import UserActivity from '@/components/admin/UserActivity.vue';

export default [
  {
    path: '/admin',
    name: 'admin.dashboard',
    component: Dashboard,
    meta: { 
      requiresAuth: true,
      requiresAdmin: true 
    }
  },
  {
    path: '/admin/activity',
    name: 'admin.activity',
    component: UserActivity,
    meta: { 
      requiresAuth: true,
      requiresAdmin: true 
    }
  }
]; 