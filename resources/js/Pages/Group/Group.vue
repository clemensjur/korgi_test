<template>
    <page-layout :user="user" :groups="groups">
        <div id="group">
            <div id="group-content" :class="{'active': active}">
                <div id="group-header">
                    <div class="row">
                        <div style="display: flex; align-items: center; flex-grow: 1">
                            <inertia-link
                                :href="route('groups.show')"
                                class="round-btn secondary-background"
                            ><i class="fas fa-arrow-left"></i
                            ></inertia-link>
                            <div
                                style="margin-left: 3vh"
                                class="headline"
                            >
                                {{ group.name }}
                            </div>
                        </div>


                        <!--inertia-link
                            :href="route('group.users', { url: this.group.url })"
                            style="margin-left: 2%"
                            class="headline"
                        >
                            Users
                        </inertia-link>

                        <inertia-link
                            :href="route('group.files.show', { url: this.group.url })"
                            style="margin-left: 2%"
                            class="headline"
                        >
                            Files
                        </inertia-link-->
                        <div class="btn primary-background" @click="toggleGroupInfo">
                            <p>Gruppeninfo</p>
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>
                    <div id="chat-selection" class="no-select">
                        <button
                            as="button"
                            class="chat-link left"
                            :class="{'current': !current}"
                            v-on:click="switchToGeneral"
                        >
                            Allgemein
                        </button>

                        <button
                            class="chat-link right"
                            :class="{'current': current}"
                            v-on:click="switchToImportant"
                        >
                            Wichtig
                        </button>
                    </div>
                </div>

                <div class="swiper-container">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <div class="swiper-slide">
                            <Chat
                                :group="group"
                                :chat="chats['allgemein']"
                            />
                        </div>
                        <div class="swiper-slide">
                            <Chat
                                :group="group"
                                :chat="chats['wichtig']"
                            />
                        </div>
                    </div>
                </div>

                <div id="input-group">
                    <dialog-window
                        :bus="fileInputBus"
                        title="Datei senden"
                        @submit="publishFile"
                    >
                        <dialog-content-send-file :bus="fileInputBus"/>
                    </dialog-window>
                    <dialog-window
                        :bus="eventAnnouncementBus"
                        title="Termin bekannt geben"
                        @submit="publishEventAnnouncement"
                    >
                        <dialog-content-event-announcement :bus="eventAnnouncementBus"/>
                    </dialog-window>
                    <dialog-window
                        :bus="importantMessageBus"
                        title="Wichtige Nachricht senden"
                        @submit="publishImportantMessage"
                    >
                        <dialog-content-important-message :bus="importantMessageBus"/>
                    </dialog-window>
                    <dialog-window
                        :bus="pollBus"
                        title="Umfrage starten"
                        @submit="publishPoll"
                    >
                        <dialog-content-poll :bus="pollBus"/>
                    </dialog-window>
                    <transition name="fade-box">
                        <div
                            v-show="openSpecialMessages && current === 1"
                            class="special-messages-container"
                            @click="toggleSpecialMessages"
                        >
                            <div
                                class="btn primary-background"
                                v-on:click="pollBus.$emit('open')"
                            >
                                <p>Umfrage</p>
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div
                                class="btn primary-background"
                                v-on:click="eventAnnouncementBus.$emit('open')"
                            >
                                <p>Termin</p>
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div
                                class="btn primary-background"
                                v-on:click="importantMessageBus.$emit('open')"
                            >
                                <p>Wichtige Nachricht</p>
                                <i class="far fa-calendar-alt"/>
                            </div>
                            <div
                                class="btn primary-background"
                                v-on:click="fileInputBus.$emit('open')"
                            >
                                <p>Wichtige Datei</p>
                                <i class="fas fa-paperclip"/>
                            </div>
                        </div>
                    </transition>
                    <div
                        class="round-btn warn-background"
                        :class="hasAccess() ? '' : 'disabled'"
                        v-if="current === 0"
                        :disabled="!hasAccess()"
                        v-on:click="fileInputBus.$emit('open')"
                    >
                        <i class="fas fa-paperclip"/>
                    </div>
                    <div
                        class="round-btn warn-background"
                        :class="hasAccess() ? '' : 'disabled'"
                        v-if="current === 1"
                        :disabled="!hasAccess()"
                        v-on:click="toggleSpecialMessages"
                    >
                        <i class="fas fa-plus"/>
                    </div>
                    <!--<div>Groups: {{ Object.values($store.state.groups).length }}</div>-->
                    <input
                        type="text"
                        class="input message-input"
                        :class="hasAccess() ? '' : 'disabled'"
                        v-model="message"
                        @keypress.enter="publishMessage"
                        :placeholder="
          hasAccess()
            ? 'Nachricht'
            : 'Du darfst in diesem Chat leider keine Nachrichten schreiben!'
        "
                    />
                    <div
                        class="round-btn secondary-background"
                        :class="hasAccess() && message.replaceAll(' ', '').length ? '' : 'disabled'"
                        v-on:click="publishMessage"
                    >
                        <i class="fas fa-paper-plane"/>
                    </div>
                </div>
            </div>
        </div>
        <group-info :group="group" :bus="bus" :dialog-bus="inviteBus" :hasAdminPermissions="hasAdminPermissions"/>
        <dialog-window :title="'Einladungslink'" :bus="inviteBus" :info-only="true">
            <dialog-content-join-link :link="link"/>
        </dialog-window>
    </page-layout>
