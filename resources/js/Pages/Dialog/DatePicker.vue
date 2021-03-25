<template>
    <div class="date-picker">
        <div class="month-selector">
            <div class="round-btn mini secondary-background" @click="previousMonth">
                <i class="fas fa-chevron-left"></i>
            </div>
            <h1 class="month">{{ monthArray[month] }} {{ year }}</h1>
            <div class="round-btn mini secondary-background" @click="nextMonth">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
        <div class="dates">
            <div class="date">SO</div>
            <div class="date">MO</div>
            <div class="date">DI</div>
            <div class="date">MI</div>
            <div class="date">DO</div>
            <div class="date">FR</div>
            <div class="date">SA</div>
            <div class="date" v-for="d in dates" :class="calculateDateClasses(d)" @click.shift="shiftToggleDate(d)"
                 @click.exact="toggleDate(d)" :key="d.getTime()">
                {{ d.getDate() }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DatePicker",
    props: {
        multiple: Boolean
    },
    data() {
        return {
            selectedDates: [],
            shiftedDate: undefined,
            monthArray: ['Jänner', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            year: new Date().getFullYear(),
            month: new Date().getMonth(),
        }
    },
    watch: {
        selectedDates() {
            this.$emit('input', {
                dates: this.selectedDates
            });
        }
    },
    computed: {
        date() {
            return new Date(this.year, this.month, 1, 0, 0, 0, 0);
        },
        dates() {
            let date = this.date;
            date.setDate(this.date.getDate() - this.date.getDay());
            let dates = [];
            while (date.getMonth() < (this.month === 0 && date.getMonth() === 11 ? 12 : this.month) + 1 && (date.getFullYear() < this.year + 1)) {
                dates.push(new Date(date));
                date.setDate(date.getDate() + 1);
            }
            return dates;
        }
    },
    methods: {
        nextMonth() {
            if (this.month < 11) {
                this.month++;
            } else {
                this.month = 0;
                this.year++;
            }
            this.shiftedDate = undefined;
        },
        previousMonth() {
            if (this.month > 0) {
                this.month--;
            } else {
                this.month = 11;
                this.year--;
            }
            this.shiftedDate = undefined;
        },
        toggleDate(date) {
            if (this.selectedDates.find(d => {
                return d.toString() === date.toString();
            })) {
                this.selectedDates.splice(
                    this.selectedDates.indexOf(
                        this.selectedDates.find(
                            d => {
                                return d.toString() === date.toString();
                            }
                        )
                    ),
                    1);
            } else {
                if (!this.multiple) {
                    this.selectedDates = [];
                }
                this.selectedDates.push(date);
            }
            // Sorts selectedDates
            this.selectedDates.sort((d1, d2) => {
                return d1.getTime() < d2.getTime() ? -1 : d1.getTime() === d2.getTime() ? 0 : 1;
            });
            this.$forceUpdate();
        },
        shiftToggleDate(date) {
            if (this.multiple) {
                if (this.shiftedDate) {
                    // Saves all dates between the shiftedDate and current date in shiftedDates
                    let shiftedDates = this.dates.filter(d => {
                        return (d.getTime() >= date.getTime() && d.getTime() <= this.shiftedDate.getTime()) || (d.getTime() <= date.getTime() && d.getTime() >= this.shiftedDate.getTime())
                    })

                    // Checks if any shiftedDates weren't previously selected
                    if (shiftedDates.find(d => {
                        return !this.selectedDates.find(da => {
                            return da.getTime() === d.getTime();
                        });
                    })) {
                        // Saves all non selected shiftedDates in selectedDates
                        shiftedDates.forEach(d => {
                            if (!this.selectedDates.find(da => {
                                return da.getTime() === d.getTime();
                            })) this.selectedDates.push(d);
                        });
                    } else {
                        // Removes all selected shiftedDates from selectedDates
                        this.selectedDates.splice((
                            this.shiftedDate < date ?
                                this.selectedDates.indexOf(
                                    this.selectedDates.find(d => {
                                        return d.getTime() === this.shiftedDate.getTime()
                                    })
                                )
                                :
                                this.selectedDates.indexOf(
                                    this.selectedDates.find(d => {
                                        return d.getTime() === date.getTime()
                                    })
                                )
                        ), shiftedDates.length);
                    }

                    // Sorts selectedDates
                    this.selectedDates.sort((d1, d2) => {
                        return d1.getTime() < d2.getTime() ? -1 : d1.getTime() === d2.getTime() ? 0 : 1;
                    });
                    this.$forceUpdate();
                    this.shiftedDate = undefined;
                } else {
                    this.shiftedDate = date;
                }
            } else {
                this.toggleDate(date);
            }
        },
        calculateDateClasses(date) {
            if (date.getMonth() !== this.month || date.getFullYear() !== this.year) {
                return 'disabled';
            } else if (this.selectedDates.find(d => {
                return d.toString() === date.toString();
            })) {
                return 'selected';
            }
            return '';
        }
    }
}
</script>

<style scoped>
.date-picker {
    width: 16.1rem;
    height: 16.1rem;
}

.month-selector {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.month {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    text-align: center;
    width: 9rem;
    height: 1.2rem;
    color: #FFCB8E;;
    font-weight: 600;
}

.dates {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    color: var(--font-color);
}

.date {
    border-radius: 0.9rem;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    cursor: pointer;
    width: 1.8rem;
    height: 1.8rem;
    margin: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s ease;
}

.date.selected {
    color: white;
    background-color: #FFCB8E;
}

.date.disabled {
    transition: none;
    color: darkgray;
    pointer-events: none;
}
</style>
