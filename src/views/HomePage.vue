<template>
  <div>
    <AppHeader />
    <AuthCard @login-success="handleLoginSuccess" />
  </div>
</template>

<script>
import AppHeader from '@/components/Header.vue'
import AuthCard from '@/components/AuthCard.vue'

export default {
  name: 'HomePage',
  components: {
    AppHeader,
    AuthCard
  },
  methods: {
    handleLoginSuccess(userData) {
      console.log('User logged in:', userData);
    }
  }
}
</script>

<style scoped>
@import '@/assets/styles/main.css';
@import '@/assets/styles/responsive.css';
</style>