</template>

<script>
import Chat from "@/Pages/Chat/Chat";
import Navbar from "@/Pages/Navigation/Navbar";
import GroupInfo from "@/Pages/Group/GroupInfo";
import Vue from "vue";
import Navigation from "@/Pages/Navigation/Navigation";
import PageLayout from "@/Layouts/PageLayout";
import StoreInitializer from "@/Pages/store-initializer";

import Swiper from 'swiper';
import 'swiper/swiper.min.css';
import DialogWindow from "@/Pages/Dialog/dialog-window";
import DialogContentSendFile from "@/Pages/Dialog/dialog-content-send-file";
import DialogContentEventAnnouncement from "@/Pages/Dialog/dialog-content-event-announcement";
import DialogContentImportantMessage from "@/Pages/Dialog/dialog-content-important-message";
import DialogContentPoll from "@/Pages/Dialog/dialog-content-poll";
import axios from "axios";
import DialogContentJoinLink from "@/Pages/Dialog/dialog-content-join-link";

export default {
    name: "Group",
    components: {
        StoreInitializer,
        PageLayout,
        Navigation,
        Chat,
        Navbar,
        GroupInfo,
        DialogContentJoinLink,
        DialogContentImportantMessage,
        DialogContentPoll,
        DialogWindow,
        DialogContentSendFile,
        DialogContentEventAnnouncement,
    },
    props: {
        group: Object,
        groups: Array,
        user: Object,
    },
    data() {
        return {
            hasAdminPermissions: this.getAdminStatus(),
            type: "allgemein",
            typeDelayed: "allgemein",
            bus: new Vue(),
            chats: this.group.channels,
            current: 0,
            message: "",
            fileInputBus: new Vue(),
            eventAnnouncementBus: new Vue(),
            dateVotingBus: new Vue(),
            pollBus: new Vue(),
            importantMessageBus: new Vue(),
            openSpecialMessages: false,
            inviteBus: new Vue(),
            link: route("group.join.show", {uuid: this.group.uuid}),
        };
    },
    computed: {
        active() {
            return this.$store.getters.getShowGroupInfo
        }
    },
    mounted() {
        this.swiper = new Swiper('.swiper-container', {
            init:true,
            slidesPerView:'auto',
            direction: 'horizontal',
            loop: false,
            allowSlidePrev: false,
            allowTouchMove: window.matchMedia('(max-width: 576px)').matches,
            cssMode: true,
        });

        this.swiper.on('slideChange', e => {
            this.current = e.activeIndex;
            this.swiper.allowSlideNext = !e.activeIndex;
            this.swiper.allowSlidePrev = !!e.activeIndex;
        })
    },
    methods: {
        getAdminStatus() {
            let admin;
            this.group.users.forEach(user => {
                if (user.id == this.user.id) {
                    admin = user.isAdmin;
                }
            });
            return admin;
        },
        toggleGroupInfo() {
            this.$store.commit("setShowGroupInfo", {'showGroupInfo': !this.$store.getters.getShowGroupInfo});
        },
        switchToGeneral() {
            this.current = 0;
            this.swiper.slideTo(0);
        },
        switchToImportant() {
            this.current = 1;
            this.swiper.slideTo(1);
        },

        publishMessage() {
            console.log("Group.vue: publish Message");
            console.log("   UUID:", this.group.channels['allgemein'].uuid);

            if (this.message.replaceAll(' ', '').length) {
                console.log(this.group);
                this.$store.commit("publishMessage", {
                    message: this.message,
                    channel: this.current === 0 ? this.chats['allgemein'].uuid : this.chats['wichtig'].uuid,
                    chat: this.current === 0 ? this.chats['allgemein'].url : this.chats['wichtig'].url,
                    group: this.group.url,
                });

                this.message = "";
            }
        },
        publishImportantMessage(content) {
            this.$store.commit("publishImportantMessage", {
                subject: content.subject,
                message: content.text,
                channel: this.chats['wichtig'].uuid,
                chat: this.chats['wichtig'].url,
                group: this.group.url,
            });
        },
        publishPoll(content) {
            this.$store.commit("publishPoll", {
                subject: content.subject,
                channel: this.chats['wichtig'].uuid,
                chat: this.chats['wichtig'].url,
                group: this.group.url,
                allowMultiple: content.allowMultiple,
                answers: content.answers,
            });
        },
        publishFile(content) {
            // TODO Upload File

            const data = new FormData();
            data.append("file", content.file);
            data.append("groupId", this.group.id);
            axios
                .post(route("group.files.store"), data, {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                })
                .then((res) => {
                    console.log(res)


                });
            this.$store.commit("publishFile", {
                message: content.message,
                channel: this.chats['allgemein'].uuid,
                chat: this.chats['allgemein'].url,
                group: this.group.url,
                fileName: content.file.name,
                fileType: content.file.type,
            });

        },
        publishEventAnnouncement(eventAnnouncement) {
            // TODO add event to group in database
            this.$store.commit("publishEventAnnouncement", {
                message: eventAnnouncement.subject,
                channel: this.chats['wichtig'].uuid,
                chat: this.chats['wichtig'].url,
                group: this.group.url,
                date: eventAnnouncement.date,
            });
        },
        hasAccess() {
            if (this.current === 1) {
                return this.hasAdminPermissions;
            } else {
                return true;
            }
        },
        toggleSpecialMessages() {
            this.openSpecialMessages = !this.openSpecialMessages;
        },
    },
    created() {
        Vue.set(this.chats['wichtig'], "uuid", this.chats['wichtig'].uuid.uuid)
        Vue.set(this.chats['allgemein'], "uuid", this.chats['allgemein'].uuid.uuid)
        this.$store.commit("setCurrentPage", {page: this.group.name});
        this.$store.commit("setShowArrow", {showArrow: true});
    },
}
</script>

