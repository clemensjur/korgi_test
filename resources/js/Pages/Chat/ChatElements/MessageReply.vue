<template>
    <div class="message" :class="{own : isOwn}">
        <div class="message-header">
            <div class="sender">{{ message.message.user.username }}</div>
            <i class="fas fa-reply" @click="$emit('open', {'message': message})"></i>
        </div>
        <div class="reply" @click="scrollToMessage">
            <div class="reply-sender">{{replyMessage.message.user.username}}</div>
            <div class="reply-text">{{replyMessage.message.text}}</div>
        </div>
        <div class="text">{{ message.message.text }}</div>
        <div class="timetoken">{{
                new Date(message.timetoken / 10000).toLocaleTimeString('de', {
                    hour: "2-digit",
                    minute: "2-digit"
                })
            }}
        </div>
    </div>
</template>

<script>
export default {
    name: "message-reply",
    props: {
        message: Object
    },
    data() {
        return {
            replyMessage: this.$store.getters.getChannel(this.message.message.group, this.message.message.chat).messages[this.message.message.messageTimetoken]
        }
    },
    computed: {
        isOwn() {
            return this.message.message.user.uuid === this.$store.state.pubnub.getUUID()
        }
    },
    methods: {
        scrollToMessage() {
            document.getElementById(this.replyMessage.timetoken).scrollIntoView({
                behavior: "smooth"
            });
        },
        scrollToId(id) {
            document.getElementById(id).scrollIntoView({
                behavior: "smooth"
            });
        }
    }
}
</script>

<style scoped>


.own div::selection {
    background: var(--primary);
    color: #ffffff;
}

.message {
    max-width: 80%;
    min-width: 30%;
    background-color: var(--message-color);
    color: var(--font-color);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1vh;
    margin: 0.5vh;
    border-radius: 1rem;
}

.sender {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 0.5vh;
}

.text {
    word-break: break-word;
}

.timetoken {
    align-self: flex-end;
    color: var(--font-color-light);
    font-size: 0.8rem;
}

.own {
    align-self: flex-end;
    background-color: var(--message-right-color);
}

.message-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.reply {
    width: 100%;
    background-color: var(--background-color-alternate);
    padding: 1vh;
    border-radius: 1rem;
    margin-bottom: 0.5vh;
    cursor: pointer;
}

.reply-sender {
    font-size: 0.9rem;
    font-weight: bold;
    margin-bottom: 0.5vh;
}

.reply-text {
    font-size: 0.8rem;
}

.fa-reply {
    color: var(--header-color);
    cursor: pointer;
    margin-left: 1rem;
}

.fa-reply:hover {
    color: var(--primary);
}

@media (max-width: 576px) {
    .message {
        padding: 2.5%;
        font-size: 0.8rem;
        margin: 0.2vh;
    }
    .sender {
        font-size: 0.9rem;
    }
    .timetoken {
        font-size: 0.7rem;
    }
}
</style>
