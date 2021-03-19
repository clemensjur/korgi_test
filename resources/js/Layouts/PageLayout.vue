<template>
    <div id="wrapper">
        <store-initializer :user="user" :groups="groups"/>
        <navigation :bus="bus" :user="$page.props.user.name"/>
        <navbar :bus="bus"/>
        <main @click.prevent="isActive ? bus.$emit('toggleMenu') : null" :class="{'blurred': isActive}">
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
    width: 100vw;
    height: 100vh;
    display: flex;
    flex-direction: row;
    overflow: hidden;
}

main.blurred {
    filter: blur(2px) brightness(70%) opacity(100);
    overflow: hidden;
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
