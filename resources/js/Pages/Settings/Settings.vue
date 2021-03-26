<template xmlns="http://www.w3.org/1999/html">
    <page-layout :user="user" :groups="groups">
        <div id="settings">
            <!--navigation :user="username" :bus="bus"/-->
            <div class="settings-header">
                <h1 class="title">Einstellungen</h1>
            </div>
            <div id="item-container">
                <div class="item">
                    <div class="subject">Benutzer</div>
                    <div class="content">
                        <div id="user">
                            <div class="flex-item-container">
                                <span class="small-headline">Name</span>
                                <input
                                    class="alternate-input"
                                    :class="{ disabled: !nameInputActive }"
                                    type="text"
                                    name="username"
                                    value="username"
                                    placeholder="Username"
                                    v-model="username"
                                />
                                <div class="user-settings-buttons">
                                    <Transition name="fade">
                                        <div
                                            class="round-btn warn-background"
                                            v-if="nameInputActive"
                                            @click="cancelUsername"
                                        >
                                            <i class="fas fa-times"/>
                                        </div>

                                    </Transition>


                                    <Transition name="fade">
                                        <div
                                            class="round-btn secondary-background"
                                            v-if="nameInputActive"
                                            @click="changeUsername"
                                        >
                                            <i class="fas fa-check"/>
                                        </div>

                                        <div
                                            class="round-btn primary-background"
                                            v-if="!nameInputActive"
                                            @click="nameInputActive = true;emailInputActive = false;"
                                        >
                                            <i class="fas fa-pen"/>
                                        </div>
                                    </Transition>
                                </div>
                            </div>
                            <div class="flex-item-container">
                                <span class="small-headline">E-Mail</span>
                                <input
                                    class="alternate-input"
                                    :class="{ disabled: !emailInputActive }"
                                    type="email"
                                    name="email"
                                    value="email"
                                    placeholder="E-Mail"
                                    v-model="usermail"
                                />
                                <div class="user-settings-buttons">
                                    <Transition name="fade">
                                        <div
                                            class="round-btn warn-background"
                                            v-if="emailInputActive"
                                            @click="cancelUsermail"
                                        >
                                            <i class="fas fa-times"/>
                                        </div>
                                    </Transition>

                                    <Transition name="fade">
                                        <div
                                            class="round-btn secondary-background"
                                            v-if="emailInputActive"
                                            @click="changeUsermail"
                                        >
                                            <i class="fas fa-check"/>
                                        </div>

                                        <div
                                            class="round-btn primary-background"
                                            v-if="!emailInputActive"
                                            @click="
                        emailInputActive = true;
                        nameInputActive = false;
                      "
                                        >
                                            <i class="fas fa-pen"/>
                                        </div>
                                    </Transition>
                                </div>
                            </div>
                            <div class="link-container">
                                <button @click="changePassword">Passwort ändern</button>
                                <a @click="deleteAccount" class="warn">Account löschen</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="subject">Privatsphäre</div>
                    <div class="content">
                        <div id="privacy">
              <span class="small-headline"
              >Alle gesammelten Daten anfordern</span
              >
                            <br/>
                            Chatnachrichten, Nutzerdaten, Verschickte Dateien, ...
                            <br/>
                            <br/>
                            Der Vorgang kann einzige Zeit in Anspruch nehmen. Nach Abschluss
                            erhältst du eine E-Mail mit den gesammelten Daten.
                            <br/>
                        </div>
                        <a class="mail"
                           :href="'mailto:contact@korgi.at?subject=Daten anfordern&body=E-Mail: ' + this.usermail + ' Username: ' + this.username">
                            <div class="btn primary-background">Daten anfordern</div>
                        </a>
                    </div>
                </div>

                <div class="item">
                    <div class="subject">Benachrichtigungen</div>
                    <div class="content">
                        <div id="messages">
                            <label class="checkbox-container">
                                E-Mail Benachrichtigungen
                                <input type="checkbox" name="email-msg" value="email-msg"/>
                                <span class="checkbox"></span>
                            </label>
                            <label class="checkbox-container">
                                Push Benachrichtigungen
                                <input type="checkbox" name="push-msg" value="push-msg"/>
                                <span class="checkbox"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="subject">Darstellung</div>
                    <div class="content">
                        <div id="presentation">
                            <label class="checkbox-container"
                            >Heller Modus
                                <input
                                    type="radio"
                                    :checked="!darkmode"
                                    @change="toggleDarkmode"
                                    name="Farbmodus"
                                    value="lightmode"
                                />
                                <span class="radio"></span>
                            </label>
                            <label class="checkbox-container"
                            >Dunkler Modus
                                <input
                                    type="radio"
                                    :checked="darkmode"
                                    @change="toggleDarkmode"
                                    name="Farbmodus"
                                    value="darkmode"
                                />
                                <span class="radio"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </page-layout>
