<template>
    <div class="message" v-bind:class="changeAlignment()">
        <div class="sender" v-if="!isOwn">{{ message.message.user.username }}</div>
        <div class="message-header">
            <div class="subject">{{ message.message.subject }}</div>
            <i class="fas fa-info-circle" @click="bus.$emit('open')"></i>
        </div>
        <div class="date"><span style="font-weight: bold">Datum:</span> {{ weekdayNames[date.getDay()] }},
            {{ date.toLocaleDateString('de') }}
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
        }
    }, created() {
        // this.$store.commit("addEvent", {
        //     subject: this.message.message.subject,
        //     text: this.message.message.text,
        //     date: this.message.message.date,
        //     group: this.message.message.group
        // });
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
