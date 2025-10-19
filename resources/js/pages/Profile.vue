<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
  auth: {
    user: {
      id: number;
      name: string;
      email: string;
      created_at: string;
    };
  };
}>();

// Datos de prueba para la tabla
const archivos = [
  {
    id: 1,
    created_at: '2025-10-19 14:30:00',
    content_url: '/storage/encrypted/file1.txt',
    key_url: '/storage/keys/file1.key'
  },
  {
    id: 2,
    created_at: '2025-10-19 15:45:00',
    content_url: '/storage/encrypted/file2.txt',
    key_url: '/storage/keys/file2.key'
  },
  {
    id: 3,
    created_at: '2025-10-19 16:20:00',
    content_url: '/storage/encrypted/file3.txt',
    key_url: '/storage/keys/file3.key'
  }
];
</script>

<template>
  <AppLayout>
    <Head title="Mi Perfil" />

    <div class="container mx-auto py-6 space-y-6">
      <!-- Perfil del usuario -->
      <Card>
        <CardHeader>
          <CardTitle>Información del Usuario</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid gap-4">
            <div>
              <p class="text-sm font-medium text-muted-foreground">Nombre</p>
              <p class="text-lg">{{ auth.user.name }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Correo electrónico</p>
              <p class="text-lg">{{ auth.user.email }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Miembro desde</p>
              <p class="text-lg">{{ new Date(auth.user.created_at).toLocaleDateString('es-MX') }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Tabla de archivos -->
      <Card>
        <CardHeader>
          <CardTitle>Mis Archivos Encriptados</CardTitle>
        </CardHeader>
        <CardContent>
          <Table>
            <TableCaption>Lista de tus archivos encriptados</TableCaption>
            <TableHeader>
              <TableRow>
                <TableHead>Fecha de creación</TableHead>
                <TableHead>Contenido</TableHead>
                <TableHead>Llave</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="archivo in archivos" :key="archivo.id">
                <TableCell>{{ new Date(archivo.created_at).toLocaleString('es-MX') }}</TableCell>
                <TableCell>
                  <Button variant="outline" size="sm" @click="() => window.open(archivo.content_url)">
                    Descargar contenido
                  </Button>
                </TableCell>
                <TableCell>
                  <Button variant="outline" size="sm" @click="() => window.open(archivo.key_url)">
                    Descargar key
                  </Button>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>