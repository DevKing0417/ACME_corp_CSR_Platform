<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <ChartBarIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Campaigns</dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900">{{ stats.totalCampaigns }}</div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <CurrencyDollarIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Donations</dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900">${{ stats.totalAmount }}</div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <ClockIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Active Campaigns</dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900">{{ stats.activeCampaigns }}</div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <CheckCircleIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Completed Campaigns</dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900">{{ stats.completedCampaigns }}</div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Settings Section -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Application Settings</h3>
        <form @submit.prevent="handleSettingsSubmit" class="mt-5 space-y-6">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label for="minDonationAmount" class="block text-sm font-medium text-gray-700">Minimum Donation Amount</label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input
                  type="number"
                  id="minDonationAmount"
                  v-model="form.minDonationAmount"
                  min="1"
                  class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>
            </div>

            <div>
              <label for="maxDonationAmount" class="block text-sm font-medium text-gray-700">Maximum Donation Amount</label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input
                  type="number"
                  id="maxDonationAmount"
                  v-model="form.maxDonationAmount"
                  min="1"
                  class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>
            </div>

            <div>
              <label for="campaignDuration" class="block text-sm font-medium text-gray-700">Default Campaign Duration (days)</label>
              <input
                type="number"
                id="campaignDuration"
                v-model="form.campaignDuration"
                min="1"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Allowed Categories</label>
            <div class="mt-2 space-y-2">
              <div v-for="category in form.allowedCategories" :key="category" class="flex items-center">
                <input
                  type="text"
                  v-model="category"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
                <button
                  type="button"
                  @click="removeCategory(category)"
                  class="ml-2 text-red-600 hover:text-red-900"
                >
                  Remove
                </button>
              </div>
              <button
                type="button"
                @click="addCategory"
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Add Category
              </button>
            </div>
          </div>

          <!-- Payment Provider Settings -->
          <div>
            <h4 class="text-md font-medium text-gray-900 mb-4">Payment Provider Settings</h4>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="paymentProvider" class="block text-sm font-medium text-gray-700">Payment Provider</label>
                <select
                  id="paymentProvider"
                  v-model="form.paymentProvider"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                  <option v-for="provider in availableProviders" :key="provider" :value="provider">
                    {{ provider.charAt(0).toUpperCase() + provider.slice(1) }}
                  </option>
                </select>
              </div>

              <div>
                <label for="paymentEnvironment" class="block text-sm font-medium text-gray-700">Environment</label>
                <select
                  id="paymentEnvironment"
                  v-model="form.paymentEnvironment"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                  <option value="test">Test</option>
                  <option value="production">Production</option>
                </select>
              </div>

              <div>
                <label for="paymentApiKey" class="block text-sm font-medium text-gray-700">API Key</label>
                <input
                  type="password"
                  id="paymentApiKey"
                  v-model="form.paymentApiKey"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  placeholder="Enter API key"
                />
              </div>
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="loading"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              {{ loading ? 'Saving...' : 'Save Settings' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAdminStore } from '@/stores/admin';
import { usePaymentStore } from '@/stores/payment';
import PaymentProviderFactory from '@/services/payment/PaymentProviderFactory';
import {
  ChartBarIcon,
  CurrencyDollarIcon,
  ClockIcon,
  CheckCircleIcon
} from '@heroicons/vue/24/outline';

const adminStore = useAdminStore();
const paymentStore = usePaymentStore();
const loading = ref(false);

const availableProviders = PaymentProviderFactory.getAvailableProviders();

const form = ref({
  minDonationAmount: adminStore.settings.minDonationAmount,
  maxDonationAmount: adminStore.settings.maxDonationAmount,
  campaignDuration: adminStore.settings.campaignDuration,
  allowedCategories: [...adminStore.settings.allowedCategories],
  paymentProvider: paymentStore.config.provider,
  paymentEnvironment: paymentStore.config.environment,
  paymentApiKey: paymentStore.config.apiKey
});

onMounted(async () => {
  await adminStore.fetchStats();
  await adminStore.fetchSettings();
});

const addCategory = () => {
  form.value.allowedCategories.push('');
};

const removeCategory = (category) => {
  const index = form.value.allowedCategories.indexOf(category);
  if (index > -1) {
    form.value.allowedCategories.splice(index, 1);
  }
};

const handleSettingsSubmit = async () => {
  try {
    loading.value = true;
    
    // Update payment provider settings
    paymentStore.setProvider(form.value.paymentProvider);
    paymentStore.config.environment = form.value.paymentEnvironment;
    paymentStore.config.apiKey = form.value.paymentApiKey;
    
    // Update other settings
    await adminStore.updateSettings({
      minDonationAmount: form.value.minDonationAmount,
      maxDonationAmount: form.value.maxDonationAmount,
      campaignDuration: form.value.campaignDuration,
      allowedCategories: form.value.allowedCategories
    });
  } catch (error) {
    console.error('Failed to update settings:', error);
  } finally {
    loading.value = false;
  }
};
</script> 