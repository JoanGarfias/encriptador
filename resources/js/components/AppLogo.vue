<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="flex aspect-square size-12 items-center justify-center rounded-md text-sidebar-primary-foreground"
    >
        <img
            src="/logo-dark.png"
            alt="Unicripter"
            class="size-12 block dark:hidden"
        />
        <img
            src="/logo.png"
            alt="Unicripter"
            class="size-12 hidden dark:block"
        />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >Unicripter</span
        >
    </div>
</template>