</template>

<script>
import Vue from "vue";

import PageLayout from "@/Layouts/PageLayout.vue";
import Label from "@/Jetstream/Label";
import StoreInitializer from "@/Pages/store-initializer";

import axios from "axios";

export default {
    name: "Settings",
    components: {
        StoreInitializer,
        PageLayout,
        Label,
    },
    props: {
        user: Object,
        groups: Array,
    },
    data() {
        return {
            username: this.user.name,
            usermail: this.user.email,
            bus: new Vue(),
            nameInputActive: false,
            emailInputActive: false,
        };
    },
    created() {
        this.$store.commit("setCurrentPage", {page: "Einstellungen"});
        this.$store.commit("setShowArrow", {showArrow: false});
    },
    computed: {
        darkmode: function () {
            return this.$store.state.user.settings.darkmode;
        },
    },
    watch: {
        darkmode: function (darkmode) {
            let root = document.documentElement;
            let isDarkmode = root.classList.contains("darkmode");
            if (isDarkmode && !darkmode) {
                root.classList.remove("darkmode");
            } else if (!isDarkmode && darkmode) {
                root.classList.add("darkmode");
            }
        },
    },
    methods: {
        toggleDarkmode() {
            console.log(this.darkmode)
            this.darkmode = !this.darkmode;
            axios.patch(route("user.update.theme"), {
                theme: this.darkmode,
            }).then((res) => {
                console.log(res);
                this.$inertia.visit(route("settings.show"));
            });
        },
        deleteAccount() {
            axios.post(route("user.delete")).then((response) => {
                // this.$inertia.visit(route("home"));
            });
        },
        changePassword() {
            axios.post(route("logout")).then((res) => {
                console.log(res);
                this.$inertia.visit(route("password.request"));
            });
        },
        updateName() {
            this.nameInputActive = false;
        },
        cancelUsername() {
            this.nameInputActive = false;
            this.username = this.user.name;
        },
        changeUsername() {
            this.nameInputActive = false;
            axios
                .patch(route("user.update.name"), {
                    name: this.username,
                })
                .then((res) => {
                    this.$inertia.visit(route("settings.show"));
                    this.$store.commit("setPopupMessage", {message: "Benutzername geändert!"});
                    setTimeout(() => {
                        this.$store.commit("setPopupMessage", {message: ""});
                    }, 1500)
                    console.log(res)
                });
        },
        changeUsermail() {
            this.emailInputActive = false;
            axios
                .patch(route("user.update.mail"), {
                    email: this.usermail,
                })
                .then((res) => {
                    console.log(res);
                    this.$inertia.visit(route("settings.show"));
                    this.$store.commit("setPopupMessage", {message: "E-Mail geändert!"});
                    setTimeout(() => {
                        this.$store.commit("setPopupMessage", {message: ""});
                    }, 1500)
                    /*
                    axios.post(route("logout")).then((res) => {
                      console.log(res);
                      this.$inertia.visit(route("verification.notice"));
                    });
                    */
                });
        },
        cancelUsermail() {
            this.emailInputActive = false;
            this.usermail = this.user.email
        },
    }
};
</script>

