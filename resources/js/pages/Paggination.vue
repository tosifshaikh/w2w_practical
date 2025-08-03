<script setup>
const props = defineProps({
  links: Array
});

const getDisplayLabel = (label) => {
  const mappings = {
    '&laquo; Previous': '← Previous',
    'Next &raquo;': 'Next →',
    '&laquo;': '←',
    '&raquo;': '→'
  };

  return mappings[label] || label;
};
</script>

<template>
  <div class="flex items-center space-x-1">
    <template v-for="(link, index) in links" :key="index">
      <a
        v-if="link.url"
        :href="link.url"
        class="px-3 py-1 rounded border text-sm"
        :class="{
          'bg-blue-500 text-white': link.active,
          'text-gray-700 hover:bg-gray-100': !link.active
        }"
      >
        {{ getDisplayLabel(link.label) }}
      </a>
      <span
        v-else
        class="px-3 py-1 rounded border border-gray-200 text-gray-400 text-sm"
      >
        {{ getDisplayLabel(link.label) }}
      </span>
    </template>
  </div>
</template>
