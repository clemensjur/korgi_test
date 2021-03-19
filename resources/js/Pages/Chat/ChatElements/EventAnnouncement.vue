<template>
    <div class="message" v-bind:class="changeAlignment()">
        <div class="sender" v-if="!isOwn">{{ message.message.user.username }}</div>
        <div class="subject">{{ message.message.text }}</div>
        <div class="date"><span style="font-weight: bold">Datum:</span> {{weekdayNames[date.getDay()]}}, {{date.toLocaleDateString('de')}}</div>
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
        }
    },
    props: {
        message: Object
    },
    computed: {
        isOwn() {
            return this.message.publisher === this.$store.state.pubnub.getUUID()
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

.right {
    background-color: var(--message-right-color);
}

.right .subject {
    color: var(--subject-color);
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
