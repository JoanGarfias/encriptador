<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Textarea } from '@/components/ui/textarea';
import { Head, Link, router } from '@inertiajs/vue3';

// --- Props de autenticación ---
const props = defineProps<{
  auth: {
    user: null | { id: number; name: string; email: string };
  };
}>();


// --- Navegación ---
const handleAuthClick = (path: string) => {
  props.auth.user ? router.visit('/perfil') : router.visit(path);
};

// --- Tema (oscuro/claro) ---

// --- Auth y tema ---
const handleAuthClick = (path: string) => {
  if (props.auth.user) router.visit('/perfil');
  else router.visit(path);
};

const isMenuOpen = ref(false);

const theme = ref<'light' | 'dark'>('light');

const toggleTheme = () => {
  theme.value = theme.value === 'light' ? 'dark' : 'light';
  document.documentElement.classList.toggle('dark', theme.value === 'dark');
  localStorage.setItem('theme', theme.value);
};

onMounted(() => {
  const saved = localStorage.getItem('theme');
  theme.value = saved === 'dark' ? 'dark' : 'light';
  document.documentElement.classList.toggle('dark', theme.value === 'dark');
});


// --- Estado general ---
// --- Variables de UI ---

const activeTab = ref('encrypt');
const encryptFile = ref<File | null>(null);
const decryptFile = ref<File | null>(null);
const keyFile = ref<File | null>(null);
const isLoading = ref(false);
const progress = ref(0);
const decryptedText = ref('Este es el texto desencriptado de prueba.');
const showEncryptSuccessModal = ref(false);
const showDecryptSuccessModal = ref(false);

// --- Drag & Drop Encriptar ---
const isDragging = ref(false);
const fileName = ref('');

const encryptInput = ref<HTMLInputElement | null>(null);

const encryptedFileName = ref('');
const keyFileName = ref('');
const downloadReady = ref(false);


// --- Drag & Drop ---
const handleDrop = (e: DragEvent) => {
  e.preventDefault();
  isDragging.value = false;
  const file = e.dataTransfer?.files?.[0];
  if (file && file.type === 'text/plain') {
    encryptFile.value = file;
    fileName.value = file.name;
  } else {
    alert('Por favor, selecciona un archivo .txt');
  }
};

const handleDragOver = (e: DragEvent) => {
  e.preventDefault();
  isDragging.value = true;
};
const handleDragLeave = () => (isDragging.value = false);


const handleEncryptFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const file = target.files?.[0];
  if (file && file.type === 'text/plain') {
    encryptFile.value = file;
    fileName.value = file.name;

// --- Manejo de inputs ---
const handleEncryptFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files[0] && target.files[0].type === 'text/plain') {
    encryptFile.value = target.files[0];
    fileName.value = target.files[0].name;

  } else {
    alert('Por favor, selecciona un archivo .txt');
    target.value = '';
  }
};

// --- Drag & Drop Desencriptar ---
const isDraggingTxt = ref(false);
const isDraggingKey = ref(false);
const decryptTxtInput = ref<HTMLInputElement | null>(null);
const keyInput = ref<HTMLInputElement | null>(null);

const handleTxtDrop = (event: DragEvent) => {
  event.preventDefault();
  isDraggingTxt.value = false;
  const file = event.dataTransfer?.files?.[0];
  if (file && file.name.endsWith('.txt')) decryptFile.value = file;
  else alert('Por favor, selecciona un archivo .txt');
};

const handleKeyDrop = (event: DragEvent) => {
  event.preventDefault();
  isDraggingKey.value = false;
  const file = event.dataTransfer?.files?.[0];
  if (file && file.name.endsWith('.key')) keyFile.value = file;
  else alert('Por favor, selecciona un archivo .key');
};

const handleDecryptFileChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file && file.name.endsWith('.txt')) decryptFile.value = file;
  else alert('Por favor, selecciona un archivo .txt');
};

const handleKeyFileChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file && file.name.endsWith('.key')) keyFile.value = file;
  else alert('Por favor, selecciona un archivo .key');
};


// --- Simulación de proceso (mock) ---
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
  if (!encryptFile.value) return alert('Selecciona un archivo para encriptar.');
  startProcessing(() => (showEncryptSuccessModal.value = true));

