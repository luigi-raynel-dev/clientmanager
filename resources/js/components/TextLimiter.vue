<script setup lang="ts">
import { ref, type HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"

const props = defineProps<{
  class?: HTMLAttributes["class"]
  maxLength: number
  text: string
}>()

const showMore = ref(false)
</script>

<template>
  <p :class="cn('text-muted-foreground text-sm whitespace-pre-line', props.class)">
    {{ showMore ? text : text.length > maxLength ? text.substring(0, maxLength) + '...' : text }}
    <button v-if="text.length > maxLength" class="ml-1 text-primary cursor-pointer" @click="showMore = !showMore">
      {{ showMore ? 'Show Less' : 'Show More' }}
    </button>
  </p>
</template>
