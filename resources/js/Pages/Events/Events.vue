<template>
    <page-layout>
        <div id="events">
            <div id="events-content" :class="{ active: active }">
                <div class="events-header">
                    <h1 class="title">Termine</h1>
                    <div class="btn primary-background" @click="toggleFilters">
                        Filter
                    </div>
                </div>
                <div id="events-container">
                    <event v-for="event in events" :key="event.date+event.name" :event="event"/>
                </div>
                <div
                    class="round-btn primary-background"
                    id="filters-btn-mobile"
                    @click="toggleFilters"
                >
                    <i class="fas fa-sliders-h"></i>
                </div>
            </div>
            <event-filters :bus="bus"/>
        </div>
    </page-layout>
</template>

<script>
import Vue from "vue";

import PageLayout from "@/Layouts/PageLayout.vue";
import Event from "@/Pages/Events/Event";
import EventFilters from "@/Pages/Events/EventFilters";

export default {
    name: "Events",
    components: {EventFilters, Event, PageLayout},
    props: {
        user: Object,
        groups: Array,
    },
    // computed: {
    //     events() {
    //         return this.$store.getters.getEvents;
    //     },
    // },
    data() {
        return {
            bus: new Vue(),
            events: [],
            active: false
        };
    },
    created() {
        this.bus.$on("toggleFilters", () => {
            this.active = !this.active
        })
        axios.get(route("user.events")).then(res => {
            res.data.forEach(events => {
                events.forEach(event => {
                    this.events.push(event)
                })
            })

        });
        this.$store.commit("setCurrentPage", {page: "Termine"});
        this.$store.commit("setShowArrow", {showArrow: false});
    },
    methods: {
        toggleFilters() {
            this.bus.$emit("toggleFilters");
        },
    },
};
</script>

<style scoped>
#events {
    display: flex;
    flex-direction: row;
    width: 100%;
    height: 100%;
}

#events-content.active {
    width: 60vw;
}

#events-content {
    width: 100%;
    background-color: var(--background-color-alternate);
    overflow: auto;
}

#events-container {
    margin-top: 2vh;
    display: flex;
    position: relative;
    flex-direction: column;
    flex-wrap: wrap;
    justify-content: flex-start;
}

.events-header {
    padding: 4vh;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.btn {
    width: fit-content;
}

#filters-btn-mobile {
    display: none;
    position: fixed;
    bottom: 4vh;
    left: 4vh;
    box-shadow: 1px 0px 8px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 8px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 8px 3px var(--shadow-color);
}

@media (max-width: 576px) {
    .title {
        display: none;
    }

    .events-header {
        display: none;
    }

    #filters-btn-mobile {
        display: flex;
    }

    #events-container {
        margin-top: 0;
    }
}

@media (min-width: 576px) {
    #events-content::-webkit-scrollbar {
        margin-left: -1rem;
        width: 1rem;
    }

    #events-content::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 0.5rem;
    }

    #events-content::-webkit-scrollbar-thumb {
        background-color: #ffa88e;
        border-radius: 0.5rem;
    }
}
</style>
