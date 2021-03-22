<template>
    <Transition name="fade-up">
        <div class="chat-element" :class="{own: isOwn}" :id="message.timetoken" v-if="showMessage">
            <message v-if="message.message.messageType === 'message' && message.message.chat === 'allgemein'"
                     :message="message" v-on:open="(payload) => messageReplyBus.$emit('open', payload)"/>
            <important-message v-if="message.message.messageType === 'importantMessage'" :message="message"/>
            <less-important-message
                v-if="message.message.messageType === 'message' && message.message.chat === 'wichtig'"
                :message="message"/>
            <file v-if="message.message.messageType === 'file'" :group="group" :message="message"
                  v-on:open="(payload) => messageReplyBus.$emit('open', payload)"/>
            <event-announcement v-if="message.message.messageType === 'eventAnnouncement'" :message="message"/>
            <poll v-if="message.message.messageType === 'poll'" :message="message" :group="group"/>
            <message-reply v-if="message.message.messageType === 'reply'" :message="message"
                           v-on:open="(payload) => messageReplyBus.$emit('open', payload)"></message-reply>
        </div>
    </Transition>
</template>

<script>
import Message from "@/Pages/Chat/ChatElements/Message";
import File from "@/Pages/Chat/ChatElements/File";
import EventAnnouncement from "@/Pages/Chat/ChatElements/EventAnnouncement";
import LessImportantMessage from "@/Pages/Chat/ChatElements/LessImportantMessage";
import ImportantMessage from "@/Pages/Chat/ChatElements/ImportantMessage";
import Poll from "@/Pages/Chat/ChatElements/Poll";
import DialogWindow from "@/Pages/Dialog/dialog-window";
import DialogContentMessageReply from "@/Pages/Dialog/dialog-content-message-reply";
import Vue from "vue";
import MessageReply from "@/Pages/Chat/ChatElements/MessageReply";

export default {
    name: "ChatElement",
    components: {
        MessageReply,
        DialogContentMessageReply,
        DialogWindow, Poll, ImportantMessage, LessImportantMessage, EventAnnouncement, File, Message
    },
    props: {
        messageReplyBus: Object,
        message: Object,
        group: Object
    },
    computed: {
        isOwn() {
            return this.message.message.user.uuid === this.$store.state.pubnub.getUUID()
        },
    },
    data() {
        return {
            playAnimation: false,
            showMessage: true
        };
    },
    created() {
        if (!this.message.notNew) {
            this.message.notNew = true;
            this.playAnimation = true;
            this.showMessage = false;

            this.$store.state.methods.saveMessagesToLocalStorage(this.message.message.group, this.message.message.chat, this.message.channel)
        }
    },
    mounted() {
        if (this.playAnimation) {
            this.showMessage = true;
        }
    },
    methods: {

    }
}
</script>

<style scoped>

.chat-element {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    width: 100%;
    padding-left: 10vh;
    padding-right: 10vh;
}

.fade-up-enter-active,
.fade-up-leave-active {
    transition: all .5s ease;
}

.fade-up-enter, .fade-up-leave-to /* .fade-leave-active below version 2.1.8 */
{
    transform: translateX(-50%);
    opacity: 0;
}

.own.fade-up-enter, .fade-up-leave-to /* .fade-leave-active below version 2.1.8 */
{
    transform: translateX(50%);
    opacity: 0;
}

@media (max-width: 1200px) {
    .chat-element {
        padding-left: 2vh;
        padding-right: 2vh;
    }
}

@media (max-width: 576px) {
    .chat-element {
        padding-left: 4%;
        padding-right: 2%;
        margin-top: 4px;
        margin-bottom: 4px;
    }
}
</style>