<style scoped>
#group {
    display: flex;
    flex-direction: row;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

#group-content {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

#group-content.active {
    width: 60vw;
}

#group-header {
    display: flex;
    flex-direction: column;
    box-shadow: 1px 0px 8px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0px 8px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0px 8px 3px var(--shadow-color);
    padding: 2vh 2vh 0 2vh;
    z-index: 30;
    background-color: var(--background-color);
}

.swiper-container {
    height: 100%;
    width: 100%;
}

.swiper-wrapper {

}

.swiper-slide {
}

.btn {
    flex-grow: 0 !important;
    width: fit-content;
}

button:focus {
    outline: 0;
}

#chat-selection {
    display: flex;
    flex-direction: row;
    justify-content: center;
}

.chat-link {
    width: 6em;
    text-align: center;
    font-size: 1.2rem;
    color: var(--font-color-light);
    font-weight: 600;
    transition: 0.2s ease;
}

.row {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
}

.chat-link:hover {
    color: #ffa88e;
}

.chat-link::after {
    content: "";
    width: 0;
    height: 4px;
    display: block;
    background-color: #ffa88e;
    transition: 0.2s ease;
}

.chat-link.left::after {
    margin-left: 100%;
}

.chat-link.right::after {
    margin-left: 0;
}

.chat-link.current {
    color: #ffa88e;
}

.chat-link.current.left::after {
    width: 100%;
    margin-left: 0;
}

.chat-link.current.right::after {
    width: 100%;
    margin-left: 0;
}

.slide-left-enter-active,
.slide-left-leave-active {
    transition: all 0.2s ease;

}

.slide-left-enter, .slide-left-leave-to {
    transform: translateX(-25%);
    opacity: 0;
}

.slide-right-enter-active,
.slide-right-leave-active {
    transition: all 0.2s ease;
}

.slide-right-enter, .slide-right-leave-to {
    transform: translateX(25%);
    opacity: 0;
}

.message-input {
    margin-left: 3vh;
    margin-right: 3vh;
    flex-grow: 1;
}

#input-group {
    position: relative;
    display: flex;
    padding: 1.5vh 3vh 1.5vh 3vh;
    box-shadow: 1px 0 15px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0 15px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0 15px 3px var(--shadow-color);
    justify-content: space-between;
    background-color: var(--background-color);
}

.special-messages-container {
    background-color: var(--background-color);
    margin-bottom: 2vh;
    padding: 1vh;
    height: 15rem;
    position: absolute;
    bottom: 100%;
    border-radius: 1rem;
    box-shadow: 1px 0 15px 3px var(--shadow-color);
    -webkit-box-shadow: 1px 0 15px 3px var(--shadow-color);
    -moz-box-shadow: 1px 0 15px 3px var(--shadow-color);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    z-index: 1;
}

.special-messages-container .btn {
    width: 100%;
}

.fade-box-enter-active,
.fade-box-leave-active {
    transition: opacity 0.15s ease;
}

.fade-box-enter, .fade-box-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0;
}

.special-messages-container i {
    margin-left: 1rem;
}

.btn i {
    margin-left: 1rem;
}

@media (max-width: 1200px) {
    #group-content.active {
        width: 100vw;
    }
}

@media (max-width: 576px) {
    .message-input {
        max-width: 68%; /*Irgendwie dumm, aber sonst funktioniert nix*/
        margin-left: 0.5vh;
        margin-right: 0.5vh;
    }

    #group-header {
        padding: 1vh 1vh 0 1vh;
    }

    .chat-link {
        font-size: 1rem;
    }

    #input-group {
        padding: 2%; /*Unten mehr, damit bei Handys mit abgerundeten Ecken nix abgeschnitten wird*/
    }

    .special-messages-container {
        padding: 4%;
    }

    .row {
        display: none;
    }

    #group {
        height: calc(100vh - 20vw);
    }

    #group-content.active {
        width: 100vw;
        transform: translateX(-100%);
    }
}
</style>
