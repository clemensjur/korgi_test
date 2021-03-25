<template>
    <div class="important-message-info">
        <div class="subject">{{message.message.subject}}</div>
        <div class="text">{{message.message.text}}</div>
        <div class="read-by-container">
            <div class="read-by-header">Bestätigt von:</div>
            <div class="entry" v-for="entry in Object.values(message.message.readBy)">
                <div class="username">- {{entry.user.username}}</div>
                <div class="time">vor {{convertToBestTime(new Date()-new Date(entry.time))}}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "dialog-content-important-message-info",
    props: {
        bus: Object
    },
    data() {
        return {
            message: undefined
        }
    },
    created() {
        this.bus.$on("open", message => {
            this.message = message
        })
    },
    methods: {
        // Time in ms (Integer)
        convertToBestTime(time) {
            let unit = "Sekunden";
            let convertedTime = time / 1000;

            // min
            if (convertedTime / 60 >= 1) {
                convertedTime = convertedTime / 60;
                unit = "Minuten";
            }

            // h
            if (convertedTime / 60 >= 1) {
                convertedTime = convertedTime / 60;
                unit = "Stunden";
            }

            // d
            if (convertedTime / 24 >= 1) {
                convertedTime = convertedTime / 24;
                unit = "Tagen";
            }

            // d
            if (convertedTime / 365 >= 1) {
                convertedTime = convertedTime / 365;
                unit = "Jahren";
            }

            return Math.floor(convertedTime) + " " + unit;
        }
    }
}
</script>

<style scoped>
.important-message-info {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.subject {
    color: var(--warn);
    font-weight: bold;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.text {
    margin-bottom: 0.5rem;
    word-break: break-word;
}

.read-by-container {
    display: flex;
    flex-direction: column;
    height: 20vh;
    overflow: auto;
}

.read-by-header {
    font-size: 1.1rem;
    font-weight: bold;
}

.entry {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
</style>
