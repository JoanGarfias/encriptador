<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import {
  Pagination,
  PaginationContent,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, computed } from 'vue'

defineProps<{
  auth: {
    user: {
      id: number
      name: string
      email: string
      created_at: string
    }
  }
}>()

// Datos simulados
const archivos = ref([
  {
    id: 1,
    content: 'RnJ+Y3cqc...',
    key_url: '/storage/keys/file1.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 2,
    content: 'RnB4JGh5a...',
    key_url: '/storage/keys/file2.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 3,
    content: 'R211cG5fc...',
    key_url: '/storage/keys/file3.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 4,
    content: 'UWyJzah94...',
    key_url: '/storage/keys/file4.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 5,
    content: 'SHBwc3Qhc...',
    key_url: '/storage/keys/file5.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 6,
    content: 'QHdkcS1cH...',
    key_url: '/storage/keys/file6.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 7,
    content: 'TW9ybmhtR...',
    key_url: '/storage/keys/file7.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 8,
    content: 'W9n9tSgQa...',
    key_url: '/storage/keys/file8.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 9,
    content: 'Q2xtcXViV...',
    key_url: '/storage/keys/file9.key',
    created_at: '2025-10-19 07:11:33',
  },
  {
    id: 10,
    content: 'UGVxHV93b...',
    key_url: '/storage/keys/file10.key',
    created_at: '2025-10-19 07:11:33',
  },
])

const searchQuery = ref('')
const itemsPerPage = ref(10)
const currentPage = ref(1)

const filteredData = computed(() =>
  archivos.value.filter((file) =>
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
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}
</script>

<template>
  <AppLayout>
    <Head title="Mi Perfil" />

    <div class="container mx-auto py-8 space-y-6">
      <!-- Perfil -->
      <Card class="shadow-md border-primary/10 bg-gradient-to-br from-background to-primary/5">
        <CardHeader>
          <CardTitle class="text-xl text-primary font-semibold">Información del Usuario</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid sm:grid-cols-3 gap-4 text-sm">
            <div>
              <p class="text-muted-foreground">Nombre</p>
              <p class="font-medium">{{ auth.user.name }}</p>
            </div>
            <div>
              <p class="text-muted-foreground">Correo electrónico</p>
              <p class="font-medium">{{ auth.user.email }}</p>
            </div>
            <div>
              <p class="text-muted-foreground">Miembro desde</p>
              <p class="font-medium">{{ new Date(auth.user.created_at).toLocaleDateString('es-MX') }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Archivos -->
      <Card class="shadow-md border-primary/10 bg-gradient-to-b from-background to-primary/5">
        <CardHeader class="flex flex-col sm:flex-row justify-between items-center gap-3">
          <CardTitle class="text-xl text-primary font-semibold">Mis Archivos Encriptados</CardTitle>
          <Input
            v-model="searchQuery"
            class="sm:w-72 focus:ring-primary"
            placeholder="Buscar contenido..."
          />
        </CardHeader>
        <CardContent>
          <div class="rounded-lg border border-border/40 overflow-hidden">
            <Table>
              <TableHeader class="bg-primary/10">
                <TableRow>
                  <TableHead class="font-semibold">Contenido</TableHead>
                  <TableHead class="font-semibold">Key</TableHead>
                  <TableHead class="font-semibold">Fecha</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="file in paginatedData"
                  :key="file.id"
                  class="hover:bg-primary/5 transition"
                >
                  <TableCell class="font-mono text-sm text-muted-foreground truncate max-w-[300px]">
                    {{ file.content }}
                  </TableCell>
                  <TableCell>
                    <Button variant="outline" size="sm" as-child class="hover:bg-primary/10 hover:text-primary">
                      <a :href="file.key_url" download>Descargar .key</a>
                    </Button>
                  </TableCell>
                  <TableCell class="text-sm text-muted-foreground">
                    {{ new Date(file.created_at).toLocaleString('es-MX') }}
                  </TableCell>
                </TableRow>

                <TableRow v-if="paginatedData.length === 0">
                  <TableCell colspan="3" class="text-center py-8 text-muted-foreground">
                    No se encontraron resultados
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginación -->
<!-- Paginación: reemplaza tu bloque actual por este -->
<div v-if="totalPages > 1" class="pt-6 flex justify-center">
  <Pagination
    :page="currentPage"
    :total="filteredData.length"
    :items-per-page="itemsPerPage"
    @update:page="goToPage"
  >
    <PaginationContent class="flex gap-2 items-center">
      <PaginationPrevious @click="goToPage(currentPage - 1)" />

      <!-- items calculados desde 1 hasta totalPages -->
      <PaginationItem
        v-for="n in totalPages"
        :key="n"
        :value="n"
        :is-active="n === currentPage"
        @click="goToPage(n)"
        class="cursor-pointer rounded-md px-3 py-1 border border-primary/30 hover:bg-primary/10"
      >
        {{ n }}
      </PaginationItem>

      <PaginationNext @click="goToPage(currentPage + 1)" />
    </PaginationContent>
  </Pagination>
</div>

        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
