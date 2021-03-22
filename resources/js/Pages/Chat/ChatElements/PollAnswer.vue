<template>
    <div class="answer" :style="cssVars"  :class="{answered: isAnswered, selected: isSelected}" @click="$emit('click')">
        <p>{{message.message.answers[answerKey]}}</p>
        <transition name="fade" mode="out-in">
            <p v-if="isAnswered || isOwn">{{delayedPercentage || 0}}%</p>
        </transition>
    </div>
</template>

<script>
export default {
    name: "poll-answer",
    data() {
        return {
            delayedPercentage: 0,
        }
    },
    props: {
        answerKey: String,
        message: Object
    },
    computed: {
        totalAnswers() {
            return Object.keys(this.message.message.results).length
        },
        isAnswered() {
            return Object.keys(this.message.message.results).includes(this.$store.state.user.uuid);
        },
        isSelected() {
            return Object.keys(this.message.message.results).includes(this.$store.state.user.uuid) ? this.message.message.results[this.$store.state.user.uuid] === this.answerKey : false;
        },
        percentage() {
            return Math.round((Object.values(this.message.message.results).filter(value => value === this.answerKey).length / this.totalAnswers) * 100);
        },
        cssVars () {
            return this.isSelected ? {'--percentage': this.delayedPercentage + '%', '--fill-color': 'var(--answered-poll-color)'} : {'--percentage': this.delayedPercentage + '%', '--fill-color': 'var(--primary)'}
        },
        isOwn() {
            return this.message.message.user.uuid === this.$store.state.pubnub.getUUID()
        },
    },
    watch: {
        percentage() {
            if (this.isAnswered || this.isOwn) {
                this.updateDelayedPercentage();
            }
        },
        isAnswered() {
            if (this.percentage === 100) {
                this.updateDelayedPercentage();
            }
        }
    },
    mounted() {
        if (this.isAnswered) {
            this.delayedPercentage = this.percentage;
        }
    },
    methods: {
        updateDelayedPercentage() {
            let percentagePlaceholder = Math.round((Object.values(this.message.message.results).filter(value => value === this.answerKey).length / this.totalAnswers) * 100);
            let delay = 200 / Math.abs(percentagePlaceholder-this.delayedPercentage);

            this.matchDelayedPercentage(percentagePlaceholder, delay);
        },
        matchDelayedPercentage(percentage, delay) {
            if (this.delayedPercentage + 1 <= percentage) {
                this.delayedPercentage++;
                setTimeout(() => {
                    this.matchDelayedPercentage(percentage, delay)
                }, delay);
            } else if (this.delayedPercentage - 1 >= percentage) {
                this.delayedPercentage--;
                setTimeout(() => {
                    this.matchDelayedPercentage(percentage, delay)
                }, delay);
            }
        }
    }
}
</script>

<style scoped>

.right .answer {
    background-color: var(--background-color);
}

.message .answer {
    height: 2.5rem;
    width: 100%;
    border: 2px solid var(--fill-color);
    margin-bottom: 0.25rem;
    margin-top: 0.25rem;
    border-radius: 12px;
    padding-left: 12px;
    padding-right: 12px;
    font-weight: 600;
    cursor: pointer;
    user-select: none;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;

    transition: 0.2s ease;
}

.answer.answered {
    cursor: default;
    pointer-events: none;

    background: linear-gradient(to right, var(--fill-color) var(--percentage), var(--background-color) 0);
}

.own .answer {
    background: linear-gradient(to right, var(--fill-color) var(--percentage), var(--background-color) 0);
}

.answer:hover {
    border-color: var(--primary-darker);
    background-color: var(--background-color-alternate);
}
</style>
