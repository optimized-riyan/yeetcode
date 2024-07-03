<template>
    <div class="relative flex">
        <button type="button" @click="languageDropdown = !languageDropdown"
        class="m-1 px-2 py-1 bg-leetcode-backgroundlight rounded-md"
        >
            {{ displayLanguage(selectedLanguage) }}
        </button>
        <div class="absolute top-10 left-2 z-10 text-leetcode-text" v-if="languageDropdown">
            <ul class="rounded-md bg-leetcode-backgroundlight shadow-inner">
                <li v-for="(language, index) in availableLanguages" :key="index" @click="languageChange(language)"
                    class="cursor-pointer px-2 py-1 bg-transparent hover:text-leetcode-green-light">
                    {{ displayLanguage(language) }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            languageDropdown: false,
            selectedLanguage: null,
        }
    },
    props: {
        availableLanguages: Array,
        defaultLanguage: String,
    },
    methods: {
        languageChange(language) {
            this.selectedLanguage = language;
            this.$emit("langChange", language);
            this.languageDropdown = false;
        },
        displayLanguage(language) {
            let displayed = language;
            switch (language) {
                case "python":
                    displayed = "Python";
                    break;
                case "js":
                    displayed = "Javascript";
                    break;
                case "php":
                    displayed = "PHP";
                    break;
                case "c":
                    displayed = "C";
                    break;
                case "c++":
                    displayed = "C++";
                    break;
            }
            return displayed;
        },
        closeDropdown(e) {
            if (!this.$el.contains(e.target)) {
                this.languageDropdown = false;
            }
        },
    },
    created() {
        this.selectedLanguage = this.defaultLanguage;
    },
    emits: ["langChange"],
    mounted() {
        document.addEventListener("click", this.closeDropdown);
    },
    beforeDestroy() {
        document.removeEventListener("click", this.closeDropdown);
    },
}

</script>
