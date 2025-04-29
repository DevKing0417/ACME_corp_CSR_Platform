import PaymentService from './PaymentService';
import StripePaymentService from './providers/StripePaymentService';
import PayPalPaymentService from './providers/PayPalPaymentService';
import SquarePaymentService from './providers/SquarePaymentService';

class PaymentProviderFactory {
  static providers = {
    stripe: StripePaymentService,
    paypal: PayPalPaymentService,
    square: SquarePaymentService
  };

  static createProvider(providerName, config) {
    const ProviderClass = this.providers[providerName.toLowerCase()];
    
    if (!ProviderClass) {
      throw new Error(`Payment provider '${providerName}' is not supported`);
    }

    return new ProviderClass(config);
  }

  static getAvailableProviders() {
    return Object.keys(this.providers);
  }
}

export default PaymentProviderFactory; 