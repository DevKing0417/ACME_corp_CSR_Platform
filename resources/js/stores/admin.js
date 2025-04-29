import { defineStore } from 'pinia';
import axios from 'axios';

export const useAdminStore = defineStore('admin', {
  state: () => ({
    stats: {
      totalCampaigns: 0,
      totalDonations: 0,
      totalAmount: 0,
      activeCampaigns: 0,
      completedCampaigns: 0
    },
    settings: {
      minDonationAmount: 1,
      maxDonationAmount: 10000,
      campaignDuration: 30,
      allowedCategories: ['Education', 'Environment', 'Health', 'Community', 'Other']
    },
    loading: false,
    error: null
  }),

  actions: {
    async fetchStats() {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/admin/stats');
        this.stats = response.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch admin stats';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchSettings() {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/admin/settings');
        this.settings = response.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch settings';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateSettings(settings) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.put('/api/admin/settings', settings);
        this.settings = response.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update settings';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchUserActivity() {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/admin/user-activity');
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch user activity';
        throw error;
      } finally {
        this.loading = false;
      }
    }
  }
}); 