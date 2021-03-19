<template>
    <div id="container">
        <div id="member" @mouseleave="showMenu=false">
            <div id="name">
                <p class="role" v-if="member.isAdmin">Admin</p>
                <p>{{member.name}}</p>
            </div>
            <div id="menu" @click="showMenu=!showMenu">
                <i class="fas fa-chevron-right" :class="{active: showMenu}"></i>
            </div>
            <transition name="fade">
                <user-context-menu v-if="showMenu"/>
            </transition>
        </div>
        <div id="line"/>
    </div>
</template>

<script>
import Vue from "vue";
import UserContextMenu from "@/Pages/Group/UserContextMenu";
export default {
    name: "member",
    components: {UserContextMenu},
    props: {
        member: Object,
    },
    data() {
        return {
            showMenu: false,
        }
    }
}
</script>

<style scoped>

#member {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    position: relative;
}

.active {
    transform: rotate(180deg);
    transition: 0.25s;
}

#name {
    display: flex;
    flex-direction: column;
    justify-content: center;
    white-space: nowrap;
}

#menu {
    font-size: 1.5rem;
    height: fit-content;
    cursor: pointer;
    transition: 0.25s;
}

#container {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    width: 100%;

    margin-bottom: 1.5vh;
    margin-top: 1vh;
}

#line {
    margin-top: 0.3vh;
    border-top: 2px solid var(--background-color-alternate);
    height: 1px;
    width: 100%;
    flex: 1;
}

.role {
    font-size: 0.8rem;
    font-weight: 900;
    color: var(--warn);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0;
}

</style>
