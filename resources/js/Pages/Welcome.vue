<template>
    <!--  <div id="page-wrapper">-->
    <!--    <div-->
    <!--      v-if="canLogin"-->
    <!--      class="hidden fixed top-0 right-0 px-6 py-4 sm:block"-->
    <!--      id="navigation"-->
    <!--    >-->
    <!--      <inertia-link-->
    <!--        v-if="$page.props.user"-->
    <!--        href="/gruppen"-->
    <!--        class="text-sm text-gray-700 underline"-->
    <!--      >-->
    <!--        Gruppen-->
    <!--      </inertia-link>-->

    <!--          <template v-else>-->
    <!--            <inertia-link-->
    <!--              :href="route('login')"-->
    <!--              class="text-sm text-gray-700 underline"-->
    <!--            >-->
    <!--              Login-->
    <!--            </inertia-link>-->

    <!--        <inertia-link-->
    <!--          v-if="canRegister"-->
    <!--          :href="route('register')"-->
    <!--          class="ml-4 text-sm text-gray-700 underline"-->
    <!--        >-->
    <!--          Register-->
    <!--        </inertia-link>-->
    <!--      </template>-->
    <!--    </div>-->
    <!--    <div id="header">-->
    <!--      <h1>-->
    <!--        <span class="accentText">K</span><span class="primaryText">ORG</span-->
    <!--        ><span class="accentText">I</span>-->
    <!--      </h1>-->
    <!--    </div>-->
    <!--  </div>-->
    <div id="welcome">
        <div id="navigation">
            <img
                @click="scrollToId('header')"
                id="logo"
                src="/images/korgi_full_l.svg"
                alt="Logo"
            />
            <div id="link-container">
                <a @click="scrollToId('header')">Home</a>
                <a @click="scrollToId('login')">Anmelden</a>
                <a @click="scrollToId('about')">Über uns</a>
            </div>
            <a id="burger" @click="toggleMenu"><span/></a>
        </div>
        <div id="slider">
            <a
                @click="
          scrollToId('header');
          toggleMenu;
        "
            >Home</a
            >
            <a
                @click="
          scrollToId('login');
          toggleMenu;
        "
            >Anmelden</a
            >
            <a
                @click="
          scrollToId('about');
          toggleMenu;
        "
            >Über uns</a
            >
        </div>
        <div id="header">
            <div id="header-content">
                <div id="header-content-left">
                    <h1 class="slogan">Organisation leicht gemacht.</h1>
                    <div class="separator"></div>
                    <div class="text">
                        Weil Kommunikation und Organisation auch im Kindergarten nicht
                        kompliziert sein muss, setzt KORGI genau hier an.
                    </div>
                    <a @click="scrollToId('login')" class="btn primary-background"
                    >Anmelden</a
                    >
                </div>
                <div id="header-content-right">
                    <img
                        id="app-preview"
                        src="/images/app_vorschau_hr.png"
                        alt="App Vorschau"
                    />
                </div>
            </div>
        </div>
        <div id="login" :style="{ height: vpheight + 'px' }">
            <h2 class="headline">Anmeldung</h2>
            <div id="selection">
                <button
                    as="button"
                    class="selection-link left"
                    :class="loginIsCurrent()"
                    v-on:click="type = false"
                    @click="showLogin()"
                >
                    Anmelden
                </button>

                <button
                    class="selection-link right"
                    :class="registrationIsCurrent()"
                    v-on:click="type = true"
                    @click="showRegistration()"
                >
                    Registrieren
                </button>
            </div>
            <div id="login-content">
                <div id="login-content-left" class="active">
                    <login></login>
                </div>
                <div class="separator"></div>
                <div id="register">
                    <img id="logo-msg" src="/images/dog_message.svg" alt="KORGI"/>
                    <div id="register-info">
                        Registriere dich jetzt und genieße alle Vorteile von KORGI. Absolut
                        kostenlos.
                    </div>
                    <inertia-link
                        :href="route('register')"
                        class="btn secondary-background"
                    >Registrieren
                    </inertia-link
                    >
                </div>
            </div>
        </div>
        <div id="about">
            <h2 class="headline">Über uns</h2>
            <div id="about-content">
                <div id="about-content-left">
                    <h2 class="section-headline">Die Idee</h2>
                    <div class="text">
                        Die Idee ist es, die Komplexität der Verwaltung mehrere
                        Kindergartengruppen, hinsichtlich Organisation und Terminplanung für
                        diverse alltägliche Ereignisse, für Eltern sowie Personal, auf ein
                        Minimum zu reduzieren.
                    </div>
                    <h2 class="section-headline">Wieso KORGI?</h2>
                    <div class="text">
                        Nicht selten ist es der Fall, dass die Kommunikation zwischen Eltern
                        und PädagogInnen anfällig für Missverständnisse ist. Dem wollen wir
                        mit Korgi Abhilfe verschaffen, indem wir ein zentrales,
                        benutzerfreundliches Werkzeug für die KOmmunikation und Organisation
                        zur Verfügung stellen.
                    </div>
                    <h2 class="section-headline">Was kann die App?</h2>
                    <div class="text">
                        Mit Korgi können Sie auf einfachste Weise Gruppen erstellen, in
                        denen Sie mit den Mitgliedern per Textnachrichten kommunizieren
                        können. Zusätzlich bieten wir Ihnen die Möglichkeit Umfragen
                        durchzuführen und Termine festzulegen.
                    </div>
                </div>
                <div id="about-content-right">
                    <img src="/images/korgi_red_shadow.svg"/>
                </div>
            </div>
        </div>
        <div id="footer">
            <div id="footer-content">
                <inertia-link :href="route('imprint')" class="footer-link">Impressum</inertia-link>
                <inertia-link :href="route('tos')" class="footer-link">Datenschutzerklärung</inertia-link>
            </div>
            <div class="footer-cr">© 2021 KORGI</div>
        </div>
    </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Comfortaa:wght@700&display=swap");

