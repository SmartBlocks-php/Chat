define([
    'jquery',
    'underscore',
    'backbone',
    'text!../Templates/chat_viewer.html'
], function ($, _, Backbone, chat_viewer_tpl) {
    var View = Backbone.View.extend({
        tagName:"div",
        className:"chat_viewer",
        initialize:function () {
            var base = this;
        },
        init:function (app) {
            var base = this;
            base.app = app;
            base.render();

            var allcontacts = SmartBlocks.Blocks.UserManagement.Data.contacts.models;
            console.log("allcontacts", allcontacts);
            var contacts = [];
            for (var i = 0; i < allcontacts.length; i++) {
                contacts.push(allcontacts[i].get("real_contact"));
            }
            console.log("contacts", contacts);
            var messages = SmartBlocks.Blocks.Chat.Data.messages.models;
            for (var i = 0; i < messages.length; i++) {
                base.displayMessage(messages[i]);
            }

            base.registerEvents();
        },
        displayMessage:function (message) {
            var base = this;
            base.$el.find("#chatbox").append("<div>" + message.get("owner").name + " : </br>" + message.get("content") + "</div >");
        },
        sendMessage:function (id, text) {
            var base = this;
            var user = SmartBlocks.Blocks.Kernel.Data.users.get(id);
            var session_id = user.get('session_id');
            SmartBlocks.sendWs(session_id, {
                block:"chat",
                message:text,
                time:new Date()
            });
        },
        broadcastMessage:function (message) {
            var base = this;
            SmartBlocks.broadcastWs({
                block:"chat",
                message:message,
                time:new Date()
            });
        },
        createMessage:function (text) {
            var base = this;
            var message = new SmartBlocks.Blocks.Chat.Models.Message();
            message.save({
                content:text
            }, {
                success:function () {
                    base.broadcastMessage(message);
                },
                error:function () {
                    console.log("error saving message");
                }
            });
            SmartBlocks.Blocks.Chat.Data.messages.add(message);
        },
        render:function () {
            var base = this;
            var template = _.template(chat_viewer_tpl, {});
            base.$el.html(template);
        },
        registerEvents:function () {
            var base = this;

            SmartBlocks.events.on("broadcastWs", function (data) {
                base.displayMessage(data.message);
            });

            SmartBlocks.events.on("ws_notification", function (data) {
                if (data.block == "chat") {
                    base.displayMessage(data.content);
                }
            });

            base.$el.find("#sendButton").click(function () {
                var username = base.$el.find('#name_input').val();
                var text = base.$el.find('#textbox').val();
                base.createMessage(text);
            });
        }
    });

    return View;
});