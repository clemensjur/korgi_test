<template>
    <transition name="fade-box">
        <div id="popup" v-if="message">
            {{message}}
        </div>
    </transition>
</template>

<script>
export default {
    name: "Popup",
    computed: {
        message() {
            return this.$store.getters.getPopupMessage
        }
    }
}
</script>

<style scoped>
#popup {
    z-index: 100;
    position: fixed;
    bottom: 6rem;
    right: 1.5rem;

    color: var(--white);
    background-color: var(--primary);
    padding: 1.5rem;
    border-radius: 1.5rem;

    box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 15px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 15px 3px var(--shadow-color);
}

.fade-box-enter-active,
.fade-box-leave-active {
    transition: all 0.2s ease;
}

.fade-box-enter, .fade-box-leave-to /* .fade-leave-active below version 2.1.8 */
{
    transform: scale(0);
    opacity: 0;
}
</style>
