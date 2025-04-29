import { defineStore } from 'pinia';
import PaymentProviderFactory from '@/services/payment/PaymentProviderFactory';

export const usePaymentStore = defineStore('payment', {
  state: () => ({
    provider: null,
    paymentIntent: null,
    loading: false,
    error: null,
    config: {
      provider: 'stripe', // Default provider, can be changed in admin settings
      apiKey: import.meta.env.VITE_PAYMENT_API_KEY,
      environment: import.meta.env.VITE_PAYMENT_ENVIRONMENT || 'test'
    }
  }),

  actions: {
    async initializeProvider() {
      this.loading = true;
      this.error = null;
      try {
        this.provider = PaymentProviderFactory.createProvider(this.config.provider, this.config);
        await this.provider.initialize();
      } catch (error) {
        this.error = error.message || 'Failed to initialize payment provider';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createPaymentIntent(amount, currency = 'USD') {
      this.loading = true;
      this.error = null;
      try {
        if (!this.provider) {
          await this.initializeProvider();
        }
        this.paymentIntent = await this.provider.createPaymentIntent(amount, currency);
        return this.paymentIntent;
      } catch (error) {
        this.error = error.message || 'Failed to create payment intent';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async confirmPayment(paymentMethod) {
      this.loading = true;
      this.error = null;
      try {
        if (!this.provider || !this.paymentIntent) {
          throw new Error('Payment intent not initialized');
        }
        const result = await this.provider.confirmPayment(this.paymentIntent.id, paymentMethod);
        this.paymentIntent = null; // Clear payment intent after successful confirmation
        return result;
      } catch (error) {
        this.error = error.message || 'Failed to confirm payment';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async refundPayment(paymentId, amount) {
      this.loading = true;
      this.error = null;
      try {
        if (!this.provider) {
          await this.initializeProvider();
        }
        return await this.provider.refundPayment(paymentId, amount);
      } catch (error) {
        this.error = error.message || 'Failed to process refund';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async getPaymentStatus(paymentId) {
      this.loading = true;
      this.error = null;
      try {
        if (!this.provider) {
          await this.initializeProvider();
        }
        return await this.provider.getPaymentStatus(paymentId);
      } catch (error) {
        this.error = error.message || 'Failed to get payment status';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    setProvider(providerName) {
      this.config.provider = providerName;
      this.provider = null; // Reset provider to force reinitialization
    }
  }
}); 