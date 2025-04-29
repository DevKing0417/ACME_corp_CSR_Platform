<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-900">Campaigns</h1>
      <router-link
        to="/campaigns/create"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Create Campaign
      </router-link>
    </div>

    <div class="flex space-x-4">
      <div class="flex-1">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search campaigns..."
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          @input="handleSearch"
        />
      </div>
      <select
        v-model="selectedCategory"
        class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        @change="handleFilter"
      >
        <option value="">All Categories</option>
        <option v-for="category in categories" :key="category" :value="category">
          {{ category }}
        </option>
      </select>
    </div>

    <div v-if="loading" class="flex justify-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
    </div>

    <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
      <div class="flex">
        <div class="flex-shrink-0">
          <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">{{ error }}</h3>
        </div>
      </div>
    </div>

    <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="campaign in campaigns"
        :key="campaign.id"
        class="bg-white overflow-hidden shadow rounded-lg"
      >
        <div class="p-5">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">{{ campaign.title }}</h3>
            <span
              :class="[
                'px-2 py-1 text-xs font-medium rounded-full',
                campaign.is_active
                  ? 'bg-green-100 text-green-800'
                  : 'bg-gray-100 text-gray-800',
              ]"
            >
              {{ campaign.status }}
            </span>
          </div>
          <p class="mt-2 text-sm text-gray-500">{{ campaign.description }}</p>
          <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
              <span>Progress</span>
              <span>{{ campaign.progress_percentage }}%</span>
            </div>
            <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
              <div
                class="bg-indigo-600 h-2.5 rounded-full"
                :style="{ width: `${campaign.progress_percentage}%` }"
              ></div>
            </div>
            <div class="mt-2 flex justify-between text-sm text-gray-500">
              <span>Raised: ${{ campaign.current_amount }}</span>
              <span>Target: ${{ campaign.target_amount }}</span>
            </div>
          </div>
          <div class="mt-4">
            <DonationForm :campaign="campaign" />
          </div>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-sm text-gray-500">
              {{ campaign.days_remaining }} days remaining
            </span>
            <div class="flex space-x-2">
              <router-link
                :to="`/campaigns/${campaign.id}/edit`"
                class="text-indigo-600 hover:text-indigo-900"
              >
                Edit
              </router-link>
              <button
                @click="deleteCampaign(campaign.id)"
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useCampaignStore } from '@/stores/campaign';
import { XCircleIcon } from '@heroicons/vue/24/outline';
import DonationForm from './DonationForm.vue';

const campaignStore = useCampaignStore();
const searchQuery = ref('');
const selectedCategory = ref('');
const categories = ref(['Education', 'Environment', 'Health', 'Community', 'Other']);

const { campaigns, loading, error } = campaignStore;

const handleSearch = () => {
  if (searchQuery.value) {
    campaignStore.searchCampaigns(searchQuery.value);
  } else {
    campaignStore.fetchCampaigns();
  }
};

const handleFilter = () => {
  campaignStore.fetchCampaigns({ category: selectedCategory.value });
};

const deleteCampaign = async (id) => {
  if (confirm('Are you sure you want to delete this campaign?')) {
    try {
      await campaignStore.deleteCampaign(id);
    } catch (error) {
      console.error('Failed to delete campaign:', error);
    }
  }
};

onMounted(() => {
  campaignStore.fetchCampaigns();
});
</script> 