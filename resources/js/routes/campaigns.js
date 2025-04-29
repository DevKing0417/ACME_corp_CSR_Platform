import CampaignList from '@/components/campaigns/CampaignList.vue';
import CampaignForm from '@/components/campaigns/CampaignForm.vue';
import DonationForm from '@/components/campaigns/DonationForm.vue';

export default [
  {
    path: '/campaigns',
    name: 'campaigns',
    component: CampaignList,
    meta: { requiresAuth: true }
  },
  {
    path: '/campaigns/create',
    name: 'campaigns.create',
    component: CampaignForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/campaigns/:id/edit',
    name: 'campaigns.edit',
    component: CampaignForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/campaigns/:id/donate',
    name: 'campaigns.donate',
    component: DonationForm,
    meta: { requiresAuth: true }
  }
]; 