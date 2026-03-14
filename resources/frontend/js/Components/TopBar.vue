<script setup>
import AppLogo from '@/Components/AppLogo.vue';
let date = new Date();
let hours = date.getHours();
let greeting;
if (hours < 12) {
    greeting = 'Good morning';
} else if (hours < 18) {
    greeting = 'Good afternoon';
} else {
    greeting = 'Good evening';
}
</script>

<template>
    <div class="pl-app__top-bar w-full bg-primary-400">
        <div class="pl-app__container py-4 flex justify-start items-center gap-x-2">
            <AppLogo :show-name="false" color-variant="negative" class="w-10 lg:w-12"/>
            <p class="text-white text-sm mt-5 lg:mt-7">{{ greeting }}, {{ $page.props.auth.user.name }}</p>
        </div>
    </div>
</template>