#welcome {
    font-family: "Montserrat", sans-serif;
}

::selection {
    background: #ffcb8e;
    color: #ffffff;
}

img {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

#navigation {
    z-index: 10;
    background-color: #ffffff;
    width: 100%;
    position: fixed;
    height: 7vh;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding-left: 15%;
    padding-right: 15%;
    -webkit-box-shadow: 5px 5px 40px 0px rgba(92, 86, 86, 0.3);
    -moz-box-shadow: 5px 5px 40px 0px rgba(92, 86, 86, 0.3);
    box-shadow: 5px 5px 40px 0px rgba(92, 86, 86, 0.3);
}

#navigation #logo {
}

#navigation #link-container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

#navigation #link-container a {
    margin-left: 1.5vh;
    margin-right: 1.5vh;
    font-weight: 600;
    cursor: pointer;
    color: #000000;

    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

#navigation #link-container a:hover {
    color: #ffcb8e;
    transition: 0.15s;
}

#slider a {
    margin-bottom: 2vh;
    font-size: 1.2rem;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #000000;
}

#slider a:hover {
    color: #ffcb8e;
    transition: 0.15s;
}

#slider {
    width: 80vw;
    height: 100vh;
    display: flex;
    flex-direction: column;
    -webkit-box-shadow: 5px 5px 40px 0px rgba(44, 47, 51, 0.3);
    -moz-box-shadow: 5px 5px 40px 0px rgba(44, 47, 51, 0.3);
    box-shadow: 5px 5px 40px 0px rgba(44, 47, 51, 0.3);
    position: fixed;
    left: 100vw;
    z-index: 5;
    background-color: #ffffff;
    transition: all 0.25s ease;
    align-items: center;
    justify-content: center;
}

