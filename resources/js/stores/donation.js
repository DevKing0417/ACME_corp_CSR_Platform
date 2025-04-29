import { defineStore } from 'pinia';
import axios from 'axios';

export const useDonationStore = defineStore('donation', {
  state: () => ({
    donations: [],
    loading: false,
    error: null,
  }),

  actions: {
    async donateToCampaign({ campaignId, amount, message }) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.post('/api/donations', {
          campaign_id: campaignId,
          amount,
          message
        });
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to process donation';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchDonations(params = {}) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/donations', { params });
        this.donations = response.data.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch donations';
        throw error;
      } finally {
        this.loading = false;
      }
    }
  }
}); 