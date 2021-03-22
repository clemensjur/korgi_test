<template>
    <div id="context-menu" class="no-select" :class="{ active: active }">
        <div v-if="hasAdminPermissions && isEmpty" class="option warn" @click="$emit('delete')">
            <p>Gruppe löschen</p>
            <i class="fas fa-trash-alt"></i>
        </div>
        <div v-if="(!hasAdminPermissions) || (!isEmpty)" class="option warn" @click="$emit('leave')">
            <p>Gruppe verlassen</p>
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <div class="option" @click="$emit('changeColor')">
            <p>Farbe ändern</p>
            <i class="fas fa-palette"></i>
        </div>
    </div>
</template>

<script>
export default {
    name: "ContextMenu",
    props: {
        bus: Object,
        hasAdminPermissions: Boolean,
        isEmpty: Boolean
    },
    data() {
        return {
            active: false,
        }
    },
    // created() {
    //     this.bus.$on("showMenu", () => {
    //         this.toggleActive();
    //     });
    //     this.bus.$on("hideMenu", () => {
    //         this.hideMenu();
    //     });
    // },
    // methods: {
    //     toggleActive() {
    //         this.active = !this.active;
    //     },
    //     hideMenu() {
    //         this.active= false;
    //     }
    // }
}
</script>

<style scoped>
#context-menu {
    background-color: var(--background-color);
    box-shadow: 1px 0px 10px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 10px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 10px 3px var(--shadow-color);
    display: flex;
    flex-direction: column;
    position: absolute;
    border-radius: 0.5rem;
    right: 7%;
    top: 15%;
}

.option {
    padding: 10px;
    color: var(--font-color);
    border-radius: 0.5rem;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
}
.option:hover {
    background-color: var(--background-color-alternate);
}

.warn {
    color: var(--warn)
}

i {
    margin-left: 1rem;
}

@media (max-width: 1200px) {
    #context-menu {
        top: 18%;
    }
}

@media (max-width: 576px) {
    #context-menu {
        right: 14%;
    }
}

</style>
