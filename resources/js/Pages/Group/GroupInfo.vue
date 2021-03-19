<template>
    <div id="group-info" :class="{ active: active }">


        <div id="group-info-header">
            <div>Gruppeninfo</div>
            <div class="round-btn secondary-background" @click="toggleGroupInfo">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div id="group-info-name">
            <div class="section-header">Name</div>
            <div class="input-container">
                <input
                    class="alternate-input"
                    :class="{'disabled': !nameInputActive}"
                    id="group-name-input"
                    type="text"
                    name="groupname"
                    value="groupname"
                    placeholder="Gruppenname"
                    v-model="groupName"
                />
                <div id="group-info-name-buttons">
                    <Transition name="fade">
                        <div class="round-btn warn-background" v-if="nameInputActive" @click="cancelName">
                            <i class="fas fa-times"/>
                        </div>
                    </Transition>

                    <Transition name="fade">
                        <div class="round-btn secondary-background" v-if="nameInputActive" @click="updateName">
                            <i class="fas fa-check"/>
                        </div>

                        <div class="round-btn primary-background" v-if="!nameInputActive" @click="nameInputActive=true">
                            <i class="fas fa-pen"/>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>
        <div id="group-info-members">
            <div class="section-header">Mitglieder</div>
            <member v-for="member in showAll ? group.users : group.users.slice(0, 5)" :key="member.id" :member="member"></member>
            <div class="button-container" v-if="group.users.length > 5">
                <div class="btn secondary-background" v-if="!showAll" @click="showAll=true">
                    <p>{{ group.users.length - 5 }} weitere</p>
                    <i class="fas fa-angle-down"/>
                </div>
                <div class="btn secondary-background" v-if="showAll" @click="showAll=false">
                    <p>weniger</p>
                    <i class="fas fa-angle-up"/>
                </div>
            </div>
        </div>

        <div id="group-info-invitation">
            <div class="section-header">Einladungslink</div>
            <div class="btn secondary-background" @click="dialogBus.$emit('open')">
                Leute einladen
            </div>
        </div>
        <div id="group-info-delete" v-if="hasAdminPermissions && isEmpty">
            <div class="btn warn-background" v-on:click="deleteGroup">
                <p>Gruppe löschen</p>
                <i class="fas fa-trash-alt"/>
            </div>
        </div>
        <div id="group-info-leave" v-else><!--v-if="!hasAdminPermissions || !isEmpty"-->
            <div class="btn warn-background" v-on:click="leaveGroup">
                <p>Gruppe verlassen</p>
                <i class="fas fa-sign-out-alt"></i>
            </div>
        </div>
    </div>
</template>

<script>
import Member from "@/Pages/Group/Member";
import axios from "axios";

export default {
    name: "group-info",
    components: {Member},
    props: {
        dialogBus: Object,
        bus: Object,
        group: Object,
        hasAdminPermissions: Boolean,

    },
    data() {
        return {
            groupName: this.group.name,
            isEmpty: this.group.users.length < 2,
            nameInputActive: false,
            showAll: false,
        };
    },
    computed: {
        active() {
            return this.$store.getters.getShowGroupInfo
        }
    },
    created() {
        this.group.users = this.group.users.sort((a, b) => {
            if (a.isAdmin && !b.isAdmin) {
                return -1;
            }

            if (b.isAdmin && !a.isAdmin) {
                return 1;
            }

            return a.name.localeCompare(b.name);
        })
    },
    methods: {
        toggleGroupInfo() {
            this.$store.commit("setShowGroupInfo", {'showGroupInfo': !this.$store.getters.getShowGroupInfo});
        },
        deleteGroup() {
            axios
                .post(route("group.delete"), {
                    uuid: this.group.uuid,
                })
                .then(() => this.$inertia.visit(route("groups.show"), {only: ["groups"]}));
        },
        leaveGroup() {
            axios
                .post(route("group.leave"), {
                    uuid: this.group.uuid,
                }) /*.then(() => {
        Object.values(this.$store.state.groups).filter((i, v, a) => {
          return v.uuid != this.group.uuid;
        });
      })*/
                .then(() => this.$inertia.visit(route("groups.show")));
        },
        updateName() {
            this.nameInputActive = false
            axios.post(route("group.update"), {
                groupId: this.group.id,
                groupName: this.groupName
            }).then(res => {
                console.log(res);
                this.$inertia.visit(route("group.show", {url: this.urlFormat(this.groupName)}), {only: ["group"]});
            });
        },
        cancelName() {
            this.nameInputActive = false;
            this.groupName = this.group.name;
        },

        urlFormat(str) {
            return str.toLowerCase().replace(" ", "-");
        }
    },
    mounted() {
    },
};
</script>

<style scoped>
#group-info {
    position: fixed;
    right: 0;
    top: 0;

    width: 20vw;
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
    padding: 1%;

    transform: translateX(100%);
}

#group-info.active {
    transform: translateX(0);
}

::placeholder {
    color: var(--font-color);
}

#group-info-header {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    font-size: 1.5rem;
    font-weight: 700;
}

#group-info-name {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
}

#group-info-name-buttons {
    display: flex;
    flex-direction: row;
    width: 7rem;
    justify-content: flex-end;
}

#group-info-name-buttons .round-btn {
    margin-left: 0.5rem;
}

#group-info-members {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
}

#group-info-delete {
    margin-top: auto;
}

#group-info-leave {
    margin-top: auto;
}

.section-header {
    font-weight: 600;
    margin-top: 2.5vh;
    margin-bottom: 0.6vh;
}

.button-container {
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}

#group-info-members .btn {
    width: fit-content;
}

#group-info-members .btn .fas {
    margin-left: 0.5rem;
}

#group-info-invitation .btn {
    width: fit-content;
    justify-content: center;
}

#group-info-delete .btn {
    width: 100%;
}

#group-info-leave .btn {
    width: 100%;
}

.input-container {
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

/*Transitions*/

.fade-enter-active,
.fade-leave-active {
    transition: all 0.15s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0;
}

@media (max-width: 1200px) {
    #group-info.active {
        position: absolute;
        width: 65vw;
        height: 100%;
        padding: 2vh;
        z-index: 35;
    }

    #group-info {
        position: absolute;
        width: 100%;
        height: 100%;
        box-shadow: none;
        z-index: 0;
    }

    #group-info-delete .btn {
        width: 25vh;
    }
}

@media (max-width: 576px) {
    #group-info.active {
        /*display: flex;*/
        position: absolute;
        width: 100%;
        height: 100%;
        padding: 2vh;
    }

    #group-info {
        /*display: none;*/
        position: absolute;
        width: 100%;
        height: 100%;
        box-shadow: none;
        padding: 2vh;
    }
}
</style>
