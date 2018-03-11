<template>
    <div class="text-center">
        <h1 class="headline text-gray"> 404</h1>
        <div class="error-content">
            <h2><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h2>
            <p>
                We could not find the page you were looking for.
                Meanwhile, you may <router-link :to="{ name: 'admin' }">return to dashboard.</router-link>
            </p>
        </div>
    </div>
</template>