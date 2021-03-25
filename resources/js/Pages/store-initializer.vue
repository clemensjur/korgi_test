<template></template>

<script>
import Vue from "vue";
import PubNub from "pubnub";

export default {
  name: "store-initializer",
  props: {
    user: Object,
    groups: Array,
  },
  data() {
    return {
      messages: [],
    };
  },
  created() {
    this.initStore();
    this.pubnubAddListener();
    this.pubnubSubscribe();
  },
  methods: {
    initStore() {
      this.$store.commit("setState", {
        pubnub: new PubNub({
          publishKey: this.getPublishKey(),
          subscribeKey: this.getSubscribeKey(),
          uuid: this.user.uuid,
        }),
        user: {
          username: this.getUsername(),
          uuid: this.user.uuid,
          settings: {
            darkmode: false,
          },
        },
        groups: this.getGroups(),
      });

      this.getAllMessagesFromLocalStorage();
      this.getAllMissedMessagesFromPubNub();
    },
    pubnubAddListener() {
      this.$store.state.pubnub.addListener({
        message: (event) => {
          this.$store.commit("addMessage", {
            message: event,
          });

          this.$inertia.reload(
            route("group.show", { url: event.message.group })
          );
        },
        messageAction: (event) => {
          let value = JSON.parse(event.data.value);

          switch (event.data.type) {
            case "poll":
              this.$store.commit("addPollMessageAction", {
                group: value.group,
                chat: value.chat,
                channel: value.channel,
                user: value.user,
                messageTimetoken: event.data.messageTimetoken,

                // Poll specific
                answerKey: value.answerKey,
              });
              break;
          }
          this.$inertia.reload(route("group.show", { url: value.group }));
        },
      });
    },
    pubnubSubscribe() {
      this.$store.state.pubnub.subscribe({
        channels: this.$store.getters.getAllChannelUuids,
        withPresence: true,
      });
    },
    getAllMessagesFromLocalStorage() {
      Object.keys(this.$store.state.groups).forEach((group) => {
        Object.keys(this.$store.state.groups[group].channels).forEach(
          (chat) => {
            let channel = this.$store.state.groups[group].channels[chat].uuid;
            let messages = this.getMessagesFromLocalStorage(channel);

            Object.keys(messages).forEach((timetoken) => {
              Vue.set(
                this.$store.state.groups[group].channels[chat].messages,
                timetoken,
                messages[timetoken]
              );
            });
          }
        );
      });
    },
    getMessagesFromLocalStorage(channel) {
      return JSON.parse(localStorage.getItem(channel)) || {};
    },
    getAllMissedMessagesFromPubNub() {
      Object.keys(this.$store.state.groups).forEach((group) => {
        Object.keys(this.$store.state.groups[group].channels).forEach(
          (chat) => {
            let channel = this.$store.state.groups[group].channels[chat];
            let messageValues = Object.values(channel.messages);
            if (messageValues.length > 0) {
              this.getMissedMessagesFromPubNub(
                group,
                chat,
                channel.uuid,
                messageValues[messageValues.length - 1].timetoken
              );
            }
          }
        );
      });
    },
    getMissedMessagesFromPubNub(
      group,
      chat,
      channel,
      endTimetoken,
      startTimetoken
    ) {
      this.$store.state.pubnub.fetchMessages(
        {
          channels: [channel],
          start: startTimetoken,
          end: endTimetoken,
          count: 25, // default/max is 25
        },
        (status, response) => {
          if (response) {
            let newMessages = Object.values(response.channels)[0];

            this.messages = this.messages.concat(newMessages);

            let currentTimetoken = newMessages[0].timetoken;

            if (currentTimetoken !== endTimetoken) {
              this.getMissedMessagesFromPubNub(
                group,
                chat,
                channel,
                endTimetoken,
                currentTimetoken
              );
              return;
            }

            this.messages.sort((a, b) => {
              if (parseInt(a.timetoken) > parseInt(b.timetoken)) {
                return 1;
              }
              if (parseInt(a.timetoken) < parseInt(b.timetoken)) {
                return -1;
              }
              return 0;
            });

            this.messages.shift();

            this.messages.forEach((message) => {
              Vue.set(
                this.$store.state.groups[group].channels[chat].messages,
                message.timetoken,
                message
              );
            });

            this.$inertia.reload(route("group.show", { url: group }));

            this.$store.state.methods.saveMessagesToLocalStorage(
              group,
              chat,
              channel
            );
            this.messages = [];
          }
        }
      );

      // store.state.pubnub.fetchMessages(
      //     {
      //         channels: [channel],
      //         start: startTimetoken,
      //         end: endTimetoken,
      //         count: 1 // default/max is 25
      //     },
      //     function (status, response) {
      //         let messages = Object.values(response.channels)[0];
      //         messages.forEach(message => {
      //             Vue.set(store.state.groups[group].channels[chat].messages, message.timetoken, message);
      //         });
      //         console.log(messages)
      //         console.log(messages[messages.length-1].timetoken);
      //         console.log(endTimetoken);
      //         let currentTimetoken = messages[messages.length-1].timetoken;
      //         if (currentTimetoken !== endTimetoken) {
      //             getMissedMessagesFromPubNub(group, chat, channel, endTimetoken, currentTimetoken)
      //         }
      //         saveMessagesToLocalStorage(group, chat, channel);
      //     }
      // )
    },
    //TODO aus der Datenbank holen
    getPublishKey() {
      return "pub-c-b25ff12b-5bec-4fa8-b5c8-9d37331f3464";
    },

    //TODO aus der Datenbank holen
    getSubscribeKey() {
      return "sub-c-fdf66a10-6890-11eb-95b1-4ae0cccec446";
    },
    getUsername() {
      return this.user.name;
    },

    getUrlFromName(name) {
      return name.toLowerCase().replace(" ", "-");
    },

    toObject(array) {
      let obj = {};
      array.forEach((element) => {
        obj[Object.keys(element)[0]] = element[Object.keys(element)[0]];
      });
      return obj;
    },

    //TODO aus der Datenbank holen
    getGroups() {
      let groups = this.toObject(this.groups);
      Object.keys(groups).forEach((group) => {
        groups[group].channels.allgemein.messages = {};
        groups[group].channels.wichtig.messages = {};
      });
      return groups;
    },

    getEvents() {
    },
  },
};
</script>

<style scoped>
</style>
