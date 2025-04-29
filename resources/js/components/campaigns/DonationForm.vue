<template>
  <div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Make a Donation</h3>
    
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
        <div class="mt-1 relative rounded-md shadow-sm">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">$</span>
          </div>
          <input
            type="number"
            id="amount"
            v-model="form.amount"
            min="1"
            step="0.01"
            class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            required
          />
        </div>
      </div>

      <div>
        <label for="message" class="block text-sm font-medium text-gray-700">Message (Optional)</label>
        <textarea
          id="message"
          v-model="form.message"
          rows="3"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          placeholder="Add a message to your donation..."
        ></textarea>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          :disabled="loading"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
        >
          {{ loading ? 'Processing...' : 'Donate' }}
        </button>
      </div>
    </form>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 max-w-sm w-full">
        <div class="flex items-center justify-center mb-4">
          <div class="rounded-full bg-green-100 p-3">
            <CheckIcon class="h-6 w-6 text-green-600" />
          </div>
        </div>
        <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Donation Successful!</h3>
        <p class="text-sm text-gray-500 text-center mb-4">
          Thank you for your donation of ${{ form.amount }} to {{ campaign.title }}.
        </p>
        <div class="flex justify-center">
          <button
            @click="closeSuccessModal"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useDonationStore } from '@/stores/donation';
import { usePaymentStore } from '@/stores/payment';
import { CheckIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  campaign: {
    type: Object,
    required: true
  }
});

const donationStore = useDonationStore();
const paymentStore = usePaymentStore();
const loading = ref(false);
const showSuccessModal = ref(false);
const showPaymentModal = ref(false);

const form = ref({
  amount: '',
  message: ''
});

const handleSubmit = async () => {
  try {
    loading.value = true;
    
    // Create payment intent
    const paymentIntent = await paymentStore.createPaymentIntent(form.value.amount);
    
    // Show payment modal
    showPaymentModal.value = true;
    
  } catch (error) {
    console.error('Failed to process donation:', error);
  } finally {
    loading.value = false;
  }
};

const handlePaymentComplete = async (paymentMethod) => {
  try {
    loading.value = true;
    
    // Confirm payment
    await paymentStore.confirmPayment(paymentMethod);
    
    // Create donation record
    await donationStore.donateToCampaign({
      campaignId: props.campaign.id,
      amount: form.value.amount,
      message: form.value.message
    });
    
    showSuccessModal.value = true;
    showPaymentModal.value = false;
    form.value = { amount: '', message: '' };
    
  } catch (error) {
    console.error('Failed to complete payment:', error);
  } finally {
    loading.value = false;
  }
};

const closeSuccessModal = () => {
  showSuccessModal.value = false;
};
</script> 