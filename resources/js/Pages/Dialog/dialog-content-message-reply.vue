<template>
    <div class="dialog-content">
        <textarea id="message-reply-text-input" placeholder="Nachricht" v-model="text" @input="saveContent"
                  class="textarea" @keypress.enter="bus.$emit('submit')"/>
    </div>
</template>

<script>
export default {
    name: "dialog-content-message-reply",
    data() {
        return {
            subject: "",
            text: "",
            message: undefined
        }
    },
    props: {
        bus: Object
    },
    methods: {
        saveContent() {
            this.bus.$emit('validate', {
                text: this.text,
                message : this.message
            });
        },
    },
    created() {
        this.bus.$on("open", (payload) => {
            this.message = payload.message;
        })
    },
}
</script>

<style scoped>
.dialog-content {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/*TODO @Jakob Scrollbar bitte schön machen*/
#message-reply-text-input {
    height: 20vh;
    overflow: auto;
    cursor: auto;
}

#message-reply-text-input::-webkit-scrollbar {
    margin-left: -0.5rem;
    width: 0.5rem;
}

#message-reply-text-input::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 2rem;
}

#message-reply-text-input::-webkit-scrollbar-thumb {
    background-color: #FFA88E;
    border-radius: 2rem;
}
</style>
