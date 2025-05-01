<template>
  <div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Campaign Approvals</h3>

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

      <div v-else class="space-y-4">
        <div v-for="campaign in pendingCampaigns" :key="campaign.id" class="border rounded-lg p-4">
          <div class="flex justify-between items-start">
            <div>
              <h4 class="text-lg font-medium text-gray-900">{{ campaign.title }}</h4>
              <p class="text-sm text-gray-500">Created by {{ campaign.user.name }}</p>
            </div>
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
              Pending
            </span>
          </div>

          <div class="mt-4">
            <p class="text-sm text-gray-700">{{ campaign.description }}</p>
            <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">Target Amount:</span>
                <span class="ml-2 text-gray-900">${{ campaign.target_amount }}</span>
              </div>
              <div>
                <span class="text-gray-500">Category:</span>
                <span class="ml-2 text-gray-900">{{ campaign.category }}</span>
              </div>
              <div>
                <span class="text-gray-500">Start Date:</span>
                <span class="ml-2 text-gray-900">{{ formatDate(campaign.start_date) }}</span>
              </div>
              <div>
                <span class="text-gray-500">End Date:</span>
                <span class="ml-2 text-gray-900">{{ formatDate(campaign.end_date) }}</span>
              </div>
            </div>
          </div>

          <div class="mt-4 flex justify-end space-x-3">
            <button
              @click="rejectCampaign(campaign)"
              class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
              Reject
            </button>
            <button
              @click="approveCampaign(campaign)"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              Approve
            </button>
          </div>
        </div>

        <div v-if="pendingCampaigns.length === 0" class="text-center py-4">
          <p class="text-gray-500">No pending campaigns to review</p>
        </div>
      </div>
    </div>

    <!-- Rejection Modal -->
    <div v-if="showRejectionModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Campaign</h3>
        <div class="space-y-4">
          <div>
            <label for="rejectionReason" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
            <textarea
              id="rejectionReason"
              v-model="rejectionReason"
              rows="4"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
              required
            ></textarea>
          </div>
          <div class="flex justify-end space-x-3">
            <button
              @click="closeRejectionModal"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </button>
            <button
              @click="confirmRejection"
              :disabled="!rejectionReason"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
            >
              Confirm Rejection
            </button>
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

const campaignStore = useCampaignStore();
const loading = ref(false);
const error = ref(null);
const showRejectionModal = ref(false);
const rejectionReason = ref('');
const selectedCampaign = ref(null);

const pendingCampaigns = computed(() => {
  return campaignStore.campaigns.filter(campaign => campaign.status === 'pending');
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const approveCampaign = async (campaign) => {
  try {
    loading.value = true;
    await campaignStore.approveCampaign(campaign.id);
    await campaignStore.fetchCampaigns();
  } catch (error) {
    console.error('Failed to approve campaign:', error);
  } finally {
    loading.value = false;
  }
};

const rejectCampaign = (campaign) => {
  selectedCampaign.value = campaign;
  showRejectionModal.value = true;
};

const confirmRejection = async () => {
  if (!selectedCampaign.value || !rejectionReason.value) return;

  try {
    loading.value = true;
    await campaignStore.rejectCampaign(selectedCampaign.value.id, rejectionReason.value);
    await campaignStore.fetchCampaigns();
    closeRejectionModal();
  } catch (error) {
    console.error('Failed to reject campaign:', error);
  } finally {
    loading.value = false;
  }
};

const closeRejectionModal = () => {
  showRejectionModal.value = false;
  rejectionReason.value = '';
  selectedCampaign.value = null;
};

onMounted(async () => {
  try {
    loading.value = true;
    await campaignStore.fetchCampaigns();
  } catch (error) {
    console.error('Failed to fetch campaigns:', error);
  } finally {
    loading.value = false;
  }
});
</script> 