<template>
    <div class="flex space-x-4">
        <div>
            <InputLabel
                :for="`time-duration-${time.id }`">
                Dauer
            </InputLabel>
            <NumberInput
                :id="`time-duration-${time.id }`"
                v-model.number="time.duration"
                min="0"
                step="any"
            ></NumberInput>
        </div>
        <div>
            <InputLabel
                :for="`time-uom-${time.id}`">
                Einheit
            </InputLabel>
            <select
                :id="`time-uom-${time.id}`"
                v-model="time.uom_id"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                required>
                <option
                    v-for="timeUnitOfMeasure in timeUnitOfMeasures"
                    :value="timeUnitOfMeasure.id"
                >{{ timeUnitOfMeasure.long }}(n)
                </option>
            </select>
        </div>
    </div>
</template>
<script setup>

import InputLabel from "@/Components/InputLabel.vue";
import NumberInput from "@/Components/NumberInput.vue";

const props = defineProps({time: Object, timeUnitOfMeasures: Array})
</script>
