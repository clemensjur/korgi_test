<template>
    <Transition name="fade-up">
        <div v-if="mounted" class="group-card no-select" v-bind:style="{borderColor: group.color}" @mouseleave="showMenu=false" @click.self="linkToGroup">
            <div class="group-card-icon" v-bind:style="{backgroundColor: group.color}" @click.self="linkToGroup">{{ group.name.substring(0, 1) }}</div>
            <h1 class="group-card-name" @click.self="linkToGroup">{{ group.name }}</h1>
            <i class="fas fa-ellipsis-h group-card-menu" @click.self="showMenu=!showMenu"></i>
            <transition name="fade">
                <context-menu v-if="showMenu" :is-empty="isEmpty" :has-admin-permissions="getAdminStatus()" @leave="leaveGroup" @delete="deleteGroup" @changeColor="colorPickerBus.$emit('open', {'group': group})"/>
            </transition>
        </div>
    </Transition>
</template>

<script>
import axios from "axios";
import Vue from "vue";
import ContextMenu from "@/Pages/Group/ContextMenu";

export default {
    name: "GroupCard",
    components: {ContextMenu},
    props: {
        group: Object,
        user: Object,
        delay: Number,
        colorPickerBus: Object
    },
    data() {
        return {
            bus: new Vue(),
            showMenu: false,
            mounted: false,
            isEmpty: this.group.users.length < 2,
        }
    },
    created() {
        console.log(this.user)
    },
    mounted() {
        // document.getElementsByClassName("group-card")[0].style.borderColor = this.group.color;
        // console.log(document.getElementsByClassName("group-card")[0])
        setTimeout(() => {
            this.mounted = true
        }, this.delay)
    },
    methods: {
        getAdminStatus() {
            let admin;
            this.group.users.forEach(user => {
                if (user.id === this.user.id) {
                    admin = user.isAdmin;
                }
            });
            return admin;
        },
        deleteGroup() {
            if (this.getAdminStatus()) {
                this.$store.commit("deleteGroup", {"group": this.group});
            }
        },
        leaveGroup() {
            this.$store.commit("leaveGroup", {"group": this.group})
        },
        linkToGroup() {
            axios
                .post(route("group.set"), {
                    groupId: this.group.id,
                    last_message: this.generateLaravelTimestamp(),
                })
                // .then((res) => console.log(res))
                .then(() => {
                    this.$inertia.visit(route("group.show", {url: this.group.url}));
                });
        },
        generateLaravelTimestamp() {
            var d = new Date();
            var year = d.getFullYear();
            var month = ("0" + (d.getMonth() + 1)).slice(-2);
            var day = ("0" + d.getDate()).slice(-2);
            var hour = ("0" + d.getHours()).slice(-2);
            var minutes = ("0" + d.getMinutes()).slice(-2);
            var seconds = ("0" + d.getSeconds()).slice(-2);
            return (
                year +
                "-" +
                month +
                "-" +
                day +
                " " +
                hour +
                ":" +
                minutes +
                ":" +
                seconds
            );
        },
    },
};
</script>

<style scoped>
.group-card {
    cursor: pointer;
    user-select: none;

    background-color: var(--background-color);

    width: 15vw;
    height: 15vw;
    /*border: #ffa88e solid 5px;*/
    border-style: solid;
    border-width: 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    border-radius: 1.5rem;

    box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 15px 3px var(--shadow-color);

    transition: 0.2s ease;

    margin: 2%;
}

.group-card:hover {
    transform: scale(1.05);
}

.group-card-icon {
    width: 50%;
    height: 50%;
    /*background-color: var(--primary);*/
    color: white;
    font-size: 3rem;
    font-weight: 600;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 100%;
    order: 2;
    margin-top: 0;
}

.group-card-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--header-color);
    order: 3;
    margin-top: 10%;
}

.group-card-menu {
    font-size: 1.5rem;
    align-self: flex-end;
    order: 1;
    color: var(--font-color);
    margin-top: 0.8vh;
    margin-right: 1.5vh;
    padding: 2%;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0;
}

.fade-up-enter-active,
.fade-up-leave-active {
    transition: all .5s ease;
}

.fade-up-enter, .fade-up-leave-to /* .fade-leave-active below version 2.1.8 */
{
    transform: translateY(50%);
    opacity: 0;
}

@media (max-width: 1200px) {
    .group-card {
        width: 18vw;
        height: 18vw;
    }

    .group-card-name {
        font-size: 1rem;
        margin-top: 3%;
    }

    .group-card-icon {
        font-size: 2.5rem;
    }

    .group-card-menu {
        margin-top: 0.4vh;
        margin-right: 0.8vh;
    }
}

@media (max-width: 576px) {
    .group-card {
        width: 90vw;
        height: 20vw;
        flex-direction: row;
        justify-content: flex-start;

        box-shadow: 1px 0px 8px 3px var(--shadow-color);
        -webkit-box-shadow: 1px 0px 8px 3px var(--shadow-color);
        -moz-box-shadow: 1px 0px 8px 3px var(--shadow-color);
        /* border: #ffa88e solid 4px; */
        border-style: solid;
        border-width: 4px;

        -webkit-tap-highlight-color: transparent;
    }

    .group-card-icon {
        order: 0;
        width: 16.66%;
        height: 80%;
        font-size: 2rem;
        margin: 10px;
    }

    .group-card-name {
        order: 0;
        font-size: 1.3rem;
        margin-top: 0;
    }

    .group-card-menu {
        order: 0;
        display: block;
        margin-left: auto;
        align-self: center;
        padding: 12px;
        margin-right: 0;
        margin-top: 0;
    }
}
</style>
