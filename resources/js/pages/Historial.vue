<script setup lang="ts">
import { ref, computed } from "vue"

const props = defineProps<{ data: any; perPage: number }>()

// registros 
const archivos = computed(() => {
  const items = props.data && props.data.data ? props.data.data : []
  return items.map((it: any) => ({
    id: it.id,
    content: it.content,
    created_at: it.created_at,
  }))
})

// búsqueda sencilla (filtra los registros de la página actual)
const search = ref("")

const archivosFiltrados = computed(() => {
  const q = search.value.trim().toLowerCase()
  if (!q) return archivos.value
  return archivos.value.filter((a: any) => (a.content || "").toLowerCase().includes(q))
})

// Links de paginación 
const paginationLinks = computed(() => (props.data && props.data.links ? props.data.links : []))
</script>

<template>
  <div class="max-w-6xl mx-auto mt-8">
    <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h1 class="text-2xl font-semibold text-indigo-700">🔐 Archivos Encriptados</h1>
          <p class="text-sm text-gray-500">Listado de archivos procesados recientemente</p>
        </div>

        <div class="flex items-center gap-3">
          <div class="relative">
            <input
              v-model="search"
              type="search"
              placeholder="🔎 Buscar contenido..."
              class="w-72 px-4 py-2 rounded-lg border bg-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
            />
          </div>
          <div class="text-sm text-gray-500">Mostrando {{ archivosFiltrados.length }} / {{ archivos.length }}</div>
        </div>
      </div>

      <div class="overflow-hidden rounded-xl border border-indigo-100">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-indigo-50">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-medium text-indigo-700">Contenido</th>
              <th class="px-6 py-3 text-center text-sm font-medium text-indigo-700">Key</th>
              <th class="px-6 py-3 text-right text-sm font-medium text-indigo-700">Fecha</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="file in archivosFiltrados" :key="file.id" class="hover:bg-indigo-50/30">
              <td class="px-6 py-4 break-words font-mono text-sm text-gray-800">{{ file.content }}</td>
              <td class="px-6 py-4 text-center">
                <a
                  :href="`/history/download/${file.id}`"
                  class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-indigo-200 bg-white text-indigo-600 hover:bg-indigo-50 text-sm"
                  target="_blank"
                  rel="noopener"
                >
                  🔑 Descargar .key
                </a>
              </td>
              <td class="px-6 py-4 text-right text-sm text-gray-500">{{ file.created_at }}</td>
            </tr>

            <tr v-if="archivosFiltrados.length === 0">
              <td colspan="3" class="text-center py-8 text-gray-400">No hay registros</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <nav v-if="paginationLinks.length > 0" class="mt-4 flex justify-center items-center space-x-2">
        <template v-for="(link, idx) in paginationLinks" :key="idx">
          <a
            v-if="link.url"
            :href="link.url"
            class="px-3 py-1 rounded border text-sm"
            :class="link.active ? 'bg-indigo-600 text-white' : 'text-indigo-600 bg-white hover:bg-indigo-50'"
            v-html="link.label"
          ></a>
          <span v-else class="px-3 py-1 rounded text-sm text-gray-400" v-html="link.label"></span>
        </template>
      </nav>
    </div>
  </div>
</template>