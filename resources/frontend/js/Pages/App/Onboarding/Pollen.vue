<script setup>
import { ref, watch } from 'vue'
import { Combobox, ComboboxInput, ComboboxOptions, ComboboxOption } from '@headlessui/vue'
import axios from 'axios'
import { useForm } from '@inertiajs/vue3'
import { Form } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

let allergies = {
    "tree" : "Tree Pollen",
    "grass" : "Grass Pollen",
    "weed" : "Weed Pollen"
}

const form = useForm({
    allergies: ["tree", "grass", "weed"], // Your checkbox array
    location: null // This will hold the Nominatim object
})

const query = ref('') // What they are typing
const locations = ref([]) // The results from Nominatim
const selectedLocation = ref(null) // The final location they click
const isSearching = ref(false)

let timeout = null

watch(query, (newQuery) => {
    // If the input is too short, don't search
    if (newQuery.length < 3) {
        locations.value = []
        return
    }

    clearTimeout(timeout)
    timeout = setTimeout(async () => {
        isSearching.value = true
        try {
            // Nominatim API call (format=json ensures we get a JS object back)
            const response = await axios.get('https://nominatim.openstreetmap.org/search', {
                params: {
                    q: newQuery,
                    format: 'json',
                    addressdetails: 1,
                    limit: 5,
                    featuretype: 'settlement',
                    'accept-language': 'en' // TODO: Localize this based on user preference later on
                }
            })
            locations.value = response.data
        } catch (error) {
            console.error('Nominatim error:', error)
        } finally {
            isSearching.value = false
        }
    }, 500) // Wait 500ms after they stop typing before searching
})

function formatLocationName(location) {
    if (!location?.address) return location?.display_name || '';

    // Grab the most accurate "city" equivalent
    const city = location.address.city ||
                 location.address.town ||
                 location.address.village ||
                 location.address.municipality;

    const country = location.address.country;

    // If we have both, format as "Zurich, Switzerland"
    if (city && country) {
        return `${city}, ${country}`;
    }

    // Fallback just in case
    return location.display_name;
}

// 4. When they select an option, update the Inertia form
watch(selectedLocation, (newLocation) => {
    if (newLocation) {
        form.location = {
            name: formatLocationName(newLocation),
            lat: newLocation.lat,
            lon: newLocation.lon
        }
    }
})

function submit() {
    form.post('/onboarding/pollen')
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
                        <div class="pl-onboarding--progress__bar__path w-full h-1 bg-gray-400"></div>
                        <div class="pl-onboarding--progress__bar__step bg-gray-400"></div>
                    </div>
                    <div class="pl-onboarding--progess__lables grid grid-cols-3 mt-2 text-sm">
                        <span class="text-sm text-primary-400">Create Account</span>
                        <span class="text-sm text-primary-400 text-center">Allergies</span>
                        <span class="text-sm text-gray-500 text-end">Reminders</span>
                    </div>
                </div>
                <div class="pl-onboarding--form mt-8">
                    <Form class="pl-form" @submit.prevent="submit">
                        <div class="pl-formgroup">
                            <label>Which types of pollen are you allergic to?</label>
                            <div class="flex flex-wrap gap-x-4 gap-y-1">
                                <div v-for="(allergy, key) in allergies" :key="key">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            :value="key"
                                            v-model="form.allergies"
                                            class="hidden"
                                        />
                                        <Icon
                                            :icon="form.allergies.includes(key) ? 'heroicons-solid:check-circle' : 'heroicons-solid:plus-circle'"
                                            class="w-5 h-5"
                                            :class="form.allergies.includes(key) ? 'text-primary-400' : 'text-gray-400'"
                                        />
                                        <span class="text-gray-700">{{ allergy }}</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="pl-formgroup">
                            <label for="location">Where are you located?</label>
                            <Combobox v-model="selectedLocation">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-pollenpink focus:border-pollenpink"
                                        @change="query = $event.target.value"
                                        :displayValue="(loc) => formatLocationName(loc)"
                                        placeholder="Search for your city..."
                                    />

                                    <div v-if="isSearching" class="absolute right-3 top-2.5 text-gray-400 text-sm">
                                        Searching...
                                    </div>

                                    <ComboboxOptions class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto">
                                        <div v-if="locations.length === 0 && query.length >= 3 && !isSearching" class="px-4 py-2 text-gray-500">
                                            No locations found.
                                        </div>

                                        <ComboboxOption
                                            v-for="location in locations"
                                            :key="location.place_id"
                                            :value="location"
                                            v-slot="{ active, selected }"
                                        >
                                            <li
                                                :class="[
                                                    'px-4 py-2 cursor-pointer transition-colors',
                                                    active ? 'bg-primary-50 text-primary-700' : 'text-gray-900',
                                                    selected ? 'font-bold' : ''
                                                ]"
                                            >
                                                {{ formatLocationName(location) }}
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="pl-button inline-flex w-fit items-center gap-2 mt-6 px-10 py-2 bg-secondary-500 text-white rounded-md text-lg md:text-xl lg:text-2xl hover:bg-secondary-600 transition"
                                :class="!form.location
                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                    : 'bg-secondary-500 text-white hover:bg-secondary-600'"
                                :disabled="!form.location"
                            >
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
