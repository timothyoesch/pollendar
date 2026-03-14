<script setup>
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import AppLogo from '@/Components/AppLogo.vue';
</script>

<template>
    <div class="pl-lander--nav absolute top-0 left-0 w-full">
        <div class="max-w-375 mx-auto px-4 md:px-8 lg:px-16 flex justify-end py-4">
            <Link href="/" class="z-10">
                <AppLogo color-variant="negative" class="w-12 md:w-18 lg:w-24"/>
            </Link>
        </div>
    </div>
    <div class="pl-lander--heroine bg-primary-400 pt-8 md:py-16 overflow-hidden md:overflow-visible">
        <div class="max-w-375 mx-auto grid grid-cols-1 md:grid-cols-6 gap-x-8 px-4 md:px-8 lg:px-16">
            <div class="pl-lander--heroine--content md:col-span-3 text-white">
                <h1 class="text-4xl md:text-5xl lg:text-7xl">Stay on top of</h1>
                <h1 class="text-4xl md:text-5xl lg:text-7xl fugaz -mt-2">your allergies</h1>
                <p class="text-xl md:text-2xl lg:text-4xl leading-tight! mt-4">Keep track of your symptoms and how pollen affected you throughout the season. Quick, simple and free.</p>
                <Link href="/sign-up" class="pl-button inline-flex items-center gap-2 mt-6 px-10 py-2 bg-secondary-500 text-white rounded-md text-lg md:text-xl lg:text-2xl hover:bg-secondary-600 transition">
                    Get Started
                    <Icon
                        icon="heroicons:chevron-right-16-solid"
                        class="w-5 h-5 md:w-6 md:h-6 lg:w-7 lg:h-7"
                    />
                </Link>
            </div>
            <div class="pl-lander--heroine--image__desktop hidden md:block mb-[-50%] mt-[-40%] col-start-3 col-span-4">
                <picture>
                <source
                    type="image/avif"
                    srcset="@media/heroine_mockup_desktop-100.avif?width=100 100w, @media/heroine_mockup_desktop-200.avif?width=200 200w, @media/heroine_mockup_desktop-400.avif?width=400 400w, @media/heroine_mockup_desktop-800.avif?width=800 800w, @media/heroine_mockup_desktop-1000.avif?width=1000 1000w, @media/heroine_mockup_desktop-1200.avif?width=1200 1200w" />
                <source
                    type="image/webp"
                    srcset="@media/heroine_mockup_desktop-100.webp?width=100 100w, @media/heroine_mockup_desktop-200.webp?width=200 200w, @media/heroine_mockup_desktop-400.webp?width=400 400w, @media/heroine_mockup_desktop-800.webp?width=800 800w, @media/heroine_mockup_desktop-1000.webp?width=1000 1000w, @media/heroine_mockup_desktop-1200.webp?width=1200 1200w" />
                <img
                    src="@media/heroine_mockup_desktop.png"
                    srcset="@media/heroine_mockup_desktop-100.png?width=100 100w, @media/heroine_mockup_desktop-200.png?width=200 200w, @media/heroine_mockup_desktop-400.png?width=400 400w, @media/heroine_mockup_desktop-800.png?width=800 800w, @media/heroine_mockup_desktop-1000.png?width=1000 1000w, @media/heroine_mockup_desktop-1200.png?width=1200 1200w"
                    sizes="(max-width: 800px) 100vw, 50vw"
                    style="width: 100%; aspect-ratio: 1.1933174224343674"
                    loading="lazy"
                    decoding="async"
                    alt="App Mockup"
                />
                </picture>
            </div>
            <div class="pl-lander--heroine--image__mobile md:hidden">
                <picture>
                <source
                    type="image/avif"
                    srcset="@media/heroine_mockup_mobile-100.avif?width=100 100w, @media/heroine_mockup_mobile-200.avif?width=200 200w, @media/heroine_mockup_mobile-400.avif?width=400 400w, @media/heroine_mockup_mobile-800.avif?width=800 800w" />
                <source
                    type="image/webp"
                    srcset="@media/heroine_mockup_mobile-100.webp?width=100 100w, @media/heroine_mockup_mobile-200.webp?width=200 200w, @media/heroine_mockup_mobile-400.webp?width=400 400w, @media/heroine_mockup_mobile-800.webp?width=800 800w" />
                <img
                    src="@media/heroine_mockup_mobile.png"
                    srcset="@media/heroine_mockup_mobile-100.png?width=100 100w, @media/heroine_mockup_mobile-200.png?width=200 200w, @media/heroine_mockup_mobile-400.png?width=400 400w, @media/heroine_mockup_mobile-800.png?width=800 800w"
                    sizes="(max-width: 800px) 100vw, 50vw"
                    style="width: 100%; aspect-ratio: 0.8780297107114934"
                    loading="lazy"
                    decoding="async"
                    alt="App Mockup"
                />
                </picture>
            </div>
        </div>
    </div>
    <div class="pl-lander--intro md:mt-[32vh]">
        <div class="px-4 md:px-8 lg:px-16 py-16">
            <div class="max-w-150 mx-auto text-center">
                <h2 class="fugaz text-primary-400 text-4xl md:text-6xl mb-4">What’s pollendar?</h2>
                <p>In the off-season of 2025, I stopped <a href="https://www.helsana.ch/en/blog/body/illnesses/desensitisation.html" target="_blank">desensitization therapy</a> for the first time after getting a shot every month for almost 7 years. I rarely had any symptoms anymore, which is why my allergologist suggested we should pause the treatment for a season and see how it’s going. Barely two months into the pollen season, I was sure I was experiencing a lot more allergy symptoms than before, but I didn’t have anything to compare it to.</p>
                <p>I know I should have kept a pollen diary for years, but I never got around to it cause all the systems I found didn’t really do what I wanted them to do. The result: Pollendar (a portmanteau of pollen and calendar). A very simple app I whipped up within a weekend that helps you keep track of your allergic symptoms and compares it to the pollen levels in your area.</p>
                <Link href="/sign-up" class="pl-button inline-flex items-center gap-2 mt-6 px-10 py-2 bg-secondary-500 text-white rounded-md text-lg md:text-xl lg:text-2xl hover:bg-secondary-600 transition">
                    Sign up
                    <Icon
                        icon="heroicons:chevron-right-16-solid"
                        class="w-5 h-5 md:w-6 md:h-6 lg:w-7 lg:h-7"
                    />
                </Link>
                <p class="text-sm text-gray-300 mt-4">It’s free.<br>And it'll stay free, as long as I can cover the costs it takes for me to run this for myself. I promise.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Fade in pictures from bottom delayed by 0.5 s*/
picture img {
    opacity: 0;
    transform: translateY(40px);
    animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    animation-delay: 0.5s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