#slider.sliderActive {
    transform: translateX(-80vw);
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
    background: #000000;
    transition: all 0.2s ease-in-out;
    border-radius: 3px;
}

#burger span:before,
#burger span:after {
    position: absolute;
    background: #000000;
    content: "";
    width: 30px;
    height: 4px;
    transition: all 0.2s;
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
    background: #000000;
}

#burger.active span:after {
    transform: rotate(-45deg) translate(5px, -6px);
    background: #000000;
}

#logo {
    width: 18vh;
    cursor: pointer;
}

#header {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #ffffff;
}

#app-preview {
    width: 100%;
}

#header #header-content {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    width: 90%;
    align-self: center;
}

#header-content-left {
    width: 40%;
    display: flex;
    flex-direction: column;
}

#header-content-left .separator {
    width: 55%;
    height: 6px;
    background-color: #ffa88e;
    margin-top: 4vh;
    margin-bottom: 4vh;
    border-radius: 10px;
}

#header-content-left .text {
    font-size: 1.5rem;
    width: 80%;
    color: #000000;
}

#header-content-left .btn {
    margin-top: 6vh;
    width: fit-content;
    align-self: center;
    font-size: 1.5rem;
    height: 4rem;
    border-radius: 2rem;
}

#header-content-right {
    width: 50%;
}

#login {
    height: 100vh;
    display: flex;
    background-color: #ffa88e;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
}

#selection {
    display: none;
    flex-direction: row;
    justify-content: center;
    align-self: center;
    margin-bottom: 4vh;
}

.selection-link {
    width: 8em;
    text-align: center;
    font-size: 1.1rem;
    color: #ffffff;
    font-weight: 600;
    transition: 0.2s ease;
}

.selection-link:hover {
    color: #ffcb8e;
}

.selection-link::after {
    content: "";
    width: 0;
    height: 4px;
    display: block;
    background-color: #ffcb8e;
    transition: 0.2s ease;
}

.selection-link.left::after {
    margin-left: 100%;
}

.selection-link.right::after {
    margin-left: 0;
}

.selection-link-current {
    color: #ffcb8e;
}

.selection-link-current.left::after {
    width: 100%;
    margin-left: 0;
}

.selection-link-current.right::after {
    width: 100%;
    margin-left: 0;
}

#login-content {
    width: 90%;
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-direction: row;
    align-self: center;
}

#login-content-left {
    width: 30%;
}

#register {
    width: 30%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #ffffff;
    border-radius: 30px;
    padding: 4vh;

    -webkit-box-shadow: 5px 5px 40px 0px rgba(92, 86, 86, 0.3);
    -moz-box-shadow: 5px 5px 40px 0px rgba(92, 86, 86, 0.3);
    box-shadow: 5px 5px 40px 0px rgba(92, 86, 86, 0.3);
}

#logo-msg {
    margin-bottom: 4vh;
}

#register-info {
    color: #000000;
    font-size: 1.4rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 4vh;
}

#register .btn {
    width: fit-content;
    font-size: 1.5rem;
    height: 4rem;
    border-radius: 2rem;
}

#login-content .separator {
    height: 60vh;
    background-color: white;
    width: 6px;
    border-radius: 10px;
}

#about {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    background-color: #ffffff;
}

#about-content {
    width: 90%;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-self: center;
    align-items: center;
}

#about-content-left {
    width: 40%;
}

#about-content-left .text {
    font-size: 1rem;
    margin-bottom: 4vh;
    color: #000000;
}

#about-content-left .section-headline {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1vh;
    color: #000000;
}

#about-content-right {
    width: 40%;
    display: flex;
    justify-content: center;
}

#about-content-right img {
    width: 60vh;
}

#footer {
    height: 10vh;
    background-color: #ffa88e;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}

#footer-content {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    width: 90%;
}

.footer-link {
    font-size: 1.2rem;
    color: #ffffff;
    margin-left: 6vh;
    text-decoration: underline;
    font-weight: 500;
    cursor: pointer;
}

