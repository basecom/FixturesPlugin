import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Shopware 6 Fixture Plugin",
  description: "The fixture plugin is really helpful if you want to create some static demo data",
  base: "/fixturesplugin",
  lastUpdated: true,
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Getting started', link: '/getting-started' },
    ],

    sidebar: [
      {
        text: 'Basics',
        items: [
          { text: 'Getting started', link: '/getting-started' },
          { text: 'Installation', link: '/installation' },
          { text: 'Support matrix', link: '/support-matrix' },
          { text: 'Changelog', link: '/changelog' },
          { text: 'UPGRADE guide', link: '/upgrade' },
        ]
      },
      {
        text: 'Writing fixtures',
        items: [
          { text: 'Your first fixture', link: '/writing/first-fixture' },
          { text: 'Dependencies & Prioritization', link: '/writing/dependencies-prioritization' },
          { text: 'Fixture Helper', link: '/writing/fixture-helper' },
          { text: 'PHPUnit & Tests', link: '/writing/phpunit-tests' },
        ]
      },
      {
        text: 'Fixture Helpers',
        items: [
          { text: 'Utility methods', link: '/helpers/utility' },
          { text: 'Media Helpers', link: '/helpers/media' },
          { text: 'Category Helpers', link: '/helpers/category' },
          { text: 'Sales Channel Helpers', link: '/helpers/sales-channel' },
          { text: 'Salutation Helpers', link: '/helpers/salutation' },
          { text: 'CMS Helpers', link: '/helpers/cms' },
          { text: 'Payment Method Helpers', link: '/helpers/payment-method' },
          { text: 'Shipping Method Helpers', link: '/helpers/shipping-method' },
          { text: 'Language & Locale Helpers', link: '/helpers/language-locale' },
          { text: 'Currency Helpers', link: '/helpers/currency' },
          { text: 'Tax Helpers', link: '/helpers/tax' },
          { text: 'Database Helpers', link: '/helpers/database' },
        ]
      },
      {
        text: 'Contributing',
        items: [
          { text: 'Internals', link: '/contributing/internals' },
          { text: 'Contribution guide', link: '/contributing/guide' },
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/vuejs/vitepress' }
    ]
  }
})
