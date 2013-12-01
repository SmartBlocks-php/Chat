define([
    'jquery',
    'underscore',
    'backbone',
    './Apps/ChatViewer/Views/chat_viewer'
], function ($, _, Backbone, ChatViewer) {

    var main = {
        init:function () {
            var base = this;
        },
        launch_viewer:function (app) {
            var base = this;
            var chat_viewer = new ChatViewer();
            SmartBlocks.Methods.render(chat_viewer.$el);
            chat_viewer.init(app);
        }
    };

    return main;
});