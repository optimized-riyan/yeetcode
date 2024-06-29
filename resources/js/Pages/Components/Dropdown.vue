<template>
    <div class="relative flex">
        <button type="button" @click="languageDropdown = !languageDropdown">{{ selectedLanguage }}</button>
        <div class="absolute top-6 z-10 bg-leetcode-background text-leetcode-text" v-if="languageDropdown">
            <ul>
                <li v-for="(language, index) in availableLanguages" :key="index" @click="languageChange(language)"
                    class="cursor-pointer">
                    {{ language }}
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
        }
    },
    created() {
        this.selectedLanguage = this.defaultLanguage;
    },
    emits: ["langChange"],
}

</script>
