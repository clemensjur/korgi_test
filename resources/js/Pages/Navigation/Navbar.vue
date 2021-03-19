<template>
    <div id="navbar">
        <img id="logo" src="/images/korgi_red.svg" alt="KORGI" v-if="!showArrow">
        <div class="arrow" v-if="showArrow">
            <inertia-link :href="route('groups.show')"><i class="fas fa-arrow-left"></i></inertia-link>
        </div>
        <div class="title no-select" @click="toggleGroupInfo">{{currentPage}}</div>
        <a id="burger" href="#" @click="toggleMenu" v-bind:class="{active: isActive}"><span/></a>
    </div>
</template>

<script>
export default {
    name: "Navbar",
    props: {
        bus: Object
    },
    methods: {
        toggleMenu() {
            this.bus.$emit("toggleMenu");
        },
        toggleGroupInfo() {
            if (this.showArrow) {
                this.$store.commit("setShowGroupInfo", {'showGroupInfo': !this.$store.getters.getShowGroupInfo});
            }
        }
    },
    created() {
        this.bus.$on("toggleMenu", () => {this.isActive = !this.isActive})
    },
    data() {
        return {
            isActive: false
        }
    },
    computed: {
        currentPage() {
            return this.$store.getters.getCurrentPage
        },
        showArrow() {
            return this.$store.getters.getShowArrow
        }
    }
}
</script>

<style scoped>

#navbar {
    padding: 3% 3% 3% 3%; /*evtl. beim ersten 24px wegen notch*/
    display: none;
    width: 100%;
    height: 20vw;
    z-index: 120;
    background-color: var(--primary);
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
}

#logo {
    display: block;
    flex-grow: 0;
    width: 16%;
    height: 100%;
}

div.title {
    color: white;
    margin-left: 10px;
    font-size: 1.3rem;
}


#burger {
    margin-left: auto;
    display: block;
    padding: 10px;
}

#burger span {
    position: relative;
    display: block;
    z-index: 5;
    width: 30px;
    height: 4px;
    background: #ffffff;
    transition: all 0.2s ease-in-out;
    border-radius: 3px;
}
#burger span:before, #burger span:after {
    position: absolute;
    background: #ffffff;
    content: "";
    width: 30px;
    height: 4px;
    transition: all 0.2s ease-in-out;
    border-radius: 3px;
}
#burger span:before {
    top: -8px;
}
#burger span:after {
    top: 8px;
}
#burger.active span {
    background: transparent;
}
#burger.active span:before {
    transform: rotate(45deg) translate(5px, 6px);
    background: #ffffff;
}
#burger.active span:after {
    transform: rotate(-45deg) translate(5px, -6px);
    background: #ffffff;
}
.fa-arrow-left {
    font-size: 2.2rem;
    color: white;
}
.arrow {
    width: 16%;
    aspect-ratio: 1/1;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

@media (max-width: 576px) {
    #navbar {
        display: flex;
    }
}

</style>
