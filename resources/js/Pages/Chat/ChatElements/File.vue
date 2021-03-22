<template>
    <div class="file-element" v-bind:class="changeAlignment()">
        <div class="message-header">
            <div class="sender">{{ message.message.user.username }}</div>
            <i class="fas fa-reply" @click="$emit('open', {'message': message})"></i>
        </div>
        <p class="text">{{message.message.text}}</p>
        <div class="file-container primary-background" @click="download">
            <i class="file-icon far fa-file-pdf"></i>
            <p class="text">{{message.message.fileName}}</p>
        </div>
        <div class="timetoken">
            {{
                new Date(message.timetoken / 10000).toLocaleTimeString('de', {
                    hour: "2-digit",
                    minute: "2-digit"
                })
            }}
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "File",
    props: {
        message: Object,
        group: Object
    },
    computed: {
        isOwn() {
            return this.message.message.user.uuid === this.$store.state.pubnub.getUUID()
        }
    },
  },
  methods: {
    changeAlignment() {
      if (this.isOwn) {
        return "right";
      }
    },
    download() {
      console.log(this.message.message.fileName);
      console.log(this.group.id);
      let filename = this.message.message.fileName;
      axios
        .post(route("group.files.download"), {
          filename: filename,
          groupId: this.group.id,
        }, {
            responseType: 'blob'
        })
        .then((res) => {
          console.log(res);
          let blob = new Blob([res.data]);

          if (window.navigator && window.navigator.msSaveOrOpenBlob)
            return window.navigator.msSaveOrOpenBlob(blob);

          // For other browsers:
          // Create a link pointing to the ObjectURL containing the blob.
          const data = window.URL.createObjectURL(blob);

          const link = document.createElement("a");
          link.href = data;
          link.download = JSON.parse(res.config.data).filename;

          // this is necessary as link.click() does not work on the latest firefox
          link.dispatchEvent(
            new MouseEvent("click", {
              bubbles: true,
              cancelable: true,
              view: window,
            })
          );

          setTimeout(() => {
            // For Firefox it is necessary to delay revoking the ObjectURL
            window.URL.revokeObjectURL(data);
            link.remove();
          }, 100);
        });
    },
  },
};
</script>

<style scoped>
.right div::selection {
  background: var(--primary);
  color: #ffffff;
}

.file-element {
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

.right {
  text-align: right;
  align-self: flex-end;
  background-color: var(--message-right-color);
}

.file-container {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  border-radius: 1rem;
  margin-top: 0.4rem;
  margin-bottom: 0.2rem;
  padding: 1rem;
}

.file-icon {
  font-size: 2rem;
  color: #ef5252;
  margin-right: 1rem;
  text-align: center;
}

.sender {
  font-size: 1.1rem;
  font-weight: bold;
  margin-bottom: 0.5vh;
}

.timetoken {
  align-self: flex-end;
  color: var(--font-color-light);
  font-size: 0.8rem;
}

.text {
  word-break: break-word;
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
  .file-element {
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
