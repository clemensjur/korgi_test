<template>
    <div id="join-link-dialog">
        <img ref="content" id="qrCode" :alt="'QR-Code für: ' + link" :src="qrCodeDataUrl">
        <div id="buttons">
            <div class="btn secondary-background" @click="printQrCode"><p>Drucken</p><i class="fas fa-print"></i></div>
            <div class="btn secondary-background" @click="copyLink"><p>Kopieren</p><i class="fas fa-clipboard"></i></div>
        </div>
    </div>
</template>

<script>
import QRCode from "qrcode";
import jsPDF from "jspdf";
import domtoimage from "dom-to-image";

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
            /** WITH CSS */
            console.log(this.link);
            var text = this.link;
            domtoimage
                .toPng(this.$refs.content)
                .then(function(dataUrl) {
                    var img = new Image();
                    var logo = new Image();
                    logo.src = "/images/korgi_full_l_lr.png"
                    img.src = dataUrl;
                    const doc = new jsPDF({
                        orientation: "portrait",
                        unit: "mm",
                        format: [210, 297]
                    });
                    doc.addImage(logo, "JPEG", 26, 10, doc.internal.pageSize.width*0.8, 42);
                    doc.text("KORGI-Gruppe", doc.internal.pageSize.width/2, 70, null, null, "center");
                    doc.addImage(img, "JPEG", 80, 80);
                    doc.text("Link:", doc.internal.pageSize.width/2, 150, null, null, "center");
                    doc.setFontSize(12);
                    doc.text(text, doc.internal.pageSize.width/2, 160, null, null, "center");
                    doc.save("QR-Code");
                })
                .catch(function(error) {
                    console.error("oops, something went wrong!", error);
                });
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

