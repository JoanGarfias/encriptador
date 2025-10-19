<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

import { Input } from "@/components/ui/input"
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from "@/components/ui/pagination"
import { ref, computed } from "vue"

type EncryptedFile = {
  id: number
  content: string
  key: string
  timestamp: string
}

// Datos que vendrían del backend
const allFiles = ref<EncryptedFile[]>([
  {
    id: 1,
    content: "RnJ+Y3cqcnZiZ3NecSF3dHcianB5KypDeX0hamUiZl5udm4ldnFxYnhxc199KmRwbm9wYWYv",
    key: "Descargar .key",
    timestamp: "2025-10-19 07:11:33",
  },
  {
    id: 2,
    content: "RnB4JGh5a2xtZG4fa2VmbnZkXHlxZZxVJGo5ZGlodHJocXNyJGNmd3lkcmcd4c24r",
    key: "Descargar .key",
    timestamp: "2025-10-19 07:11:33",
  },
  {
    id: 3,
    content: "R211Gc5q5qdB9mcWl2G2V9jV9Jn1cY9jfSFqdHh4cyJgcnJyyKw==",
    key: "Descargar .key",
    timestamp: "2025-10-19 07:11:33",
  },
  {
    id: 4,
    content: "UWJzah942nc3bNpdG91NkbmhrbSppbn15fHBqJG50biFeczI=",
    key: "Descargar .key",
    timestamp: "2025-10-19 07:11:33",
  },
])

const searchQuery = ref("")
const itemsPerPage = ref(12)
const currentPage = ref(1)

const filteredData = computed(() =>
  allFiles.value.filter((file) =>
    file.content.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

const totalPages = computed(() =>
  Math.ceil(filteredData.value.length / itemsPerPage.value)
)

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return filteredData.value.slice(start, start + itemsPerPage.value)
})

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) currentPage.value = page
}

type PaginationItemType = { type: "page"; value: number } | { type: "ellipsis" }

const paginationItems = computed<PaginationItemType[]>(() => {
  const items: PaginationItemType[] = []
  const total = totalPages.value
  const current = currentPage.value
  if (total === 0) return items

  items.push({ type: "page", value: 1 })
  if (current > 3) items.push({ type: "ellipsis" })

  const start = Math.max(2, current - 1)
  const end = Math.min(total - 1, current + 1)
  for (let i = start; i <= end; i++) {
    if (i > 1 && i < total) items.push({ type: "page", value: i })
  }

  if (current < total - 2) items.push({ type: "ellipsis" })
  if (total > 1) items.push({ type: "page", value: total })

  return items
})
</script>

<template>
  <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-zinc-900 dark:to-indigo-950 rounded-2xl p-6 shadow-lg transition-all duration-300">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-5">
      <div>
        <h2 class="text-xl font-bold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
          🔐 Archivos Encriptados
        </h2>
        <p class="text-sm text-muted-foreground">Listado de archivos procesados recientemente</p>
      </div>
      <Input v-model="searchQuery" placeholder="🔍 Buscar contenido..." class="w-full sm:w-72 border-indigo-200 focus-visible:ring-indigo-400" />
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto rounded-xl border border-indigo-100 dark:border-zinc-700">
      <Table class="min-w-[720px]">
        <TableHeader class="bg-indigo-100/70 dark:bg-indigo-900/40 text-indigo-900 dark:text-indigo-100">
          <TableRow>
            <TableHead class="w-1/2 font-semibold">Contenido</TableHead>
            <TableHead class="w-1/4 font-semibold">Key</TableHead>
            <TableHead class="w-1/4 text-right font-semibold">Fecha</TableHead>
          </TableRow>
        </TableHeader>

        <TableBody>
          <TableRow
            v-for="file in paginatedData"
            :key="file.id"
            class="hover:bg-indigo-50/70 dark:hover:bg-indigo-950/40 transition-colors group"
          >
            <!-- Contenido -->
            <TableCell class="font-mono text-sm text-gray-800 dark:text-gray-200 truncate">
              <span class="inline-block max-w-[380px]">{{ file.content }}</span>
            </TableCell>

            <!-- Key -->
            <TableCell>
              <button
                class="px-3 py-1 text-sm font-medium rounded-md border border-indigo-300 dark:border-indigo-700
                       bg-white dark:bg-zinc-800 text-indigo-700 dark:text-indigo-300
                       hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 transition-all duration-200"
              >
                🔑 {{ file.key }}
              </button>
            </TableCell>

            <!-- Fecha -->
            <TableCell class="text-right text-sm text-gray-600 dark:text-gray-400">
              {{ file.timestamp }}
            </TableCell>
          </TableRow>

          <TableRow v-if="paginatedData.length === 0">
            <TableCell colspan="3" class="text-center py-8 text-muted-foreground">
              No se encontraron archivos
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Paginación -->
    <div v-if="totalPages > 1" class="mt-5 flex justify-center">
      <Pagination
        :page="currentPage"
        :total="filteredData.length"
        :items-per-page="itemsPerPage"
        @update:page="goToPage"
      >
        <PaginationContent>
          <PaginationPrevious @click="goToPage(currentPage - 1)" />
          <template v-for="(item, index) in paginationItems" :key="index">
            <PaginationItem
              v-if="item.type === 'page'"
              :value="item.value"
              :is-active="item.value === currentPage"
              @click="goToPage(item.value)"
            >
              {{ item.value }}
            </PaginationItem>
            <PaginationEllipsis v-else :key="`ellipsis-${index}`" />
          </template>
          <PaginationNext @click="goToPage(currentPage + 1)" />
        </PaginationContent>
      </Pagination>
    </div>
  </div>
</template>