// --- Encriptar archivo ---
const handleEncrypt = async () => {
  if (!encryptFile.value) {
    alert('Por favor, selecciona un archivo para encriptar.');
    return;
  }

  const formData = new FormData();
  formData.append('user_file', encryptFile.value);

  isLoading.value = true;
  progress.value = 0;

  // Simular barra de progreso mientras espera respuesta
  const interval = setInterval(() => {
    progress.value = Math.min(progress.value + 15, 90);
  }, 200);

  try {
    const response = await fetch('/encriptar2', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      },
      body: formData
    });

    if (!response.ok) {
      throw new Error(`Error ${response.status}: ${response.statusText}`);
    }

    const data = await response.json();

    if (!data.filename || !data.key) {
      throw new Error('El servidor no devolvió los archivos esperados.');
    }

    encryptedFileName.value = data.filename;
    keyFileName.value = data.key;
    downloadReady.value = true;
    showEncryptSuccessModal.value = true;
    progress.value = 100;
  } catch (err) {
    console.error('Error al encriptar:', err);
    alert('Ocurrió un error durante la encriptación. Intenta nuevamente.');
  } finally {
    clearInterval(interval);
    isLoading.value = false;
  }

};

// --- Desencriptar archivo ---
const handleDecrypt = () => {

  if (!decryptFile.value || !keyFile.value)
    return alert('Selecciona el archivo .txt y .key para desencriptar.');
  startProcessing(() => (showDecryptSuccessModal.value = true));

  if (!decryptFile.value || !keyFile.value) {
    alert('Por favor, selecciona el archivo .txt y el archivo .key para desencriptar.');
    return;
  }

  isLoading.value = true;
  progress.value = 0;

  const interval = setInterval(() => {
    progress.value += 10;
    if (progress.value >= 100) {
      clearInterval(interval);
      isLoading.value = false;
      showDecryptSuccessModal.value = true;
    }
  }, 200);

};

