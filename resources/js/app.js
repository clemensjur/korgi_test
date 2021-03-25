require("./bootstrap");

// Import modules...
import Vue from "vue";
import {
    App as InertiaApp,
    plugin as InertiaPlugin
} from "@inertiajs/inertia-vue";
import PortalVue from "portal-vue";

import Vuex from "vuex";
import axios from "axios";

Vue.mixin({methods: {route}});
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
Vue.use(Vuex);

Vue.config.productionTip = false;

const app = document.getElementById("app");

function generateLaravelTimestamp() {
    var d = new Date();
    var year = d.getFullYear();
    var month = ("0" + (d.getMonth() + 1)).slice(-2);
    var day = ("0" + d.getDate()).slice(-2);
    var hour = ("0" + d.getHours()).slice(-2);
    var minutes = ("0" + d.getMinutes()).slice(-2);
    var seconds = ("0" + d.getSeconds()).slice(-2);
    return (
        year +
        "-" +
        month +
        "-" +
        day +
        " " +
        hour +
        ":" +
        minutes +
        ":" +
        seconds
    );
}

function setLastMessage(groupName) {
    axios.post(route("group.get"), {groupName: groupName}).then(res => {
        axios
            .post(route("group.set"), {
                groupId: res.id,
                last_message: generateLaravelTimestamp()
            })
        //.then(res => console.log(res));
    });
}

function generateHEXColor() {
    return Math.floor(Math.random() * 16777215).toString(16);
}

