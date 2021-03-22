<template>
    <div id="join-link-dialog">
        <img id="qrCode" :alt="'QR-Code für: ' + link" :src="qrCodeDataUrl">
        <div id="buttons">
            <div class="btn secondary-background" @click="printQrCode"><p>Drucken</p><i class="fas fa-print"></i></div>
            <div class="btn secondary-background" @click="copyLink"><p>Kopieren</p><i class="fas fa-clipboard"></i></div>
        </div>
    </div>
</template>

<script>
import QRCode from "qrcode";

export default {
    name: "dialog-content-join-link",
    props: {
        link: String
    },
    data() {
        return {
            qrCodeDataUrl: undefined
        }
    },
    mounted() {
        QRCode.toDataURL(this.link, {
            errorCorrectionLevel: 'H',
            type: 'image/png',
            quality: 1,
            margin: 0,
            color: {
                dark: this.$store.getters.getUser.settings.darkmode ? "#FFFFFF" : "#2c2f33",
                light: this.$store.getters.getUser.settings.darkmode ? "#505050" : "#FFFFFF"
            }
        })
            .then(url => {
                this.qrCodeDataUrl = url;
            });
    },
    methods: {
        copyLink() {
            var tempInput = document.createElement("input");
            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
            tempInput.value = this.link;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            this.$store.commit("setPopupMessage", {message:"Link kopiert!"});
            setTimeout(() => {
                this.$store.commit("setPopupMessage", {message:""});
            }, 1500)
        },
        printQrCode() {

        }
    }
}
</script>

<style scoped>
#join-link-dialog {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

#buttons {
    display: flex;
    flex-direction: column;
    width: 100%;
    align-items: center;
}

.btn {
    margin-top: 1rem;
    width: 45%;
}
</style>