// --- Copiar texto desencriptado ---
const copyToClipboard = () => {
  navigator.clipboard.writeText(decryptedText.value).then(() =>
    alert('¡Texto copiado al portapapeles!')
  );
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
              <img src="logo2.png" alt="Logo" class="h-15 w-15" />
            </Link>
            <h1 class="text-2xl font-bold">Unicripter</h1>
          </div>

          <!-- Desktop: Auth buttons -->
          <div class="hidden sm:flex sm:items-center sm:space-x-3">
            <button @click="toggleTheme" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
              <span v-if="theme === 'light'" aria-hidden>
                <!-- moon icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.293 13.293A8 8 0 116.707 2.707a7 7 0 0010.586 10.586z"/></svg>
              </span>
              <span v-else aria-hidden>
                <!-- clearer sun icon (Heroicons outline) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="4"></circle>
                  <path d="M12 2v2"></path>
                  <path d="M12 20v2"></path>
                  <path d="M4.93 4.93l1.41 1.41"></path>
                  <path d="M17.66 17.66l1.41 1.41"></path>
                  <path d="M2 12h2"></path>
                  <path d="M20 12h2"></path>
                  <path d="M4.93 19.07l1.41-1.41"></path>
                  <path d="M17.66 6.34l1.41-1.41"></path>
                </svg>
              </span>
            </button>
            <Button @click="handleAuthClick('/login')">
              {{ auth.user ? 'Ir a perfil' : 'Iniciar sesión' }}
            </Button>
            <Button v-if="!auth.user" variant="secondary" @click="handleAuthClick('/register')">
              Registrarse
            </Button>
          </div>

          <!-- Mobile: Menu button and drawer -->
          <Sheet v-model:open="isMenuOpen" class="sm:hidden">
            <SheetTrigger asChild>
              <Button variant="outline" size="icon" class="sm:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                <span class="sr-only">Toggle menu</span>
              </Button>
            </SheetTrigger>
            <SheetContent side="right" class="w-[300px] sm:hidden">
              <SheetHeader>
                <SheetTitle>Menú</SheetTitle>
              </SheetHeader>
              <div class="flex flex-col space-y-4 mt-4">
                <button @click="toggleTheme" class="p-2 rounded-md text-left">
                  <span v-if="theme === 'light'">Modo oscuro</span>
                  <span v-else>Modo claro</span>
                </button>
                <Button class="w-full" @click="handleAuthClick('/login')">
                  {{ auth.user ? 'Ir a perfil' : 'Iniciar sesión' }}
                </Button>
                <Button v-if="!auth.user" variant="secondary" class="w-full" @click="handleAuthClick('/register')">
                  Registrarse
                </Button>
              </div>
            </SheetContent>
          </Sheet>
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
                  <div class="flex flex-col gap-4 rounded-lg border p-6 shadow-sm bg-white dark:bg-gray-900 transition-all">
                    <h3 class="text-lg font-semibold">Seleccionar archivo para encriptar</h3>
                    <p class="text-sm text-muted-foreground">
                      Arrastra tu archivo <strong>.txt</strong> aquí o haz clic para seleccionarlo.
                    </p>

                    <!-- Zona Drag & Drop -->
                    <div
                      class="flex flex-col items-center justify-center border-2 border-dashed rounded-lg py-10 cursor-pointer transition-colors"
                      :class="isDragging ? 'border-blue-500 bg-blue-50 dark:bg-gray-800' : 'border-gray-300 hover:border-blue-400'"
                      @dragover="handleDragOver"
                      @dragleave="handleDragLeave"
                      @drop="handleDrop"
                      @click="$refs.encryptInput.click()"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500 mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 15a4 4 0 014-4h10a4 4 0 010 8H7a4 4 0 01-4-4zM12 12V3m0 0l3.293 3.293M12 3L8.707 6.293" />
                      </svg>

                      <span v-if="!fileName" class="text-gray-600 dark:text-gray-300">Suelta tu archivo aquí</span>
                      <span v-else class="text-green-600 dark:text-green-400 font-medium">{{ fileName }}</span>

                      <input ref="encryptInput" id="encrypt-file" type="file" accept=".txt"
                        class="hidden" @change="handleEncryptFileChange" />
                    </div>

                    <!-- Botón Encriptar -->
                    <Button @click="handleEncrypt" :disabled="isLoading || !encryptFile"
                      class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium">
                      {{ isLoading ? 'Encriptando...' : 'Subir y Encriptar' }}
                    </Button>

                    <!-- Barra de progreso -->
                    <div v-if="isLoading" class="mt-3">
                      <Progress v-model="progress" class="w-full" />
                      <p class="text-center text-sm text-gray-500 mt-1">Procesando archivo...</p>
                    </div>
                  </div>
              </TabsContent>


                <!-- Decrypt Panel -->
                <!-- Decrypt Panel -->
<TabsContent value="decrypt" class="mt-6">
  <div class="flex flex-col gap-4 rounded-lg border p-6 shadow-sm bg-white dark:bg-gray-900 transition-all">
    <h3 class="text-lg font-semibold">Subir archivos para desencriptar</h3>
    <p class="text-sm text-muted-foreground">
      Arrastra los archivos <strong>.txt</strong> y <strong>.key</strong> a las áreas correspondientes o haz clic para seleccionarlos.
    </p>

    <!-- Zonas de carga -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Zona TXT -->
      <div
        class="flex flex-col items-center justify-center border-2 border-dashed rounded-lg py-10 cursor-pointer transition-colors"
        :class="isDraggingTxt ? 'border-blue-500 bg-blue-50 dark:bg-gray-800' : 'border-gray-300 hover:border-blue-400'"
        @dragover="isDraggingTxt = true"
        @dragleave="isDraggingTxt = false"
        @drop="handleTxtDrop"
        @click="$refs.decryptTxtInput.click()"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500 mb-3" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 15a4 4 0 014-4h10a4 4 0 010 8H7a4 4 0 01-4-4zM12 12V3m0 0l3.293 3.293M12 3L8.707 6.293" />
        </svg>
        <span v-if="!decryptFile" class="text-gray-600 dark:text-gray-300">Suelta tu archivo .txt aquí</span>
        <span v-else class="text-green-600 dark:text-green-400 font-medium">{{ decryptFile.name }}</span>
        <input ref="decryptTxtInput" id="decrypt-file" type="file" accept=".txt" class="hidden" @change="handleDecryptFileChange" />
      </div>

      <!-- Zona KEY -->
      <div
        class="flex flex-col items-center justify-center border-2 border-dashed rounded-lg py-10 cursor-pointer transition-colors"
        :class="isDraggingKey ? 'border-blue-500 bg-blue-50 dark:bg-gray-800' : 'border-gray-300 hover:border-blue-400'"
        @dragover="isDraggingKey = true"
        @dragleave="isDraggingKey = false"
        @drop="handleKeyDrop"
        @click="$refs.keyInput.click()"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500 mb-3" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 15v6m0 0l3-3m-3 3l-3-3m9-6a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span v-if="!keyFile" class="text-gray-600 dark:text-gray-300">Suelta tu archivo .key aquí</span>
        <span v-else class="text-green-600 dark:text-green-400 font-medium">{{ keyFile.name }}</span>
        <input ref="keyInput" id="key-file" type="file" accept=".key" class="hidden" @change="handleKeyFileChange" />
      </div>
    </div>

    <!-- Botón Desencriptar -->
    <Button
      @click="handleDecrypt"
      :disabled="isLoading || !decryptFile || !keyFile"
      class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium"
    >
      {{ isLoading ? 'Procesando...' : 'Desencriptar Archivo' }}
    </Button>

    <!-- Progreso -->
    <div v-if="isLoading" class="mt-3">
      <Progress v-model="progress" class="w-full" />
      <p class="text-center text-sm text-gray-500 mt-1">Procesando archivos...</p>
    </div>
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