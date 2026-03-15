<script setup>
import BottomBar from '@/Components/BottomBar.vue';
import { Icon } from '@iconify/vue';
import { Form, Link, useForm, usePage } from '@inertiajs/vue3';
import { Switch } from '@headlessui/vue';

const today = new Date();
const yyyy = today.getFullYear();
const mm = String(today.getMonth() + 1).padStart(2, '0');
const dd = String(today.getDate()).padStart(2, '0');

const formattedToday = `${yyyy}-${mm}-${dd}`;

const form = useForm({
    date: formattedToday,
    symptomSeverity: 0,
    symptoms: [],
    othersSymptoms: '',
    medicationTaken: false,
    medicationInformation: '',
    notes: '',
    pollenForecastLocation: {
        name: usePage().props.auth.user.homebase_name || '',
        latitude: usePage().props.auth.user.homebase_latitude || null,
        longitude: usePage().props.auth.user.homebase_longitude || null,
    }
});

let symptomOptions = [
    {
        "emoji" : "🤧",
        "name" : "Stuffy/Runny nose"
    },
    {
        "emoji" : "😮‍💨",
        "name" : "Sneezing"
    },
    {
        "emoji" : "🥹",
        "name" : "Itchy/watery eyes"
    },
    {
        "emoji" : "😫",
        "name" : "Itchy throat"
    },
    {
        "emoji" : "😩",
        "name" : "Headache"
    },
    {
        "emoji" : "🤔",
        "name" : "Something else"
    }
];

const submitForm = () => {
    // Merge other symptoms into the main symptoms array if "Something else" is selected
    let finalSymptoms = [...form.symptoms];
    if (form.symptoms.includes("Something else") && form.otherSymptoms.trim() !== '') {
        finalSymptoms.push(form.otherSymptoms.trim());
    }

    form.symptoms = finalSymptoms;
    form.post('/app/entry/create');
};
</script>
<template>
    <div class="bg-primary-400 w-full sticky top-0 left-0">
        <div class="pl-app__container py-4">
            <Link href="/app" class="flex items-center gap-2 text-white">
                <Icon icon="heroicons:chevron-left-20-solid" class="w-5 h-5" />
                Home
            </Link>
        </div>
    </div>
    <div class="bg-primary-400 w-full">
        <div class="pl-app__container py-4 text-center">
            <h1 class="fugaz text-2xl text-white">Create New Entry</h1>
        </div>
    </div>
    <div class="pt-8 pl-app__container pb-40">
        <Form class="pl-form gap-y-8!">
            <div class="pl-formgroup">
                <label for="date">Entry date</label>
                <input type="date" v-model="form.date" id="date" name="date" class="pl-input"/>
            </div>
            <div class="pl-formgroup">
                <label for="symptomSeverity">How severe were your symptoms?</label>
                <div
                    class="flex gap-x-2 items-center not-italic"
                    :class="'severity-' + form.symptomSeverity"
                >
                    <span class="text-xl">😄</span>
                    <input type="range" v-model="form.symptomSeverity" id="symptomSeverity" name="symptomSeverity" min="0" max="5" class="w-full"/>
                    <span class="text-xl">🥺</span>
                </div>
            </div>

            <div class="pl-formgroup">
                <label for="symptoms">Which symptoms did you experience?</label>
                <div class="flex flex-wrap gap-2">
                    <label v-for="symptom in symptomOptions" :key="symptom.name" class="pl-app__symptoms-checkbox flex items-center gap-1 p-2 border-2 border-primary-400 bg-primary-50 rounded cursor-pointer transition-colors duration-200">
                        <input type="checkbox" :value="symptom.name" v-model="form.symptoms" class="hidden" />
                        <span class="not-italic leading-0.5">
                            {{ symptom.emoji }}
                        </span>
                        <span class="text-sm">
                            {{ symptom.name }}
                        </span>
                    </label>
                </div>
                <input
                    v-if="form.symptoms.includes('Something else')"
                    type="text"
                    v-model="form.otherSymptoms"
                    id="text"
                    name="otherSymptoms"
                    class="pl-input mt-2"
                    placeholder="Describe other symptoms..."
                />
            </div>
            <div class="pl-formgroup">
                <div class="flex gap-x-4">
                    <Switch
                        v-model="form.medicationTaken"
                        :class="form.medicationTaken ? 'bg-primary-400' : 'bg-gray-200'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full"
                    >
                        <span class="sr-only">Enable notifications</span>
                        <span
                        :class="form.medicationTaken ? 'translate-x-6' : 'translate-x-1'"
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                        />
                    </Switch>
                    <label
                        for="medicationTaken"
                        v-show="form.medicationTaken === true"
                        class="text-primary-400"
                    >
                        Yes, I took medication to relieve my symptoms.
                    </label>
                    <label
                        for="medicationTaken"
                        v-show="form.medicationTaken === false"
                        class="text-gray-400"
                    >
                        No, I didn't take any medication.
                    </label>
                </div>
                <input
                    v-if="form.medicationTaken"
                    type="text"
                    v-model="form.medicationInformation"
                    id="medicationInformation"
                    name="medicationInformation"
                    class="pl-input mt-2"
                    placeholder="(Optional, e.g., Claritin, Zyrtec...)"
                />
            </div>

            <div class="pl-formgroup">
                <label for="notes">Additional notes (optional)</label>
                <textarea
                    id="notes"
                    name="notes"
                    v-model="form.notes"
                    class="pl-input h-24 resize-none"
                    placeholder="Anything else you'd like to add?"
                ></textarea>
            </div>

            <button
                type="submit"
                class="px-4 py-2 bg-primary-400 text-white rounded hover:bg-primary-500 transition-colors duration-200"
                @click.prevent="submitForm"
            >
                Save Entry
            </button>
        </Form>
    </div>
    <BottomBar />
</template>
