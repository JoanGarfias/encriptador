<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import AppLayout from '@/layouts/AppLayout.vue'
import { onMounted, ref, computed, watch } from 'vue'
import axios from 'axios'

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

interface Archivo {
  id: number
  content: string
  key_url: string
  created_at: string
}

// Datos reactivos
const archivos = ref<Archivo[]>([])
const paginationLinks = ref<any[]>([])
const searchQuery = ref('')
const itemsPerPage = ref(10)
const currentPage = ref(1)
const totalItems = ref(0)

const totalPages = computed(() =>
  Math.ceil(totalItems.value / itemsPerPage.value)
)

// Obtener los archivos del backend
const fetchArchivos = async () => {
  try {
    const response = await axios.get('/history', {
      params: {
        page: currentPage.value,
        perPage: itemsPerPage.value,
        search: searchQuery.value,
      },
    })

    archivos.value = response.data.data.map((item: any) => ({
      ...item,
      key_url: `/history/download/llaves/${item.id}.key`,
      txt_url: `/history/download/encriptado/${item.id}.txt`,
    }))

    totalItems.value = response.data.total
    paginationLinks.value = response.data.links || []
  } catch (error) {
    console.error('Error al obtener los archivos:', error)
  }
}

// Cambiar de página
const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

// Cargar al iniciar
onMounted(fetchArchivos)

// Re-fetch al cambiar página o búsqueda
watch([currentPage, searchQuery, itemsPerPage], fetchArchivos)

const paginatedData = computed(() => archivos.value)
</script>

<template>
  <AppLayout>
    <Head title="Mi Perfil" />

    <div class="container mx-auto py-8 space-y-6">
      <!-- Perfil -->
      <Card class="shadow-md border-primary/10 bg-gradient-to-br from-background to-primary/5">
        <CardHeader>
          <CardTitle class="text-xl text-primary font-semibold">
            Información del Usuario
          </CardTitle>
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
              <p class="font-medium">
                {{ new Date(auth.user.created_at).toLocaleDateString('es-MX') }}
              </p>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Archivos -->
      <Card class="shadow-md border-primary/10 bg-gradient-to-b from-background to-primary/5">
        <CardHeader
          class="flex flex-col sm:flex-row justify-between items-center gap-3"
        >
          <CardTitle class="text-xl text-primary font-semibold">
            Mis Archivos Encriptados
          </CardTitle>
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
                  <!-- Contenido -->
                  <TableCell
                    class="font-mono text-sm text-muted-foreground max-w-[250px] whitespace-nowrap overflow-hidden text-ellipsis"
                    :title="file.content"
                  >
                    <Button
                      variant="outline"
                      size="sm"
                      as-child
                      class="hover:bg-primary/10 hover:text-primary mr-2"
                    >
                      <a :href="file.txt_url" download>Descargar .txt</a>
                    </Button>
                    {{ file.content }}
                  </TableCell>

                  <!-- Llave -->
                  <TableCell>
                    <Button
                      variant="outline"
                      size="sm"
                      as-child
                      class="hover:bg-primary/10 hover:text-primary"
                    >
                      <a :href="file.key_url" download>Descargar .key</a>
                    </Button>
                  </TableCell>

                  <!-- Fecha -->
                  <TableCell class="text-sm text-muted-foreground">
                    {{ new Date(file.created_at).toLocaleString('es-MX') }}
                  </TableCell>
                </TableRow>

                <TableRow v-if="paginatedData.length === 0">
                  <TableCell
                    colspan="3"
                    class="text-center py-8 text-muted-foreground"
                  >
                    No se encontraron resultados
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginación -->
          <nav
            v-if="paginationLinks.length > 0"
            class="mt-6 flex justify-center items-center flex-wrap gap-2"
          >
            <template v-for="(link, idx) in paginationLinks" :key="idx">
              <a
                v-if="link.url"
                @click.prevent="
                  link.url && goToPage(Number(link.url.split('page=')[1]))
                "
                href="#"
                class="px-3 py-1 rounded border text-sm transition-all select-none"
                :class="link.active
                  ? 'bg-primary text-white border-primary shadow-sm'
                  : 'text-primary bg-white hover:bg-primary/10 border-primary/30'"
                v-html="link.label"
              ></a>

              <span
                v-else
                class="px-3 py-1 rounded text-sm text-gray-400 select-none"
                v-html="link.label"
              ></span>
            </template>
          </nav>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
