<template>
    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li v-for="menu in menus" class="treeview" :class="{ active: isActive(menu)}" >
            <router-link :to="{ name: menu.to}">
                <i :class="menu.icon"></i>
                <span>{{ menu.name }}</span>
            </router-link>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['menuData'],

        data() {
            return {
                menus: this.menuData
            }
        },
        methods: {
            isActive(menu) {
                for (let path of menu.paths) {
                    if (this.$route.path.includes(path)) {
                        return true;
                    }
                }
                return false;
            }
        }
    }
</script>