<template>
    <div class="message" v-bind:class="changeAlignment()">
        <div class="message-header">
            <div class="sender">{{ message.message.user.username }}</div>
            <i class="fas fa-reply" @click="$emit('open')"></i>
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
    name: "Message",
    props: {
        message: Object
    },
    computed: {
        isOwn() {
            return this.message.publisher === this.$store.state.pubnub.getUUID()
        }
    },
    methods: {
        changeAlignment() {
            if (this.isOwn) {
                return 'right';
            }
        }
    }
}
</script>

<style scoped>

.right div::selection {
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
    font-weight: 500;
}

.timetoken {
    align-self: flex-end;
    color: var(--font-color-light);
    font-size: 0.8rem;
}

.right {
    align-self: flex-end;
    background-color: var(--message-right-color);
}

.message-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
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
