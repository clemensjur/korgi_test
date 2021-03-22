<template>
    <div>
        <input type="file" name="file" id="fileInput"/>
        <button @click="upload">Upload</button>
        <ul>
            <li v-for="file in fileNames" :key="file">
                <button @click="download(file)">Download</button>
                {{ file }}
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Files",
    props: {
        group: Object,
        files: Array,
    },
    computed: {
        fileNames() {
            let fileNames = [];
            this.files.forEach((file) => fileNames.push(file.split("/")[2]));
            return fileNames;
        },
    },
    methods: {
        upload() {
            const data = new FormData();
            data.append("file", document.querySelector("#fileInput").files[0]);
            data.append("groupId", this.group.id);
            axios
                .post(route("group.files.store"), data, {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                })
                .then((res) => {
                    this.$inertia.reload({only: ["files"]});
                });
        },
        download(file) {
            axios
                .post(route("group.files.download"), {
                    filename: file,
                    groupId: this.group.id,
                })
                .then((res) => {
                    console.log(res);
                    // this.$inertia.reload({ only: ["files"] });
                });
        },
    },
};
</script>

<style>
</style>