.footer-cr {
    font-size: 1.2rem;
    color: #ffffff;
    margin-right: 6vh;
}

.headline {
    color: #ffcb8e;
    font-size: 3rem;
    margin-left: 10vh;
    margin-top: 10vh;
    margin-bottom: 5vh;
}

.slogan {
    font-size: 4rem;
    font-weight: 500;
    line-height: 4.5rem;
    width: 80%;
    color: #000000;
}

@media (max-width: 576px) {
    #navigation {
        height: 15vw;
    }

    #header-content-right {
        display: none;
    }

    #header-content-left {
        width: 100%;
    }

    #navigation {
        padding-left: 5%;
        padding-right: 5%;
    }

    #navigation #link-container {
        display: none;
    }

    #header-content {
        width: 100%;
        justify-content: center;
    }

    .slogan {
        font-size: 2.6rem;
        line-height: 4rem;
    }

    #header-content-left .separator {
        margin-bottom: 2vh;
        margin-top: 2vh;
        width: 40%;
    }

    #header-content-left .text {
        font-size: 1.1rem;
    }

    #header-content-left .btn {
        align-self: flex-start;
        margin-top: 4vh;
    }

    #about-content-right {
        display: none;
    }

    #about-content-left {
        width: 100%;
    }

    #login {

    }

    .headline {
        margin-left: 0 !important;
        margin-top: 8vh;
        margin-bottom: 2vh;
        font-size: 2.2rem;
        align-self: center;
    }

    .footer-link {
        font-size: 1rem;
        margin-left: 2vh;
    }

    #footer-content {
        flex-direction: row;
        justify-content: space-around;
        width: 100%;
    }

    #footer {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 16px;
    }

    .footer-cr {
        margin-left: 0;
        margin-right: 0;
    }

    #register .btn {
        height: 3rem;
        font-size: 1rem;
    }

    #header-content .btn {
        height: 3rem;
        font-size: 1rem;
    }

    #about-content-left .section-headline {
        font-size: 1.3rem;
    }

    #about-content-left .text {
        margin-bottom: 2vh;
    }

    #login-content #login-content-left {
        width: 80vw;
        display: none;
    }

    #login-content #login-content-left.active {
        display: flex;
    }

    #login-content #register {
        display: none;
        width: 80vw;
    }

    #login-content #register.active {
        display: flex;
    }

    #login-content .separator {
        display: none;
    }

    button:focus {
        outline: 0;
    }

    #selection {
        display: flex;
    }

    .footer-link {
        margin-left: 0;
    }
}

@media (min-width: 576px) {
    #burger {
        display: none;
    }
}
</style>

<script>
import Login from "@/Pages/Auth/Login";

export default {
    components: {Login},
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    },
    data() {
        return {
            type: false,
            vpheight: window.innerHeight
        };
    },
    methods: {
        scrollToId(id) {
            document.getElementById(id).scrollIntoView({
                behavior: "smooth",
            });
        },
        toggleMenu() {
            if (
                document.getElementById("slider").classList.contains("sliderActive")
            ) {
                document.getElementById("slider").classList.remove("sliderActive");
                document.getElementById("burger").classList.remove("active");
            } else {
                document.getElementById("slider").classList.add("sliderActive");
                document.getElementById("burger").classList.add("active");
            }
        },
        showLogin() {
            document.getElementById("login-content-left").classList.add("active");
            document.getElementById("register").classList.remove("active");
        },
        showRegistration() {
            document.getElementById("register").classList.add("active");
            document.getElementById("login-content-left").classList.remove("active");
        },
        loginIsCurrent() {
            if (!this.type) {
                return "selection-link-current";
            }
        },
        registrationIsCurrent() {
            if (this.type) {
                return "selection-link-current";
            }
        },
    },
    mounted() {
        console.log(this.vpheight);
    }
};
</script>
