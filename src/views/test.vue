<template>
  <div id="app">
    <div class="band-header">
      <header class="site-header">
        <h1 class="header-title">Colos - simple food analysis</h1>
        <div class="header-right">
          <button @click="goToHome">Главная</button>
          <button @click="goToProfile">Профиль</button>
          <button @click="logout">Выйти</button>
        </div>
      </header>
    </div>
    <router-view v-slot="{ Component }">
      <transition name="fade" mode="out-in">
        <component :is="Component" />
      </transition>
    </router-view>
  </div>
</template>

<script>
export default {
  name: 'App',
  methods: {
    goToHome() {
      this.$router.push('/');
    },
    goToProfile() {
      this.$router.push('/profile');
    },
    logout() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    }
  }
}
</script>

<style>
.band-header {
  width: 100%;
  border-bottom: 1px solid #ddd;
}

.site-header {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
  padding: 15px 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.header-title {
  grid-column: 2;
  margin: 0;
  text-align: center;
  font-size: 1.3rem;
  font-weight: 500;
}

.header-right {
  grid-column: 3;
  display: flex;
  justify-content: flex-end;
  gap: 15px;
}

.header-right button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@media (max-width: 768px) {
  .site-header {
    padding: 10px;
  }
  
  .header-title {
    font-size: 1rem;
  }
  
  .header-right button {
    font-size: 0.85rem;
  }
  
  .header-right {
    gap: 8px;
  }
}
</style>