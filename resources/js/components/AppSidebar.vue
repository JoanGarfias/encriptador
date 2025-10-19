<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { profile } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, Moon, Sun, UserCircle } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();
const theme = computed(() => appearance.value === 'system' 
    ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
    : appearance.value);

const toggleTheme = () => {
    updateAppearance(theme.value === 'light' ? 'dark' : 'light');
};

const mainNavItems: NavItem[] = [
    {
        title: 'Perfil',
        href: profile(),
        icon: UserCircle,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: theme.value === 'light' ? 'Modo Oscuro' : 'Modo Claro',
        href: '#',
        icon: theme.value === 'light' ? Moon : Sun,
        onClick: toggleTheme,
    },
    {
        title: 'Github Repo',
        href: 'https://github.com/JoanGarfias/encriptador',
        icon: Folder,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
