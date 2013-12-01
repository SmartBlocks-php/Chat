define([
    'underscore',
    'backbone'
], function (_, Backbone) {
    var Model = Backbone.Model.extend({
        urlRoot:"/Chat/Message",
        defaults:{
        },
        parse:function (response) {
            return response;
        }
    });

    return Model;
});