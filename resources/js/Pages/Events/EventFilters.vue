<template>
    <div id="filters" :class="{'active': active}">
        <div id="filters-header">
            <div>Filter</div>
            <div class="round-btn secondary-background" @click="toggleActive">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="section-header">Datum</div>
        <div id="filters-date-picker">
            <date-picker :multiple="true" @input="saveDate"/>
        </div>
        <label class="checkbox-container">
            Vergangene Termine anzeigen
            <input type="checkbox" name="push-msg" value="push-msg">
            <span class="checkbox"></span>
        </label>
        <div class="section-header">Gruppe</div>
        <select class="alternate-input">
            <option>Alle</option>
            <option>Schmetterlinge</option>
            <option>Raupen</option>
        </select>
    </div>
</template>

<script>
import DatePicker from "@/Pages/Dialog/DatePicker";

export default {
    name: "event-filters",
    components: {DatePicker},
    props: {
        bus: Object
    },
    data() {
        return {
            active: false
        }
    },
    created() {
        this.bus.$on("toggleFilters", () => {
            this.toggleActive()
        })
    },
    methods: {
        toggleActive() {
            this.active = !this.active;
        },
        saveDate() {}
    }
}
</script>

<style scoped>

#filters {
    width: 0;
    height: 100%;
    color: var(--font-color);
    overflow: hidden;
    z-index: 100;
    background-color: var(--background-color);
    box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 15px 3px var(--shadow-color);

    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    /*padding: 1% 0 1% 0;*/

    transition: 0.2s ease;
}

#filters.active {
    width: 28%;
    padding: 2vh;
}

::placeholder {
    color: var(--font-color)
}

#filters-header {
    margin-top: 4vh;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 4vh;
}

#filters-date-picker {
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.section-header {
    font-weight: 600;
    margin-top: 2.5vh;
    margin-bottom: 1.2vh;
}

.checkbox-container {
    margin-top: 6vh;
    white-space: nowrap;
}

@media (max-width: 1200px) {
    #filters.active {
        position: absolute;
        width: 70vw;
        height: 100%;
        padding: 2vh;
    }
    #filters {
        position: absolute;
        width: 100%;
        height: 0;
        box-shadow: none;
    }
    #filters-header {
        margin-top: 2.5vh;
    }
}

@media (max-width: 768px) {
    #filters.active {
        width: 55vw;
    }
}

@media (max-width: 576px) {
    #filters-header {
        margin-top: 1vh;
    }
    #filters.active {
        position: absolute;
        width: 100%;
        height: 100%;
        padding: 2vh;
    }
    #filters {
        position: absolute;
        width: 100%;
        height: 0;
        box-shadow: none;
    }
}

</style>
