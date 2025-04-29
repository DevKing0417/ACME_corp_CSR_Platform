class PaymentService {
  constructor(config) {
    this.config = config;
  }

  async initialize() {
    throw new Error('initialize() must be implemented by the payment provider');
  }

  async createPaymentIntent(amount, currency = 'USD') {
    throw new Error('createPaymentIntent() must be implemented by the payment provider');
  }

  async confirmPayment(paymentId, paymentMethod) {
    throw new Error('confirmPayment() must be implemented by the payment provider');
  }

  async refundPayment(paymentId, amount) {
    throw new Error('refundPayment() must be implemented by the payment provider');
  }

  async getPaymentStatus(paymentId) {
    throw new Error('getPaymentStatus() must be implemented by the payment provider');
  }
}

export default PaymentService; 