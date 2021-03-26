<template>
    <page-layout :user="user" :groups="groups">
        <div id="events">
            <div id="events-content" :class="{ active: active }">
                <div class="events-header">
                    <h1 class="title">Termine</h1>
                    <div class="btn primary-background" @click="toggleFilters">
                        Filter
                    </div>
                </div>
                <div id="events-container">
                    <event v-for="event in events" v-if="filter(event)" :key="event.date+event.name" :event="event"/>
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
            active: false,
            filters: {
                validGroups: undefined,
                validDates: [],
                showPastEvents: false
            }
        };
    },
    created() {
        this.bus.$on("toggleFilters", () => {
            this.active = !this.active
        })
        this.bus.$on("filtersChanged", (filters) => {
            this.filters = filters
        })
        axios.get(route("user.events")).then(res => {
            res.data.forEach(events => {
                events.forEach(event => {
                    event.date = new Date(parseInt(event.date));
                    this.events.push(event)
                })
            })
        }).then(() => {
            this.events.sort((e1, e2) => {
                if (e1.date.getTime() > e2.date.getTime()) {
                    return 1;
                }
                if (e1.date.getTime() === e2.date.getTime()) {
                    return 0;
                }
                return -1;
            });
        });
        this.$store.commit("setCurrentPage", {page: "Termine"});
        this.$store.commit("setShowArrow", {showArrow: false});
    },
    methods: {
        toggleFilters() {
            this.bus.$emit("toggleFilters");
        },
        filter(event) {
            if (this.filters.validGroups) {
                if (event.team_url !== this.filters.validGroups) {
                    return false
                }
            }
            if (!this.filters.showPastEvents) {
                if (new Date(event.date).getTime() < new Date(Date.now()).getTime() ) {
                    return false
                }
            }
            if (this.filters.validDates.length > 0) {
                let dateTimes = this.filters.validDates.map(date => date.getTime())

                let dateTime = event.date.getTime();

                if (!dateTimes.includes(dateTime)) {
                    return false
                }
            }
            return true;
        }
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
}

#events-container {
    margin-top: 2vh;
    display: flex;
    position: relative;
    justify-content: flex-start;
    flex-direction: column;
    align-items: center;
    overflow: auto;
    height: 80%;
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
    #events-content.active {
        width: 100vw;
        transform: translateX(-100%);
    }

    #events-content {
        overflow: auto;
    }

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
        height: auto;
        padding-bottom: 2vh;
        overflow: visible;
    }
}

@media (min-width: 576px) {
    #events-container::-webkit-scrollbar {
        margin-left: -0.5rem;
        width: 0.5rem;
    }

    #events-container::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 0.5rem;
    }

    #events-container::-webkit-scrollbar-thumb {
        background-color: #ffa88e;
        border-radius: 0.5rem;
    }
}
</style>
