<template>
    <div id="wrapper">
        <store-initializer :user="user" :groups="groups"/>
        <navigation :bus="bus" :user="$page.props.user.name"/>
        <navbar :bus="bus"/>
        <main @click="isActive ? bus.$emit('toggleMenu') : null" :class="{'blurred': isActive}">
            <div class="blur"></div>
            <slot></slot>
        </main>
    </div>
</template>

<script>
import Vue from "vue";
import Navigation from "@/Pages/Navigation/Navigation.vue";
import Navbar from "@/Pages/Navigation/Navbar.vue";
import StoreInitializer from "@/Pages/store-initializer";

export default {
    components: {StoreInitializer, Navigation, Navbar},
    props: {
        user: Object,
        groups: Array,
    },
    data() {
        return {
            bus: new Vue(),
            isActive: false,
        };
    },
    created() {
        this.bus.$on("toggleMenu", () => {this.isActive = !this.isActive})
    }
};
</script>

<style>
#wrapper {
    position: relative;
    width: 100vw;
    height: 100vh;
    display: flex;
    flex-direction: row;
    overflow: hidden;
}

.blur {
    top: 0;
    position: fixed;

    z-index: 50;
    width: 100vw;
    height: 100vh;
    backdrop-filter: blur(2px) brightness(70%) opacity(100);
    opacity: 0;
    pointer-events: none;
}

main.blurred .blur {
    opacity: 1;
    pointer-events: all;
}

main {
    width: 80vw;
    height: 100vh;
    overflow: hidden;
}

.blurred > div {
    overflow: hidden;
    pointer-events: none;
}

@media (max-width: 576px) {
    #wrapper {
        flex-direction: column;
    }

    main {
        width: 100vw;
    }
}
</style>
