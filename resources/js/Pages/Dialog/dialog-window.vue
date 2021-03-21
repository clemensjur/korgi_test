<template>
    <transition name="fade">
        <div class="dialog-background" v-show="isOpen" @click.self="close" @keypress.esc="close">
            <div class="dialog-window" v-show="isOpen">
                <h1 class="dialog-title">{{ title }}</h1>
                <slot></slot>
                <div class="buttons" :class="{'center': infoOnly}">
                    <div class="btn warn-background" @click="close">
                        <p>Abbrechen</p>
                        <i class="fas fa-times"/>
                    </div>
                    <div class="btn secondary-background" :class="isValid ? '' : 'disabled'" @click="submit" v-if="!infoOnly">
                        <p>Bestätigen</p>
                        <i class="fas fa-check"/>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "dialog-window",
    data() {
        return {
            isOpen: undefined,
            isValid: false,
            content: undefined,
            manuallyOpened: false
        }
    },
    props: {
        title: String,
        bus: Object,
        infoOnly: Boolean
    },
    created() {
        this.bus.$on('open', this.open);
        this.bus.$on('close', this.close);
        this.bus.$on('validate', this.validate);
        this.bus.$on('submit', this.submit);
    },
    methods: {
        open() {
            this.isOpen = true;
            this.manuallyOpened = true;
        },
        close() {
            this.isOpen = false;
        },
        validate(content) {
            if (
                content &&
                !(
                    Object.values(content).includes(undefined) ||
                    Object.values(content).includes("")
                )
            ) {
                this.isValid = true;
                this.content = content;
            } else {
                this.isValid = false;
            }
        },
        submit() {
            if (this.isValid) {
                this.$emit('submit', this.content)
                this.close();
            }
        },
    }
}
</script>

<style scoped>

.dialog-background {
    position: fixed;
    top: 0;
    left: 0;

    z-index: 100;
    width: 100vw;
    height: 100vh;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    backdrop-filter: blur(2px) brightness(70%) opacity(100);
}

.dialog-title {
    color: #FFCB8E;
    font-size: 1.5rem;
    font-weight: 700;

    margin-bottom: 10%;
}

.fade-enter-active, .fade-leave-active {
    transition: backdrop-filter .2s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    backdrop-filter: blur(2px) brightness(70%) opacity(0);
}

.fade-enter-active .dialog-window, .fade-leave-active .dialog-window {
    transition: .2s ease;
}

.fade-enter .dialog-window, .fade-leave-to .dialog-window/* .fade-leave-active below version 2.1.8 */
{
    transform: scale(0);
    opacity: 0;
}

.buttons {
    margin-top: 10%;
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
}

.buttons.center {
    justify-content: center;
}

.buttons .btn {
    width: 45%;
    flex-grow: 0;
}

.dialog-window {
    background-color: var(--background-color);
    width: 30vw;
    padding: 2.5%;
    border-radius: 3rem;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    transform: scale(1);
}

@media (max-width: 1200px) {
    .dialog-window {
        width: 55vw;
    }
    .dialog-background {
        z-index: 150;
    }
}

@media (max-width: 576px) {
    .dialog-window {
        width: 90vw;
        border-radius: 2rem;
        padding: 4%;
    }
}

</style>
