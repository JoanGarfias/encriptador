
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
  filename: string
  key: string
  timestamp: string
}

const allFiles = ref<EncryptedFile[]>([
  {
    id: 1,
    filename: "documento1.txt",
    key: "documento1_key.key",
    timestamp: "2025-10-18 18:45",
  },
  {
    id: 2,
    filename: "reporte_final.pdf",
    key: "reporte_final_key.key",
    timestamp: "2025-10-17 22:10",
  },
  {
    id: 3,
    filename: "imagen_secreta.png",
    key: "imagen_secreta_key.key",
    timestamp: "2025-10-16 14:30",
  },
  {
    id: 4,
    filename: "plan_estrategico.docx",
    key: "plan_estrategico_key.key",
    timestamp: "2025-10-15 09:20",
  },
  {
    id: 5,
    filename: "datos_confidenciales.csv",
    key: "datos_confidenciales_key.key",
    timestamp: "2025-10-14 11:05",
  },
])

const searchQuery = ref("")
const itemsPerPage = ref(3)
const currentPage = ref(1)

const filteredData = computed(() => {
  return allFiles.value.filter((file) =>
    file.filename.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const totalPages = computed(() =>
  Math.ceil(filteredData.value.length / itemsPerPage.value)
)

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return filteredData.value.slice(start, start + itemsPerPage.value)
})

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

type PaginationItemType =
  | { type: "page"; value: number }
  | { type: "ellipsis" }

const paginationItems = computed<PaginationItemType[]>(() => {
  const items: PaginationItemType[] = []
  const total = totalPages.value
  const current = currentPage.value

  if (total === 0) return items

  items.push({ type: "page", value: 1 })

  if (current > 3) {
    items.push({ type: "ellipsis" })
  }

  const start = Math.max(2, current - 1)
  const end = Math.min(total - 1, current + 1)

  for (let i = start; i <= end; i++) {
    if (i > 1 && i < total) {
      items.push({ type: "page", value: i })
    }
  }

  if (current < total - 2) {
    items.push({ type: "ellipsis" })
  }

  if (total > 1) {
    items.push({ type: "page", value: total })
  }

  return items
})
</script>

<template>
  <div class="flex flex-col gap-4 bg-background/50 p-4">
    <div class="flex flex-col sm:flex-row gap-3 items-center">
      <Input
        v-model="searchQuery"
        class="w-full sm:max-w-sm"
        placeholder="Buscar archivo..."
      />
    </div>

    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>Archivo</TableHead>
          <TableHead>Clave</TableHead>
          <TableHead>Fecha</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow
          v-for="file in paginatedData"
          :key="file.id"
          class="hover:bg-muted/40"
        >
          <TableCell class="flex items-center gap-2">
            {{ file.filename }}
            <a
              :href="`/downloads/${file.filename}`"
              title="Descargar archivo"
              class="text-green-600 hover:text-green-800"
            >
              📄
            </a>
          </TableCell>
          <TableCell class="flex items-center gap-2">
            <span class="font-mono text-blue-600">{{ file.key }}</span>
            <a
              :href="`/downloads/${file.key}`"
              title="Descargar clave"
              class="text-purple-600 hover:text-purple-800"
            >
              🔑
            </a>
          </TableCell>
          <TableCell>{{ file.timestamp }}</TableCell>
        </TableRow>

        <TableRow v-if="paginatedData.length === 0">
          <TableCell colspan="3" class="text-center py-8 text-muted-foreground">
            No se encontraron archivos
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <div v-if="totalPages > 1" class="border-t pt-4">
  <Pagination
  :page="currentPage"
  :total="filteredData.length"
  :items-per-page="itemsPerPage"
  @update:page="goToPage"
  class="justify-center"
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
      <PaginationEllipsis
        v-else-if="item.type === 'ellipsis'"
        :key="`ellipsis-${index}`"
      />
    </template>

    <PaginationNext @click="goToPage(currentPage + 1)" />
  </PaginationContent>
</Pagination>
    </div>
  </div>
</template>