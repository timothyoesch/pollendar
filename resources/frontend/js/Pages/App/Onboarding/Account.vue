<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
</script>

<template>
    <div class="min-h-screen bg-primary-100 flex items-center">
        <div class="px-4 py-8 w-full h-fit">
            <div class="pl-onboarding--account bg-white max-w-100 mx-auto p-4 md:p-6 shadow-lg rounded-lg">
                <h1 class="fugaz text-primary-400 text-2xl md:text-3xl mb-4 text-center">Get started!</h1>
                <div class="pl-onboarding--progres">
                    <div class="pl-onboarding--progress__bar flex items-center">
                        <div class="pl-onboarding--progress__bar__step bg-primary-400"></div>
                        <div class="pl-onboarding--progress__bar__path w-full h-1 bg-gray-400"></div>
                        <div class="pl-onboarding--progress__bar__step bg-gray-400"></div>
                        <div class="pl-onboarding--progress__bar__path w-full h-1 bg-gray-400"></div>
                        <div class="pl-onboarding--progress__bar__step bg-gray-400"></div>
                    </div>
                    <div class="pl-onboarding--progess__lables grid grid-cols-3 mt-2 text-sm">
                        <span class="text-sm text-primary-400">Create Account</span>
                        <span class="text-sm text-gray-500 text-center">Allergies</span>
                        <span class="text-sm text-gray-500 text-end">Reminders</span>
                    </div>
                </div>
                <div class="pl-onboarding--form mt-8">
                    <Form class="pl-form" action="/onboarding/account" method="post">
                        <div class="pl-formgroup">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username"/>
                        </div>
                        <div class="pl-formgroup">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email"/>
                        </div>
                        <div class="pl-formgroup">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password"/>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="pl-button inline-flex w-fit items-center gap-2 mt-6 px-10 py-2 bg-secondary-500 text-white rounded-md text-lg md:text-xl lg:text-2xl hover:bg-secondary-600 transition">
                                    Next
                                    <Icon
                                        icon="heroicons:chevron-right-16-solid"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-7 lg:h-7"
                                    />
                            </button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.pl-onboarding--progress__bar__step {
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    z-index: 10;
    flex-shrink: 0;
}
</style>
