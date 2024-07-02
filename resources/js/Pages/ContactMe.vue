<template lang="">
    <ProblemSetLayout :avatar-image="avatarImage">
        <div class="flex justify-center items-center h-full w-full">
            <div class="h-2/6 w-4/12 bg-leetcode-backgroundlight rounded-lg flex flex-col justify-center">
                <ul class="ml-16">
                    <li>
                        <i class="fa-brands fa-linkedin pr-2 text-blue-700"></i> <u>Linkedin</u>
                    </li>
                    <li>
                        <i class="fa-solid fa-n pr-3 text-blue-500"></i> <u>Naukri</u>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone pr-2 text-green-500"></i> <u>Phone</u>
                    </li>
                    <li>
                        <i class="fa-regular fa-envelope pr-2 text-red-500"></i> <u>Email</u>
                    </li>
                    <li>
                        <i class="fa-brands fa-github-alt pr-2"></i> <u>Github</u>
                    </li>
                </ul>
            </div>
        </div>
    </ProblemSetLayout>
</template>
<script>
import ProblemSetLayout from './Layouts/ProblemSetLayout.vue';

export default {
    components: {
        ProblemSetLayout,
    },
    props: {
        avatarImage: String,
    },
}
</script>
