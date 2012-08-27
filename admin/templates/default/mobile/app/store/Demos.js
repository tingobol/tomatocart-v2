(function() {
    var root = {
        id: 'root',
        text: 'TomatoCart',
        cls: 'launchscreen',
        items: [
            {
                text: 'Content',
                id: 'content',
                cls: 'launchscreen',
                items: [
                    {
                        text: 'Articles Categories',
                        leaf: true,
                        id: 'articles-categories'
                    },
                    {
                        text: 'Articles',
                        leaf: true,
                        id: 'articles'
                    },
                    {
                        text: 'Guest Book',
                        leaf: true,
                        id: 'guestbook'
                    }
                ]
            },
            {
                text: 'Configuration',
                id: 'configuration',
                cls: 'launchscreen',
                items: [
                        {
                            text: 'Homepage Info',
                            leaf: true,
                            id: 'homepage'
                        },
                        {
                            text: 'Configuration',
                            leaf: true,
                            id: 'config-items'
                        }
                    ]
            },
            {
                text: 'Catalog',
                id: 'catalog',
                cls: 'launchscreen',
                items: [
                    {
                        text: 'Categories',
                        leaf: true,
                        id: 'categories'
                    },
                    {
                        text: 'Products',
                        leaf: true,
                        id: 'products'
                    },
                    {
                        text: 'Manufacturers',
                        leaf: true,
                        id: 'manufacturers'
                    },
                    {
                        text: 'Specials',
                        leaf: true,
                        id: 'specials'
                    }
                ]
            },
            {
                text: '*Customers',
                id: 'customers-grp',
                cls: 'launchscreen',
                items: [
                    {
                        text: '*Customers',
                        leaf: true,
                        id: 'customers',
                        view: 'Customers'
                    },
                    {
                        text: 'Customers Groups',
                        leaf: true,
                        id: 'customers-groups'
                    },
                    {
                        text: '*Orders',
                        leaf: true,
                        id: 'orders'
                    },
                    {
                        text: '*Invoices',
                        leaf: true,
                        id: 'invoices'
                    }
                ]
            },
            {
                text: 'Definitions',
                id: 'definitions-grp',
                cls: 'launchscreen',
                leaf: true
            },
            {
                text: '*Reports',
                id: 'reports-grp',
                cls: 'launchscreen',
                items: [
                    {
                        text: '*Products Reports',
                        id: 'products-reports-grp',
                        items: [
                            {
                                text: '*Products Purchased',
                                leaf: true,
                                id: 'products-purchased',
                                view: 'ProductsPurchased'
                            },
                            {
                                text: '*Products Viewed',
                                leaf: true,
                                id: 'products-viewed'
                            },
                            {
                                text: '*Categories Purchased',
                                leaf: true,
                                id: 'categories-purchased'
                            }                        
                        ]
                    },
                    {
                        text: '*Orders Reports',
                        id: 'orders-reports-grp',
                        items: [
                            {
                                text: '*Best Orders',
                                leaf: true,
                                id: 'best-orders'
                            },
                            {
                                text: '*Orders Total',
                                leaf: true,
                                id: 'orders-total'
                            }
                        ]
                    }
                ]
            },
            {
                text: 'Templates',
                id: 'templates-grp',
                cls: 'launchscreen',
                leaf: true
            },
            {
                text: 'Logout',
                id: 'logout-grp',
                cls: 'launchscreen',
                leaf: true
            }
        ]
    };

    Ext.define('Kitchensink.store.Demos', {
        alias: 'store.Demos',
        extend: 'Ext.data.TreeStore',
        requires: ['Kitchensink.model.Demo'],

        config: {
            model: 'Kitchensink.model.Demo',
            root: root,
            defaultRootProperty: 'items'
        }
    });
})();
