<template>
    <page-layout :user="user" :groups="groups">
        <div id="outer-wrapper">
            <div id="group-view">
                <!--<inertia-link :href="route('teams.create')">Create Team</inertia-link>-->
                <dialog-window
                    :bus="groupInputBus"
                    title="Gruppe erstellen"
                    @submit="createGroup"
                >
                    <dialog-content-create-group :bus="groupInputBus"/>
                </dialog-window>

                <dialog-window title="Farbe ändern" :bus="colorPickerBus" @submit="submitColorChange">
                    <DialogContentColorPicker :bus="colorPickerBus"/>
                </dialog-window>

                <div class="group-view-header">
                    <h1 class="title">Gruppenübersicht</h1>
                </div>
                <div id="groups">
                    <group-card
                        v-for="(group, key, index) in groupsObject"
                        :group="group"
                        :key="group.url"
                        :delay="index * 200"
                        :color-picker-bus="colorPickerBus"
                    />
                    <new-group-card v-if="mounted" :delay="Object.values(groupsObject).length * 200" @click="groupInputBus.$emit('open')"/>
                </div>
            </div>
        </div>
    </page-layout>
</template>

<script>
import Vue from "vue";
// import { Inertia } from "@inertiajs/inertia";

import PageLayout from "@/Layouts/PageLayout.vue";
import Group from "@/Pages/Group/Group";
import GroupCard from "@/Pages/Group/GroupCard";
import NewGroupCard from "@/Pages/Group/NewGroupCard";
import DialogContentCreateGroup from "@/Pages/Dialog/dialog-content-create-group";
import StoreInitializer from "@/Pages/store-initializer";
import DialogWindow from "@/Pages/Dialog/dialog-window";
import DialogContentColorPicker from "@/Pages/Dialog/DialogContentColorPicker";
import axios from "axios";

export default {
    name: "GroupView",
    components: {
        StoreInitializer,
        PageLayout,
        Group,
        DialogContentCreateGroup,
        DialogContentColorPicker,
        DialogWindow,
        NewGroupCard,
        GroupCard,
    },
    props: {
        user: Object,
        groups: Array,
    },
    data() {
        return {
            mounted: false,
            groupInputBus: new Vue(),
            colorPickerBus: new Vue()
        };
    },
    mounted() {
        this.mounted = true
    },
    created() {
        this.$store.commit("setCurrentPage", {page: "Gruppenübersicht"});
        this.$store.commit("setShowArrow", {showArrow: false});
    },
    computed: {
        groupsObject: function () {
            return this.$store.getters.getGroups;
        },
    },
    methods: {
        submitColorChange(payload) {
            this.$store.commit("changeGroupColor", payload)
        },
        createGroup(name) {
            this.$store.commit("addGroup", {name: name});

            // weniger schön, geht aber garantiert falls das andere irgendwann versagen sollte
            // axios.post("/gruppen", {
            //     name: name,
            //     color: "#FFC78E"
            // }).then(() => {
            //     this.$inertia.visit(route("groups.show"));
            // });
        },
    },
};
</script>

<style scoped>
#outer-wrapper {
    width: 100%;
    height: 100%;
}

#group-view {
    height: 100%;
    width: 100%;
    background-color: var(--background-color-alternate);
    overflow: auto;
}

.group-view-header {
    padding: 4vh;
}

#groups {
    margin-top: 2%;
    display: flex;
    position: relative;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
}

@media (max-width: 1200px) {
    #groups {
        padding-left: 3vh;
    }
}

@media (max-width: 576px) {
    #groups {
        /*margin-top: 25%;*/
        flex-direction: column;
        flex-wrap: nowrap;
        align-items: center;
        padding-left: 0;
    }

    .group-view-header {
        display: none;
    }
}

@media (min-width: 576px) {
    #group-view::-webkit-scrollbar {
        margin-left: -0.5rem;
        width: 0.5rem;
    }

    #group-view::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 0.5rem;
    }

    #group-view::-webkit-scrollbar-thumb {
        background-color: #ffa88e;
        border-radius: 0.5rem;
    }
}
</style>
