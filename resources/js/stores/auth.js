import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
  },

  actions: {
    async login(credentials) {
      try {
        const response = await axios.post('/api/auth/login', credentials);
        const { user, token } = response.data;

        this.user = user;
        this.token = token;
        localStorage.setItem('token', token);

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        return user;
      } catch (error) {
        throw error.response.data;
      }
    },

    async register(userData) {
      try {
        const response = await axios.post('/api/auth/register', userData);
        const { user, token } = response.data;

        this.user = user;
        this.token = token;
        localStorage.setItem('token', token);

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        return user;
      } catch (error) {
        throw error.response.data;
      }
    },

    async logout() {
      try {
        await axios.post('/api/auth/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        this.user = null;
        this.token = null;
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
      }
    },

    async fetchUser() {
      try {
        const response = await axios.get('/api/auth/user');
        this.user = response.data.user;
        return this.user;
      } catch (error) {
        this.logout();
        throw error;
      }
    },

    initialize() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        this.fetchUser().catch(() => {
          this.logout();
        });
      }
    },
  },
}); 