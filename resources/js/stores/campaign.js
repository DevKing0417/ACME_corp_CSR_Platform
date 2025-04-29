import { defineStore } from 'pinia';
import axios from 'axios';

export const useCampaignStore = defineStore('campaign', {
  state: () => ({
    campaigns: [],
    currentCampaign: null,
    loading: false,
    error: null,
  }),

  getters: {
    getCampaignById: (state) => (id) => {
      return state.campaigns.find(campaign => campaign.id === id);
    },
  },

  actions: {
    async fetchCampaigns(params = {}) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/campaigns', { params });
        this.campaigns = response.data.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch campaigns';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchCampaign(id) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/campaigns/${id}`);
        this.currentCampaign = response.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch campaign';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createCampaign(campaignData) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.post('/api/campaigns', campaignData);
        this.campaigns.unshift(response.data);
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to create campaign';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateCampaign({ id, ...campaignData }) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.put(`/api/campaigns/${id}`, campaignData);
        const index = this.campaigns.findIndex(c => c.id === id);
        if (index !== -1) {
          this.campaigns[index] = response.data;
        }
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update campaign';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async deleteCampaign(id) {
      this.loading = true;
      this.error = null;
      try {
        await axios.delete(`/api/campaigns/${id}`);
        this.campaigns = this.campaigns.filter(campaign => campaign.id !== id);
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete campaign';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async searchCampaigns(query) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/campaigns/search', { params: { query } });
        this.campaigns = response.data.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to search campaigns';
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
}); 