const store = new Vuex.Store({
    state: {
        pubnub: {},
        user: {
            username: "",
            uuid: "",
            settings: {
                darkmode: false
            }
        },
        groups: {},
        showGroupInfo: false,
        popupMessage: "",
        methods: {
            saveMessagesToLocalStorage: (group, chat, channel) => {
                localStorage.setItem(
                    channel,
                    JSON.stringify(
                        store.getters.getChannel(group, chat).messages
                    )
                );
            }
        }
    },
    mutations: {
        setPopupMessage(state, payload) {
            Vue.set(state, "popupMessage", payload.message);
        },
        setState(state, payload) {
            Vue.set(state, "pubnub", payload.pubnub);
            Vue.set(state, "user", payload.user);
            Vue.set(state, "groups", payload.groups);
        },
        changeGroupColor(state, payload) {
            state.groups[payload.group].color = payload.color;
        },
        setShowArrow(state, payload) {
            Vue.set(state, "showArrow", payload.showArrow);
        },
        setShowGroupInfo(state, payload) {
            Vue.set(state, "showGroupInfo", payload.showGroupInfo);
        },
        setCurrentPage(state, payload) {
            Vue.set(state, "currentPage", payload.page);
        },
        toggleDarkmode(state) {
            state.user.settings.darkmode = !state.user.settings.darkmode;
        },
        // addReadBy(state, payload) {
        //     Vue.set(state.groups[payload.group].channels[payload.chat].messages[payload.messageTimetoken].message.readBy, payload.user.uuid, {
        //         user: payload.user,
        //         time: payload.time
        //     });
        //     //saveMessagesToLocalStorage(payload.group, payload.chat, payload.channel)
        // },
        // Stelle 1
        addPollMessageAction(state, payload) {
            Vue.set(
                state.groups[payload.group].channels[payload.chat].messages[
                    payload.messageTimetoken
                    ].message.results,
                payload.user.uuid,
                payload.answerKey
            );
            store.state.methods.saveMessagesToLocalStorage(
                payload.group,
                payload.chat,
                payload.channel
            );
        },
        addReadConfirmation(state, payload) {
            Vue.set(
                state.groups[payload.group].channels[payload.chat].messages[payload.messageTimetoken].message.readBy,
                payload.user.uuid,
                {
                    user: payload.user,
                    time: payload.time
                }
            );

            store.state.methods.saveMessagesToLocalStorage(
                payload.group,
                payload.chat,
                payload.channel
            );
        },

        // TODO veröffentlichen einer allgemeinen Message Action
        publishMessageAction(state, payload) {
            state.pubnub.addMessageAction({
                channel: payload.message.channel,
                messageTimetoken: payload.message.timetoken,
                action: {
                    type: payload.type,
                    value: JSON.stringify({
                        user: state.user,
                        chat: payload.message.message.chat,
                        group: payload.message.message.group,
                        channel: payload.message.channel,
                        answerKey: payload.answerKey
                    })
                }
            });
        },
        publishReadConfirmation(state, payload) {
            state.pubnub.addMessageAction(
                {
                    channel: payload.message.channel,
                    messageTimetoken: payload.message.timetoken,
                    action: {
                        type: 'readConfirm',
                        value: JSON.stringify({
                            user: state.user,
                            time: new Date().getTime(),
                            chat: payload.message.message.chat,
                            group: payload.message.message.group,
                            channel: payload.message.channel
                        }),
                    }
                },
                function(status, response) {
                    console.log(status, response);
                }
            );
        },
        publishPoll(state, payload) {
            state.pubnub.publish({
                channel: payload.channel,
                message: {
                    subject: payload.subject,
                    user: state.user,
                    group: payload.group,
                    chat: payload.chat,
                    allowMultiple: payload.allowMultiple,
                    answers: payload.answers,
                    results: {},
                    messageType: "poll"
                }
            });
        },
        publishReply(state, payload) {
            state.pubnub.publish({
                channel: payload.channel,
                message: {
                    text: payload.message,
                    user: state.user,
                    group: payload.group,
                    chat: payload.chat,
                    messageTimetoken: payload.messageTimetoken,
                    messageType: "reply"
                }
            });
        },
        publishMessage(state, payload) {
            console.log("App.js: publish Message", payload);
            state.pubnub.publish({
                channel: payload.channel,
                message: {
                    text: payload.message,
                    user: state.user,
                    group: payload.group,
                    chat: payload.chat,
                    messageType: "message"
                }
            });
        },
        publishImportantMessage(state, payload) {
            state.pubnub.publish({
                channel: payload.channel,
                message: {
                    text: payload.message,
                    subject: payload.subject,
                    user: state.user,
                    group: payload.group,
                    chat: payload.chat,
                    readBy: {},
                    messageType: "importantMessage"
                }
            });
        },
        publishFile(state, payload) {
            setLastMessage(payload.group);
            state.pubnub.publish({
                channel: payload.channel,
                message: {
                    text: payload.message,
                    fileName: payload.fileName,
                    fileType: payload.fileType,
                    user: state.user,
                    group: payload.group,
                    chat: payload.chat,
                    messageType: "file"
                }
            });
        },
        publishEventAnnouncement(state, payload) {
            state.pubnub
                .publish({
                    channel: payload.channel,
                    message: {
                        subject: payload.subject,
                        text: payload.text,
                        date: payload.date,
                        user: state.user,
                        group: payload.group,
                        groupId: payload.groupId,
                        chat: payload.chat,
                        messageType: "eventAnnouncement"
                    }
                })
                .then(() => setLastMessage(payload.group))
                .then(() => {
                    axios
                        .post(route("events.store"), {
                            group: payload.group,
                            groupId: payload.groupId,
                            date: (payload.date.getTime()).toString(),
                            name: payload.subject,
                            description: payload.text
                        })
                        .then(res => console.log(res));
                    // .then(res => {
                    //     store.commit("addEvent", {
                    //         subject: payload.subject,
                    //         text: payload.text,
                    //         date: payload.date,
                    //         group: payload.group
                    //     });
                    // });
                });
        },
        publishDateVoting(state, payload) {
            setLastMessage(payload.group);
            state.pubnub.publish({
                channel: payload.channel,
                message: {
                    text: payload.message,
                    options: payload.dates,
                    user: state.user,
                    group: payload.group,
                    chat: payload.chat,
                    messageType: "dateVoting"
                }
            });
        },
        addMessage(state, payload) {
            Vue.set(
                state.groups[payload.message.message.group].channels[
                    payload.message.message.chat
                    ].messages,
                payload.message.timetoken,
                payload.message
            );
            store.state.methods.saveMessagesToLocalStorage(
                payload.message.message.group,
                payload.message.message.chat,
                payload.message.channel
            );
        },
        addEvent(state, payload) {
            let newEvent = {
                subject: payload.subject,
                text: payload.text,
                date: new Date(payload.date)
            };

            state.groups[payload.group].events.push(newEvent);
        },
        addGroup(state, payload) {
            axios
                .post("/gruppen", {
                    name: payload.name,
                    color: "#FFC78E"
                })
                .then(response => {
                    Vue.set(
                        state.groups,
                        response.data[payload.name].url,
                        response.data[payload.name]
                    );
                });
        },
        deleteGroup(state, payload) {
            axios.post(route("group.delete"), {
                uuid: payload.group.uuid
            });

            Vue.delete(state.groups, payload.group.url);
        },
        leaveGroup(state, payload) {
            axios.post(route("group.leave"), {
                uuid: payload.group.uuid
            });

            Vue.delete(state.groups, payload.group.url);
        }
    },
    getters: {
        getPopupMessage: state => {
            return state.popupMessage;
        },
        getShowArrow: state => {
            return state.showArrow;
        },
        getShowGroupInfo: state => {
            return state.showGroupInfo;
        },
        getCurrentPage: state => {
            return state.currentPage;
        },
        getUser: state => {
            return state.user;
        },
        getGroups: state => {
            return state.groups;
        },
        getGroup: state => group => {
            return state.groups[group];
        },
        getChannels: state => group => {
            return state.groups[group].channels;
        },
        getChannel: state => (group, chat) => {
            return state.groups[group].channels[chat];
        },
        getAllChannelUuids: state => {
            let uuids = [];

            Object.keys(state.groups).forEach(groupKey => {
                Object.keys(state.groups[groupKey].channels).forEach(
                    channelKey => {
                        uuids.push(
                            state.groups[groupKey].channels[channelKey].uuid
                        );
                    }
                );
            });

            return uuids;
        },
        getEvents: state => {
            let events = [];

            Object.values(state.groups).forEach(group => {
                group.events.forEach(event => {
                    events.push(event);
                });
            });

            return events.sort((e1, e2) => {
                if (e1.date.getTime() > e2.date.getTime()) {
                    return 1;
                }
                if (e1.date.getTime() === e2.date.getTime()) {
                    return 0;
                }
                return -1;
            });
        }
    }
});

new Vue({
    store,
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default
            }
        })
}).$mount(app);
