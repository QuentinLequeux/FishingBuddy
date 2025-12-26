<script setup lang="ts">
import dayjs from 'dayjs';

defineProps<{
    datetime: string;
}>();
</script>

<template>
    <time
        class="w-fit cursor-help text-xs text-gray-500"
        :datetime="datetime"
        :title="`${new Date(datetime).toLocaleDateString('fr-BE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}`"
    >
        {{ dayjs(datetime).fromNow() }}
    </time>
</template>
