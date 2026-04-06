<template>
  <div id="app">
    <div class="band-header">
      <header class="site-header">
        <h1>Colos - simple food analysis</h1>
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
  name: 'App'
}
</script>

<style>
@import '@/assets/styles/header.css';
/* Глобальные стили для переходов */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.slide-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}

.scale-enter-active,
.scale-leave-active {
  transition: all 0.4s ease;
}

.scale-enter-from {
  transform: scale(0.8);
  opacity: 0;
}

.scale-leave-to {
  transform: scale(1.2);
  opacity: 0;
}
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: linear-gradient(145deg, #f0f4f0 0%, #e2e8e0 100%);
  font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
}
</style>