<!-- resources/js/Components/ProgressBar.vue -->
<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  value: {
    type: Number,
    required: true
  },
  color: {
    type: String,
    default: 'blue-600'
  },
  isInProgress: {
    type: Boolean,
    default: false
  }
});

const progressStyle = computed(() => ({
  width: `${props.value}%`
}));

const progressClass = computed(() => 
  `bg-${props.color} ${props.isInProgress ? 'bg-yellow-600' : ''}`
);
</script>

<style scoped>
.progress-bar {
  transition: width 0.3s ease;
}
</style>
<template>
    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
      <div
        class="h-2.5 rounded-full"
        :class="progressClass"
        :style="progressStyle"
      ></div>
    </div>
  </template>
  