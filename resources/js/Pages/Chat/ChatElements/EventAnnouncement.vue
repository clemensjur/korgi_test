<template>
    <div class="message" v-bind:class="changeAlignment()">
        <div class="sender" v-if="!isOwn">{{ message.message.user.username }}</div>
        <div class="message-header">
            <div class="subject">{{ message.message.subject }}</div>
            <i class="fas fa-info-circle" v-if="isOwn" @click="bus.$emit('open', message)"></i>
        </div>
        <div class="date"><span style="font-weight: bold">Datum:</span> {{ weekdayNames[date.getDay()] }},
            {{ date.toLocaleDateString('de') }}
        </div>
        <div class="text">{{ message.message.text }}</div>
        <div class="row space-between">
            <label class="row flex-start checkbox-container" v-if="!isOwn"
                   :class="{'disabled': Object.keys(message.message.readBy).includes($store.state.pubnub.getUUID())}">
                Gelesen
                <input type="checkbox" @click="sendReadConfirmation"
                       :checked="Object.keys(message.message.readBy).includes($store.state.pubnub.getUUID())">
                <span class="checkbox" :class="{'disabled' : disabled}"></span>
            </label>
        </div>
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
    name: "EventAnnouncement",
    data() {
        return {
            weekdayNames: ["Sonntag", "Montag", 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
            disabled: false
        }
    },
    props: {
        message: Object,
        bus: Object
    },
    computed: {
        isOwn() {
            return this.message.message.user.uuid === this.$store.state.pubnub.getUUID()
        },
        date() {
            return new Date(this.message.message.date);
        }
    },
    methods: {
        changeAlignment() {
            if (this.isOwn) {
                return 'right';
            }
        },
        sendReadConfirmation() {
            if (!this.disabled) {
                this.$store.commit("publishReadConfirmation", {
                    message: this.message
                })
                this.disabled = true;
            }
        }
    }, created() {
        if (Object.keys(this.message.message.readBy).includes(this.$store.state.pubnub.getUUID())) {
            this.disabled = true;
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
    width: 80%;
    background-color: var(--message-color);
    color: var(--font-color);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1vh;
    margin: 0.5vh;
    border-radius: 1rem;
    align-self: center;
}

.sender {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 0.5vh;
}

.date {
    margin-bottom: 0.5rem;
}

.subject {
    color: var(--warn);
    font-size: 1.1rem;
    font-weight: bold;
}

.timetoken {
    align-self: flex-end;
    color: var(--font-color-light);
    font-size: 0.8rem;
}

.message-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-direction: row;
}

.message-header i {
    cursor: pointer;
    color: var(--font-color);
}

.message-header i:hover {
    color: var(--primary);
}

.right {
    background-color: var(--message-right-color);
}

.disabled {
    pointer-events: none;
}

.right .subject {
    color: var(--subject-color);
}

.text {
    word-break: break-word;
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

    .subject {
        font-size: 1rem;
    }
}
</style>
