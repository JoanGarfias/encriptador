<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Progress } from '@/components/ui/progress';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';
import { Head, Link } from '@inertiajs/vue3';

// Refs for UI state
const activeTab = ref('encrypt');
const encryptFile = ref<File | null>(null);
const decryptFile = ref<File | null>(null);
const keyFile = ref<File | null>(null);
const isLoading = ref(false);
const progress = ref(0);
const showEncryptSuccessModal = ref(false);
const showDecryptSuccessModal = ref(false);
const decryptedText = ref('Este es el texto desencriptado de prueba.');

// File handling
const handleEncryptFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files[0] && target.files[0].type === 'text/plain') {
    encryptFile.value = target.files[0];
  } else {
    alert('Por favor, selecciona un archivo .txt');
    target.value = ''; // Reset input
  }
};

const handleDecryptFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files[0] && target.files[0].type === 'text/plain') {
    decryptFile.value = target.files[0];
  } else {
    alert('Por favor, selecciona un archivo .txt');
    target.value = '';
  }
};

const handleKeyFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files[0] && target.files[0].name.endsWith('.key')) {
    keyFile.value = target.files[0];
  } else {
    alert('Por favor, selecciona un archivo .key');
    target.value = '';
  }
};


// Mock processing functions
const startProcessing = (callback: () => void) => {
  isLoading.value = true;
  progress.value = 0;
  const interval = setInterval(() => {
    progress.value += 10;
    if (progress.value >= 100) {
      clearInterval(interval);
      isLoading.value = false;
      callback();
    }
  }, 200);
};

const handleEncrypt = () => {
  if (!encryptFile.value) {
    alert('Por favor, selecciona un archivo para encriptar.');
    return;
  }
  startProcessing(() => {
    showEncryptSuccessModal.value = true;
  });
};

const handleDecrypt = () => {
  if (!decryptFile.value || !keyFile.value) {
    alert('Por favor, selecciona el archivo .txt y el archivo .key para desencriptar.');
    return;
  }
  startProcessing(() => {
    showDecryptSuccessModal.value = true;
  });
};

const copyToClipboard = () => {
  navigator.clipboard.writeText(decryptedText.value).then(() => {
    alert('¡Texto copiado al portapapeles!');
  });
};
</script>

<template>
    <Head title="Encriptador de Archivos" />

    <nav class="w-full pt-4">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Left: Logo -->
          <div class="flex flex-row items-center space-x-3">
            <Link href="/">
              <img src="logo.png" alt="Logo" class="h-20 w-20" />
            </Link>
            <h1 class="text-2xl font-bold">Unicripter</h1>
          </div>

          <!-- Right: Auth buttons -->
          <div class="hidden sm:flex sm:items-center sm:space-x-3">
            <Link href="/login">
              <Button>Iniciar sesión</Button>
            </Link>
            <Link href="/register">
              <Button variant="secondary">Registrarse</Button>
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <div class="container mx-auto flex min-h-screen flex-col items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-2xl">
            <!-- Title and Subtitle -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold tracking-tight lg:text-5xl">
                    Tus archivos de texto encriptados de forma sencilla.
                </h1>
                <p class="mt-4 text-lg text-muted-foreground">
                  Comienza a encriptar y desencriptar archivos de texto y almacenalos en la nube 100% gratis.
                </p>
            </div>

            <!-- Tabs for Encrypt/Decrypt -->
            <Tabs v-model="activeTab" class="w-full">
                <TabsList class="grid w-full grid-cols-2">
                    <TabsTrigger value="encrypt">Encriptar</TabsTrigger>
                    <TabsTrigger value="decrypt">Desencriptar</TabsTrigger>
                </TabsList>

                <!-- Encrypt Panel -->
                <TabsContent value="encrypt" class="mt-6">
                    <div class="flex flex-col gap-4 rounded-lg border p-6">
                        <h3 class="text-lg font-semibold">Subir archivo para encriptar</h3>
                        <p class="text-sm text-muted-foreground">
                            Arrastra o selecciona un archivo .txt para encriptarlo.
                        </p>
                        <Input id="encrypt-file" type="file" accept=".txt" @change="handleEncryptFileChange" />
                        <Button @click="handleEncrypt" :disabled="isLoading || !encryptFile">
                            {{ isLoading ? 'Procesando...' : 'Subir y Encriptar' }}
                        </Button>
                        <Progress v-if="isLoading" v-model="progress" class="w-full mt-2" />
                    </div>
                </TabsContent>

                <!-- Decrypt Panel -->
                <TabsContent value="decrypt" class="mt-6">
                    <div class="flex flex-col gap-4 rounded-lg border p-6">
                        <h3 class="text-lg font-semibold">Subir archivos para desencriptar</h3>
                        <p class="text-sm text-muted-foreground">
                           Sube el archivo .txt y el archivo .key correspondiente.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Input id="decrypt-file" type="file" accept=".txt" @change="handleDecryptFileChange" />
                            <Input id="key-file" type="file" accept=".key" @change="handleKeyFileChange" />
                        </div>
                        <Button @click="handleDecrypt" :disabled="isLoading || !decryptFile || !keyFile">
                            {{ isLoading ? 'Procesando...' : 'Desencriptar Archivo' }}
                        </Button>
                        <Progress v-if="isLoading" v-model="progress" class="w-full mt-2" />
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Encrypt Success Modal -->
        <Dialog v-model:open="showEncryptSuccessModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>¡Éxito!</DialogTitle>
                    <DialogDescription>
                        Archivo encriptado correctamente.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button @click="showEncryptSuccessModal = false">Cerrar</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Decrypt Success Modal -->
        <Dialog v-model:open="showDecryptSuccessModal">
            <DialogContent class="max-w-lg">
                <DialogHeader>
                    <DialogTitle>¡Éxito!</DialogTitle>
                    <DialogDescription>
                        Tu archivo ha sido desencriptado.
                    </DialogDescription>
                </DialogHeader>
                <div class="my-4">
                    <Textarea :model-value="decryptedText" readonly rows="10" class="w-full" />
                </div>
                <DialogFooter>
                    <Button @click="copyToClipboard">Copiar Contenido</Button>
                    <Button variant="secondary" @click="showDecryptSuccessModal = false">Cerrar</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>