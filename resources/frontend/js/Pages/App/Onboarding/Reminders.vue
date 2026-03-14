<script setup>
import { computed, onMounted, ref } from 'vue';
import { Icon } from '@iconify/vue';
import { Combobox, ComboboxInput, ComboboxOptions, ComboboxOption } from '@headlessui/vue'
import axios from 'axios'
import { Form, useForm } from '@inertiajs/vue3';
import { Switch } from '@headlessui/vue';

const isIos = () => {
  const userAgent = window.navigator.userAgent.toLowerCase();
  return /iphone|ipad|ipod/.test(userAgent);
};

// Checks if the app is running as a PWA
const isStandalone = () => {
  // Safari uses window.navigator.standalone, others use matchMedia
  return ('standalone' in window.navigator && window.navigator.standalone) ||
         window.matchMedia('(display-mode: standalone)').matches;
};

// If true, we show the "In-Between" screen instead of the normal Step 3
const showIosInstallPrompt = computed(() => {
    return isIos() && !isStandalone();
})

const form = useForm({
    remindersEnabled: true,
    maxNotificationAttempts: 3,
    timezone: "",
    notificationTime: "17:00"
});

const props = defineProps({
    timezones: Array
})

const query = ref('')
const selectedTimezone = ref(null)

// This filters instantly as they type!
const filteredTimezones = computed(() => {
    if (query.value === '') return props.timezones

    return props.timezones.filter((tz) => {
        return tz.toLowerCase().includes(query.value.toLowerCase())
    })
})

const isSubscribed = ref(false);

onMounted(async () => {
    // If we have a timezone prop that matches the user's current timezone, pre-select it
    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone

    if (props.timezones.includes(userTimezone)) {
        selectedTimezone.value = userTimezone
        form.timezone = userTimezone
    } else {
        console.warn('User timezone not found in list of timezones:', userTimezone)
    }

    if ('serviceWorker' in navigator) {
        const registration = await navigator.serviceWorker.getRegistration();
        if (registration) {
            const subscription = await registration.pushManager.getSubscription();
            isSubscribed.value = !!subscription;
        }
    }
})

const urlBase64ToUint8Array = (base64String) => {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
};

const enableNotifications = async () => {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        alert('Push notifications are not supported in this browser.');
        return false;
    }

    try {
        await navigator.serviceWorker.register('/notifier.js');
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            return false;
        }
        const registration = await navigator.serviceWorker.ready;

        const vapidPublicKey = import.meta.env.VITE_VAPID_PUBLIC_KEY;
        const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: convertedVapidKey
        });

        await axios.post('/onboarding/push-subscription', subscription.toJSON());

        isSubscribed.value = true;
        return true;

    } catch (error) {
        console.error('Error enabling notifications:', error);
        return false;
    }
};

async function handleForm() {

    if (form.remindersEnabled) {
        const success = await enableNotifications();
        if (!success) {
            alert('Please allow notifications in your browser, or turn off the toggle to continue.');
            return;
        }
    }
    submitForm();
}

function submitForm() {
    form.post('/onboarding/reminders');
}

function skipReminder() {
    form.remindersEnabled = false;
    submitForm();
}

