<template>
    <div class="dialog-content">

        <input id="subject-input" class="input" type="text" v-model="subject" placeholder="Bezeichnung"
               @input="saveContent" @keypress.enter="bus.$emit('submit')">
        <textarea id="important-message-text-input" placeholder="Text" v-model="text" @input="saveContent"
                  class="textarea" @keypress.enter="bus.$emit('submit')"/>
        <date-picker :multiple="false" @input="saveDate"/>

    </div>
</template>

<script>
import DatePicker from "@/Pages/Dialog/DatePicker";

export default {
    name: "dialog-content-event-announcement",
    components: {DatePicker},
    data() {
        return {
            subject: "",
            date: [],
            text: ""
        }
    },
    props: {
        bus: Object
    },
    methods: {
        saveContent() {
            this.bus.$emit('validate', {
                subject: this.subject,
                date: this.date,
                text: this.text
            });
        },
        saveDate(payload) {
            this.date = payload.dates[0];
            this.saveContent();
        }
    }
}
</script>

<style scoped>
.dialog-content {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
}

#subject-input {
    width: 100%;
    margin-bottom: 5%;
}

#important-message-text-input {
    height: 20vh;
    overflow: auto;
    width: 100%;
    margin-bottom: 5%;
}

#important-message-text-input::-webkit-scrollbar, #new-event-container::-webkit-scrollbar {
    margin-left: -1rem;
    width: 1rem;
}

#important-message-text-input::-webkit-scrollbar-track, #new-event-container::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 0.5rem;
}

#important-message-text-input::-webkit-scrollbar-thumb, #new-event-container::-webkit-scrollbar-thumb {
    background-color: #FFA88E;
    border-radius: 0.5rem;
}
</style>
