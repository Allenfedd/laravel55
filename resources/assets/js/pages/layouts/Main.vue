<template>
    <div class="wrapper">
        <nav-bar></nav-bar>
        <side-bar></side-bar>

        <div class="content-wrapper">
            <!--<content-header></content-header>-->
            <div class="content">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import NavBar from './NavBar.vue'
    import SideBar from './SideBar.vue'
    import ContentHeader from './ContentHeader.vue'

    export default {
        components: {
            NavBar, SideBar, ContentHeader
        }
    }
</script>