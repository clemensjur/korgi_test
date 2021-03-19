<template>
    <div id="context-menu" class="no-select" :class="{ active: active }">
        <div class="option warn">
            <p>Mitglied entfernen</p>
            <div class="option-icon">
            <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="option">
            <p>Administrator ernennen</p>
            <div class="option-icon"><i class="fas fa-crown"></i></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UserContextMenu",
    props: {
        bus: Object
    },
    data() {
        return {
            active: false
        }
    }
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
    right: 8%;
    cursor: pointer;
    width: fit-content;
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
}

p {
    margin-right: 1rem;
}

.option-icon {
    width: 8%;
    text-align: center;
}

</style>
