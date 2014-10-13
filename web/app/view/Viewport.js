Ext.define('App.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.tab.Panel',
        'App.router.API',
        'App.store.User'
    ],
    layout: 'fit',
    title: 'Slims',
    items: [
        {
            xtype: 'tabpanel',
            title: '<span id="title">Slims</span>',
            listeners: {
                afterrender: {
                    fn: function() {

                        console.log(Ext.StoreManager.lookup('User'));
                        // after this tabpanel is drawn add a logout button and a wee welcome message with the user's name.
                        var headers = Ext.select('#main div.x-tab-strip-wrap');
                        if(!headers || headers.length === 0)
                            throw new Error();

                        var name = Ext.DomHelper.createDom({
                            tag: 'span',
                            id: 'tabpanel-rderame',
                            html: 'Welcome<?php echo $id ? ", <b>".$id."</b>" : ""; ?>'
                        });
                        headers.appendChild(name);
                        // hyperlink is added to the far-right (see css)
                        var a = Ext.DomHelper.createDom({tag:'a', id:'tabpanel-logout-link', cls: 'slims-tabpanel-link', html: 'Log out' });
                        // this should point to the raven logout url
                        a.setAttribute('href','logout.php');
                        headers.appendChild(a);
                    }
                }
            }
        }
    ]
});
