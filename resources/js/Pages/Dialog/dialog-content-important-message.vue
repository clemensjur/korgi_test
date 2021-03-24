<template>
    <div class="dialog-content">
        <input id="important-message-subject-input"
               class="input"
               type="text"
               v-model="subject"
               placeholder="Betreff"
               @input="saveContent"
               @keypress.enter="bus.$emit('submit')">
        <textarea id="important-message-text-input" placeholder="Text" v-model="text" @input="saveContent" class="textarea" @keypress.enter="bus.$emit('submit')"/>
    </div>
</template>

<script>
export default {
    name: "dialog-content-important-message",
    data() {
        return {
            subject: "",
            text: ""
        }
    },
    props: {
        bus: Object
    },
    methods: {
        saveContent(event) {
            this.bus.$emit('validate', {"subject": this.subject, "text": this.text});
        },
    }
}
</script>

<style scoped>
.dialog-content {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

#important-message-subject-input {
    margin-bottom: 5%;
}

#important-message-text-input {
    height: 20vh;
    overflow: auto;
}

#important-message-text-input::-webkit-scrollbar {
    margin-left: -1rem;
    width: 1rem;
}

#important-message-text-input::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 0.5rem;
}

#important-message-text-input::-webkit-scrollbar-thumb {
    background-color: #FFA88E;
    border-radius: 0.5rem;
}
</style>