<style scoped>
#settings {
    width: 100%;
    height: 100%;
    background-color: var(--background-color-alternate);
    overflow: auto;
    display: flex;
    flex-direction: column;
}

.settings-header {
    padding: 4vh;
}

.settings-input {
    background-color: var(--light-grey);
    border: 2px solid var(--semi-dark-grey);
    border-radius: 8px;
    padding: 6px;
    color: var(--black);
}

.settings-input:focus {
    outline: 0;
}

::placeholder {
    color: var(--font-color);
}

#item-container {
    margin: auto;
    display: flex;
    position: relative;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-around;
    width: 80%;
}

.subject {
    margin: 2%;
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--font-color);
}

.content {
    margin: 2%;
    color: var(--font-color);
    font-size: 1rem;
    height: 100%;
}

.mail {
    text-decoration: none;
    margin: 0;
    padding: 0;
}

.item {
    display: flex;
    flex-direction: column;
    width: 45%;
    border-radius: 1rem;
    background-color: var(--background-color);

    box-shadow: 1px 0px 8px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 8px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 8px 3px var(--shadow-color);

    margin-bottom: 6%;
    padding: 1%;
}

.flex-item-container {
    margin-left: 4%;
    margin-bottom: 4%;
    display: flex;
    align-items: center;
    position: relative;
    padding-left: 35px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

#messages .flex-item-container,
#presentation .flex-item-container {
    cursor: pointer;
}

.checkbox-container {
    margin-left: 4%;
    margin-bottom: 4%;
}

.link-container {
    margin-left: 4%;
    margin-bottom: 4%;
    display: flex;
    align-items: center;
    cursor: pointer;
}

.link-container button {
    text-decoration: underline;
    margin-right: 4%;
    margin-top: 5%;
}

.link-container button:focus {
    outline: 0;
    border: none;
}

#messages .flex-item-container input,
#presentation .flex-item-container input {
    opacity: 0;
    position: absolute;
    cursor: pointer;
    height: 0;
    width: 0;
}

.btn {
    width: 40%;
    justify-content: center;
    margin-top: 4%;
}

#privacy {
    margin-bottom: 4%;
}

.small-headline {
    font-weight: 600;
    font-size: 1.1rem;
}

a {
    text-decoration: underline;
    margin-right: 4%;
    margin-top: 5%;
}

.warn {
    color: var(--warn);
}

#user .flex-item-container {
    padding: 0;
    justify-content: space-between;
    flex-wrap: wrap;
}

.user-settings-buttons {
    display: flex;
    flex-direction: row;
    width: 7rem;
    justify-content: flex-end;
}

.user-settings-buttons .round-btn {
    margin-left: 0.5rem;
}

/*Transitions*/

.fade-enter-active,
.fade-leave-active {
    transition: all 0.15s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0;
}

@media (max-width: 1200px) {
    #item-container {
        flex-direction: column;
    }

    .item {
        width: 100%;
        padding: 2%;
    }
}

@media (max-width: 576px) {
    .title {
        display: none;
    }

    #item-container {
        flex-direction: column;
        width: 90%;
    }

    .item {
        width: 100%;
        padding: 2%;
    }

    .btn {
        width: 70%;
    }

    #user .small-headline {
        flex-basis: 100%;
    }

    .settings-input {
        width: 70%;
    }

    .alternate-input {
        width: 60%;
    }

    a {
        margin-right: 3%;
    }

    .settings-header {
        display: none;
    }

    #settings {
        padding-top: 3vh;
    }
}

@media (min-width: 576px) {
    #settings::-webkit-scrollbar {
        margin-left: -1rem;
        width: 1rem;
    }

    #settings::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 0.5rem;
    }

    #settings::-webkit-scrollbar-thumb {
        background-color: #ffa88e;
        border-radius: 0.5rem;
    }
}
</style>
