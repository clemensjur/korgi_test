<template>
  <div class="message" :class="{ own: isOwn }">
    <div class="sender" v-if="!isOwn">{{ message.message.user.username }}</div>
    <div class="subject">{{ message.message.subject }}</div>
    <div class="poll">
      <poll-answer
        v-for="answerKey in Object.keys(message.message.answers)"
        :key="answerKey"
        :answerKey="answerKey"
        :message="message"
        @click="addResult(answerKey)"
      />
    </div>
    <div class="timetoken">
      {{
        new Date(message.timetoken / 10000).toLocaleTimeString("de", {
          hour: "2-digit",
          minute: "2-digit",
        })
      }}
    </div>
  </div>
</template>

<script>
import PollAnswer from "@/Pages/Chat/ChatElements/PollAnswer";

export default {
  name: "poll",
  components: { PollAnswer },
  props: {
    message: Object,
    group: Object,
  },
  computed: {
    isOwn() {
      return this.message.publisher === this.$store.state.pubnub.getUUID();
    },
  },
  methods: {
    addResult(answerKey) {
      this.$store.commit("publishMessageAction", {
        type: "poll",
        message: this.message,
        answerKey: answerKey,
      });

      this.$inertia.reload(route("group.show", { url: this.group.url }));
      // Vue.set(this.message.message.results, this.$store.state.user.uuid, answerKey);
    },
  },
};
</script>

<style scoped>


.own div::selection {
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

.own .subject {
    color: var(--subject-color);
}

.timetoken {
  align-self: flex-end;
  color: var(--font-color-light);
  font-size: 0.8rem;
}

.own {
  background-color: var(--message-right-color);
}

.poll {
  width: 100%;
  transition: 0.2s ease;
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
