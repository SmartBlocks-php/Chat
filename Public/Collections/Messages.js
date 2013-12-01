define([
    'jquery',
    'underscore',
    'backbone',
    '../Models/Message'
], function ($, _, Backbone, Message) {
    var Collection = Backbone.Collection.extend({
        model: Message,
        url:"/Chat/Message"
    });

    return Collection;
});