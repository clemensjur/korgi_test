<template>
    <div class="event">
        <div class="date">
            <div class="date-text">{{event.date.toLocaleDateString('de')}}</div>
        </div>
        <div class="subject">{{ event.name }}</div>
        <div class="description">
        </div>
    </div>
</template>

<script>
export default {
    name: "Event",
    props: {
        event: Object
    }
}
</script>

<style scoped>
.event {
    display: flex;
    flex-direction: column;
    width: 60%;
    border-radius: 1rem;
    background-color: var(--background-color);

    box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 15px 3px var(--shadow-color);

    align-self: center;
    margin-bottom: 1vh;
    margin-top: 2vh;
}

.date {
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
    background-color: var(--primary);
}

.date-text {
    font-size: 1.5rem;
    margin: 1vh 1.8vh 1vh 1.8vh;
    color: white;
    font-weight: 800;
}

.subject {
    margin-left: 1.8vh;
    margin-top: 1.8vh;
    font-weight: 800;
    font-size: 1.3rem;
    color: var(--font-color)
}
.description {
    margin: 1.8vh;
    color: var(--font-color)
}

@media (max-width: 576px) {
    .event {
        width: 90%;
    }
    .date-text {
        margin: 1.5vh 2vh 1.5vh 2vh;
        font-size: 1.4rem;
    }
    .subject {
        margin-left: 2vh;
        margin-top: 2vh;
        font-size: 1.2rem;
    }
    .description {
        margin: 2vh;
    }
}
</style>
