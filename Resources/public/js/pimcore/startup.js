pimcore.registerNS("pimcore.plugin.BasilicomThumbnailBundle");

pimcore.plugin.BasilicomThumbnailBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.BasilicomThumbnailBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("BasilicomThumbnailBundle ready!");
    }
});

var BasilicomThumbnailBundlePlugin = new pimcore.plugin.BasilicomThumbnailBundle();
