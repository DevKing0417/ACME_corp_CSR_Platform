import { createRouter, createWebHistory } from 'vue-router';
import campaignRoutes from '@/routes/campaigns';
import adminRoutes from '@/routes/admin';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // ... existing routes ...
    ...campaignRoutes,
    ...adminRoutes
  ]
});

// Add navigation guard for authentication and admin access
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token');
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  const isAdmin = user.role === 'admin';
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else if (to.meta.requiresAdmin && !isAdmin) {
    next('/');
  } else {
    next();
  }
});

export default router; 