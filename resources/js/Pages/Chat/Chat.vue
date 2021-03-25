<template>
    <div id="chat">
        <div :id="channel.url" class="messages" v-if="renderComponent">
            <ChatElement
                v-for="message in Object.values(messages)"
                :key="message.timestamp"
                :message="message"
                :group="group"
                :message-reply-bus="messageReplyBus"
                :read-confirmation-bus="readConfirmationBus"
                class="chat-element"
            />
        </div>

    </div>
</template>

<script>
import ChatElement from "@/Pages/Chat/ChatElement";
import Navbar from "@/Pages/Navigation/Navbar";
import axios from "axios";
import Files from "@/Pages/Group/Files";

export default {
    name: "Chat",
    components: {
        Files,
        ChatElement,

        Navbar,
    },
    props: {
        group: Object,
        chat: Object,
        messageReplyBus: Object,
        readConfirmationBus: Object
    },
    data() {
        return {
            user: this.$store.getters.getUser,
            renderComponent: true,
        };
    },
    computed: {
        channel() {
            return this.$store.getters.getChannel(this.group.url, this.chat.url);
        },
        messages() {
            return this.channel.messages;
        },
    },
    watch: {
        messages: {
            handler() {
                console.log("Chat.vue: Received Message")
                let messagesElement = document.getElementById(this.channel.url);
                let scrollPercentage = Math.ceil(
                    (100 * messagesElement.scrollTop) /
                    (messagesElement.scrollHeight - messagesElement.clientHeight)
                );
                if (scrollPercentage >= 100 || isNaN(scrollPercentage)) {
                    setTimeout(() => {
                        this.scrollToBottom();
                    }, 10);
                }

            },
            deep: true,
        },
    },
    mounted() {
        this.jumpToBottom();
    },
    methods: {
        jumpToBottom() {
            let messagesElement = document.getElementById(this.channel.url);
            messagesElement.scrollTo(0, messagesElement.scrollHeight);
        },
        scrollToBottom() {
            let messagesElement = document.getElementById(this.channel.url);
            messagesElement.scrollTo({top: messagesElement.scrollHeight, left: 0, behavior: "smooth"});
        },
    },
};
</script>

<style scoped>

#chat {
    background-color: var(--background-color-alternate);
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.messages {
    height: 100%;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    padding: 2vh;
    overflow-y: auto;
    overflow-x: hidden;
}

.messages::-webkit-scrollbar {
    margin-left: -0.5rem;
    width: 0.5rem;
}

.messages::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 0.5rem;
}

.messages::-webkit-scrollbar-thumb {
    background-color: #ffa88e;
    border-radius: 0.5rem;
}

@media (max-width: 576px) {


    .messages::-webkit-scrollbar {
        width: 0.5rem;
    }


    .messages {
        padding: 0.5vh;
    }
}
</style>

