<template>
    <div id="imprint">
        <inertia-link id="logo" :href="route('home')">
<!--            <img src="/images/korgi_yellow_only.svg" alt="Logo">-->
            <div class="round-btn secondary-background">
                <i class="fas fa-arrow-left"></i>
            </div>
        </inertia-link>
        <h1 id="greeting">Impressum</h1>
        <div id="imprint-content">
            <p class="adsimple-121672422">Informationspflicht laut §5 E-Commerce Gesetz, §14 Unternehmensgesetzbuch, §63
                Gewerbeordnung und Offenlegungspflicht laut §25 Mediengesetz.</p>
            <p class="adsimple-121672422">Korgi<br/>Matthias Hirmann<br/>Rennweg 89b, <br/>1030 Wien, <br/>Österreich
            </p>
            <p class="adsimple-121672422">
                <strong>Unternehmensgegenstand:</strong> IT Dienstleistungen<br/>
            </p>
            <p class="adsimple-121672422">
                <strong>E-Mail:</strong> <a href="mailto:contact@korgi.at">contact@korgi.at</a>
            </p>
            <p style="margin-top:15px">Quelle: Erstellt mit dem <a href="https://www.adsimple.at/impressum-generator/"
                                                                   title="Impressum Generator von AdSimple">Impressum
                Generator</a> von AdSimple</p>
            <h2 id="eu-streitschlichtung" class="adsimple-121672422">EU-Streitschlichtung</h2>
            <p>Gemäß Verordnung über Online-Streitbeilegung in Verbraucherangelegenheiten (ODR-Verordnung) möchten wir
                Sie über die Online-Streitbeilegungsplattform (OS-Plattform) informieren.<br/>
                Verbraucher haben die Möglichkeit, Beschwerden an die Online Streitbeilegungsplattform der Europäischen
                Kommission unter <a class="adsimple-121672422"
                                    href="https://ec.europa.eu/consumers/odr/main/index.cfm?event=main.home2.show&lng=DE"
                                    target="_blank" rel="noopener">http://ec.europa.eu/odr?tid=121672422</a> zu richten.
                Die dafür notwendigen Kontaktdaten finden Sie oberhalb in unserem Impressum.</p>
            <p>Wir möchten Sie jedoch darauf hinweisen, dass wir nicht bereit oder verpflichtet sind, an
                Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>
            <h2 id="haftung-fuer-inhalte-dieser-webseite" class="adsimple-121672422">Haftung für Inhalte dieser
                Webseite</h2>
            <p>Wir entwickeln die Inhalte dieser Webseite ständig weiter und bemühen uns korrekte und aktuelle
                Informationen bereitzustellen. Leider können wir keine Haftung für die Korrektheit aller Inhalte auf
                dieser Webseite übernehmen, speziell für jene die seitens Dritter bereitgestellt wurden.</p>
            <p>Sollten Ihnen problematische oder rechtswidrige Inhalte auffallen, bitten wir Sie uns umgehend zu
                kontaktieren, Sie finden die Kontaktdaten im Impressum.</p>
            <h2 id="haftung-links-webseite" class="adsimple-121672422">Haftung für Links auf dieser Webseite</h2>
            <p>Unsere Webseite enthält Links zu anderen Webseiten für deren Inhalt wir nicht verantwortlich sind.
                Haftung für verlinkte Websites besteht laut <a class="adsimple-121672422"
                                                               href="https://www.ris.bka.gv.at/Dokument.wxe?Abfrage=Bundesnormen&Dokumentnummer=NOR40025813&tid=121672422"
                                                               target="_blank" rel="noopener">§ 17 ECG</a> für uns
                nicht, da wir keine Kenntnis rechtswidriger Tätigkeiten hatten und haben, uns solche Rechtswidrigkeiten
                auch bisher nicht aufgefallen sind und wir Links sofort entfernen würden, wenn uns Rechtswidrigkeiten
                bekannt werden.</p>
            <p>Wenn Ihnen rechtswidrige Links auf unserer Website auffallen, bitten wir Sie uns zu kontaktieren, Sie
                finden die Kontaktdaten im Impressum.</p>
            <h2 id="urheberrechtshinweis" class="adsimple-121672422">Urheberrechtshinweis</h2>
            <p>Alle Inhalte dieser Webseite (Bilder, Fotos, Texte, Videos) unterliegen dem Urheberrecht. Falls
                notwendig, werden wir die unerlaubte Nutzung von Teilen der Inhalte unserer Seite rechtlich
                verfolgen.</p>
            <p>Sollten Sie auf dieser Webseite Inhalte finden, die das Urheberrecht verletzen, bitten wir Sie uns zu
                kontaktieren.</p>
            <h2 id="bildernachweis" class="adsimple-121672422">Bildernachweis</h2>
            <p>Die Bilder, Fotos und Grafiken auf dieser Webseite sind urheberrechtlich geschützt.</p>
            <p>Die Bilderrechte liegen bei den folgenden Fotografen und Unternehmen:</p>
            <ul class="adsimple-121672422">
                <li class="adsimple-121672422">Korgi</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "Imprint"
}
</script>

<style scoped>
::selection {
    background-color: #ffcb8e;
    color: white;
}

#imprint {
    font-family: "Montserrat", sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: fit-content;
    background-color: #ffa88e;
    justify-content: space-between;
}

#greeting {
    text-align: center;
    color: #ffffff;
    font-size: 4rem;
    font-weight: 700;
    align-self: center;
    width: 100%;
    margin-top: 4vh;
    margin-bottom: 4vh;
}

#logo {
    width: 18vh;
    align-self: flex-start;
    margin-top: 4vh;
    margin-left: 4vh;
    border: none;
    outline: none;
    box-shadow: none;
}

#imprint-content {
    background-color: #ffffff;
    border-radius: 30px;
    padding: 6.5vh;
    width: 80%;
    margin-bottom: 8vh;
}

ul {
    display: block;
    list-style-type: disc;
    margin-top: 1em;
    margin-bottom: 1em;
    margin-left: 0;
    margin-right: 0;
    padding-left: 40px;
}

h2 {
    display: block;
    font-size: 1.5em;
    margin-top: 0.83em;
    margin-bottom: 0.83em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: var(--secondary)
}

h3 {
    display: block;
    font-size: 1.17em;
    margin-top: 1em;
    margin-bottom: 1em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

li {
    display: list-item;
}

a:link, a:visited {
    color: #000000;
    text-decoration: underline;
    cursor: pointer;
}

a:link:active, a:visited:active {
    color: #000000;
}


#logo {
    text-decoration: none;
}

strong {
    font-weight: bold;
}

p {
    display: block;
    margin-top: 1em;
    margin-bottom: 1em;
    margin-left: 0;
    margin-right: 0;
}

img {
    display: inline-block;
}

@media (max-width: 576px) {
    #logo {
        margin-left: 2vh;
        margin-top: 2vh;
    }
    #imprint-content {
        width: 90%;
        padding: 2vh;
    }
    #greeting {
        font-size: 2rem;
        margin-top: 2vh;
        margin-bottom: 2vh;
    }
}
</style>
