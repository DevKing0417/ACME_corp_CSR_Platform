<template>
  <div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
      <h3 class="text-lg font-medium text-gray-900">Recent User Activity</h3>
      <div class="mt-5">
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

        <div v-else class="flow-root">
          <ul class="-mb-8">
            <li v-for="(activity, index) in activities" :key="activity.id">
              <div class="relative pb-8">
                <span
                  v-if="index !== activities.length - 1"
                  class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                  aria-hidden="true"
                />
                <div class="relative flex space-x-3">
                  <div>
                    <span
                      :class="[
                        activity.type === 'donation' ? 'bg-green-500' : 'bg-blue-500',
                        'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white'
                      ]"
                    >
                      <template v-if="activity.type === 'donation'">
                        <CurrencyDollarIcon class="h-5 w-5 text-white" aria-hidden="true" />
                      </template>
                      <template v-else>
                        <UserIcon class="h-5 w-5 text-white" aria-hidden="true" />
                      </template>
                    </span>
                  </div>
                  <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                    <div>
                      <p class="text-sm text-gray-500">
                        {{ activity.description }}
                        <span class="font-medium text-gray-900">{{ activity.user_name }}</span>
                      </p>
                    </div>
                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                      <time :datetime="activity.created_at">{{ formatDate(activity.created_at) }}</time>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAdminStore } from '@/stores/admin';
import { CurrencyDollarIcon, UserIcon, XCircleIcon } from '@heroicons/vue/24/outline';

const adminStore = useAdminStore();
const activities = ref([]);
const loading = ref(false);
const error = ref(null);

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    month: 'short',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric'
  }).format(date);
};

onMounted(async () => {
  try {
    loading.value = true;
    const response = await adminStore.fetchUserActivity();
    activities.value = response.data;
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
});
</script> 