</script>
<template>
    <div class="min-h-screen bg-primary-100 flex items-center">
        <div class="px-4 py-8 w-full h-fit">
            <div class="pl-onboarding--account bg-white max-w-100 mx-auto p-4 md:p-6 shadow-lg rounded-lg">
                <h1 class="fugaz text-primary-400 text-2xl md:text-3xl mb-4 text-center">Get started!</h1>
                <div class="pl-onboarding--progres">
                    <div class="pl-onboarding--progress__bar flex items-center">
                        <div class="pl-onboarding--progress__bar__step bg-primary-400"></div>
                        <div class="pl-onboarding--progress__bar__path w-full h-1 bg-primary-400"></div>
                        <div class="pl-onboarding--progress__bar__step bg-primary-400"></div>
                        <div class="pl-onboarding--progress__bar__path w-full h-1 bg-primary-400"></div>
                        <div class="pl-onboarding--progress__bar__step bg-primary-400"></div>
                    </div>
                    <div class="pl-onboarding--progess__lables grid grid-cols-3 mt-2 text-sm">
                        <span class="text-sm text-primary-400">Create Account</span>
                        <span class="text-sm text-primary-400 text-center">Allergies</span>
                        <span class="text-sm text-primary-400 text-end">Reminders</span>
                    </div>
                </div>
                <div
                    v-if="showIosInstallPrompt"
                    class="pl-onboarding--ios-step mt-8">
                    <p class="fugaz text-lg">Looks like you're using an iOS device!</p>
                    <p class="mt-1!">Do you want daily pollen reminders? Apple requires you to add Pollendar to your home screen first! To do this, tap the share button in the URL bar and select "Add to Home Screen".</p>
                    <Form class="pl-form mt-6" @submit.prevent="skipReminder()">
                        <button
                            type="submit"
                            class="w-fit text-gray-500 hover:text-gray-700 transition underline"
                        >
                            Skip for now
                        </button>
                    </Form>
                </div>
                <div v-else class="pl-onboarding--form mt-8">
                    <Form class="pl-form" @submit.prevent="handleForm">
                        <div class="pl-formgroup">
                            <label>Daily reminders</label>
                            <p class="pl-formgroup__helper mb-2">If you forget to log an entry into your pollendar, do you wanna be reminded about it?</p>
                            <div class="flex items-center gap-x-4">
                                <Switch
                                    v-model="form.remindersEnabled"
                                    :class="form.remindersEnabled ? 'bg-primary-400' : 'bg-gray-300'"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full"
                                >
                                    <span
                                        :class="form.remindersEnabled ? 'translate-x-6' : 'translate-x-1'"
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                    />
                                    <span class="sr-only">Enable reminders</span>
                                </Switch>
                                <p
                                    :class="form.remindersEnabled ? 'text-primary-400' : 'text-gray-500'"
                                >
                                    {{ form.remindersEnabled ? 'Yes, please remind me!' : 'No, thanks.' }}
                                </p>
                            </div>
                        </div>
                        <div class="pl-formgroup" v-if="form.remindersEnabled">
                            <label>Reminder Time</label>
                            <p class="pl-formgroup__helper">At what time should the reminders start?</p>
                            <input type="time" v-model="form.notificationTime"/>
                        </div>
                        <div class="pl-formgroup" v-if="form.remindersEnabled">
                            <label>Timezone</label>
                            <p class="pl-formgroup__helper">In which timezone are you located? This is important to make sure you get your reminders at the right time.</p>
                            <Combobox v-model="form.timezone">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-pollenpink focus:border-pollenpink"
                                        @change="query = $event.target.value"
                                        :displayValue="(tz) => tz"
                                    />

                                    <ComboboxOptions class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto">
                                        <div v-if="filteredTimezones.length === 0 && query.length >= 3" class="px-4 py-2 text-gray-500">
                                            No timezones found.
                                        </div>

                                        <ComboboxOption
                                            v-for="(timezone, index) in filteredTimezones"
                                            :key="index"
                                            :value="timezone"
                                            v-slot="{ active, selected }"
                                        >
                                            <li
                                                :class="[
                                                    'px-4 py-2 cursor-pointer transition-colors',
                                                    active ? 'bg-primary-50 text-primary-700' : 'text-gray-900',
                                                    selected ? 'font-bold' : ''
                                                ]"
                                            >
                                                {{ timezone.replace('_', ' ') }}
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>
                        <div class="pl-formgroup" v-if="form.remindersEnabled">
                            <label>Reminder frequency</label>
                            <p class="pl-formgroup__helper">Set this to a number larger than 1 and you will receive a reminder every hour until the number is reached or you logged an entry.</p>
                            <input type="number" v-model="form.maxNotificationAttempts"/>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="pl-button inline-flex w-fit items-center gap-2 mt-6 px-10 py-2 bg-secondary-500 text-white rounded-md text-lg md:text-xl lg:text-2xl hover:bg-secondary-600 transition">
                                    Finish setup
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

.pl-formgroup__helper {
    font-size: 0.75rem;
    color: #6B7280; /* Tailwind's gray-500 */
}
</style>
