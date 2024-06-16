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
          { text: 'Supported versions', link: '/supported-versions' },
          { text: 'UPGRADE guide', link: '/upgrade' },
          { text: 'Changelog', link: 'https://github.com/basecom/FixturesPlugin/blob/main/CHANGELOG.md' },
        ]
      },
      {
        text: 'Writing fixtures',
        items: [
          { text: 'Your first fixture', link: '/writing/first-fixture' },
          { text: 'Dependencies & Prioritization', link: '/writing/dependencies-prioritization' },
          { text: 'Grouping', link: '/writing/groups' },
          { text: 'Available commands', link: '/writing/available-commands' },
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
        text: 'Examples',
        items: [
          { text: 'Overview', link: '/examples/' },
          { text: 'Create a customer', link: '/examples/customer' },
          { text: 'Create a product', link: '/examples/product' },
          { text: 'Create an e-mail template type', link: '/examples/email-template' },
        ],
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
      { icon: 'github', link: 'https://github.com/basecom/FixturesPlugin' },
      { icon: 'instagram', link: 'https://www.instagram.com/basecom.de/?hl=en' },
      { icon: 'linkedin', link: 'https://www.linkedin.com/company/basecom-gmbh-&-co.-kg/' },
    ],

    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright © 2021-2024 basecom GmbH & Co. KG'
    },

    editLink: {
      pattern: 'https://github.com/basecom/FixturesPlugin/edit/main/docs/:path',
      text: 'Edit this page on GitHub'
    },

    search: {
      provider: 'local'
    }
  },
  markdown: {
    lineNumbers: true
  }
})
