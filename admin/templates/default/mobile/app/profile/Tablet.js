Ext.define('Kitchensink.profile.Tablet', {
    extend: 'Kitchensink.profile.Base',

    config: {
        controllers: ['Main'],
        views: ['Main', 'TouchEvents']
    },

    isActive: function() {
        return true;
    },

    launch: function() {
        Ext.create('Kitchensink.view.tablet.Main');

        this.callParent();
    }
});
