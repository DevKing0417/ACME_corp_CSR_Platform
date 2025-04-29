<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div>
      <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
      <input
        type="text"
        id="title"
        v-model="form.title"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        required
      />
    </div>

    <div>
      <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
      <textarea
        id="description"
        v-model="form.description"
        rows="4"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        required
      ></textarea>
    </div>

    <div>
      <label for="target_amount" class="block text-sm font-medium text-gray-700">Target Amount</label>
      <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <span class="text-gray-500 sm:text-sm">$</span>
        </div>
        <input
          type="number"
          id="target_amount"
          v-model="form.target_amount"
          min="1"
          step="0.01"
          class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required
        />
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
        <input
          type="date"
          id="start_date"
          v-model="form.start_date"
          :min="minStartDate"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required
        />
      </div>

      <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
        <input
          type="date"
          id="end_date"
          v-model="form.end_date"
          :min="form.start_date"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required
        />
      </div>
    </div>

    <div>
      <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
      <select
        id="category"
        v-model="form.category"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        required
      >
        <option value="">Select a category</option>
        <option v-for="category in categories" :key="category" :value="category">
          {{ category }}
        </option>
      </select>
    </div>

    <div>
      <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
      <input
        type="url"
        id="image_url"
        v-model="form.image_url"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
      />
    </div>

    <div class="flex justify-end space-x-3">
      <router-link
        to="/campaigns"
        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Cancel
      </router-link>
      <button
        type="submit"
        :disabled="loading"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
      >
        {{ loading ? 'Saving...' : 'Save Campaign' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useCampaignStore } from '@/stores/campaign';

const router = useRouter();
const route = useRoute();
const campaignStore = useCampaignStore();

const categories = ['Education', 'Environment', 'Health', 'Community', 'Other'];

const form = ref({
  title: '',
  description: '',
  target_amount: '',
  start_date: '',
  end_date: '',
  category: '',
  image_url: '',
});

const minStartDate = computed(() => {
  return new Date().toISOString().split('T')[0];
});

const loading = computed(() => campaignStore.loading);

const isEdit = computed(() => route.params.id);

onMounted(async () => {
  if (isEdit.value) {
    const campaign = await campaignStore.fetchCampaign(route.params.id);
    form.value = {
      title: campaign.title,
      description: campaign.description,
      target_amount: campaign.target_amount,
      start_date: campaign.start_date,
      end_date: campaign.end_date,
      category: campaign.category,
      image_url: campaign.image_url || '',
    };
  }
});

const handleSubmit = async () => {
  try {
    if (isEdit.value) {
      await campaignStore.updateCampaign({
        id: route.params.id,
        ...form.value,
      });
    } else {
      await campaignStore.createCampaign(form.value);
    }
    router.push('/campaigns');
  } catch (error) {
    console.error('Failed to save campaign:', error);
  }
};
</script> 