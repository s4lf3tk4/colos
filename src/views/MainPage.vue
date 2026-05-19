<template>
  <div>
  <div class="auth-container">
    <div class="auth-card horizontal-card">
      <div class="auth-title">✅ Colos - использование лучшего ПО для персонализированного питания</div>
      
      <div class="content-wrapper horizontal-content">
        <img src="/src/images/example1.jpg" alt="Пример анализа питания" class="example-image-horizontal">
        <h2 class="description-text-horizontal">Мы предоставляем возможность персонализированного питания на базе обучаемого отечественного искусственного интеллекта на базе детектора yoloV11, классификатора еды efficientNet, на базе usdaFoodData и запуск на мощном ResNet50 🤓</h2>
      </div>
    </div>
  </div>

  <div class="auth-container">
    <div class="auth-card horizontal-card">
      <div class="auth-title">✅ Colos - интуитивное использование без лишних вопросов</div>
      
      <div class="content-wrapper horizontal-content">
        <div class="text-group">
          <h2 class="description-text-horizontal">Пользование нашего анализатора не подразумевает лишних заморочек</h2>
          <h2 class="description-text-horizontal highlight">сделать фотографию 👉 отправить 👉 получить анализ🥳</h2>
          <h2 class="description-text-horizontal">Все легко и просто, не требует особых знаний и самостоятельных расчетов! Мы сделаем все за вас 🤗</h2>
           <div class="button-group">
     </div>
     <button type="submit" @click = "goAuth()"class="auth-btn login-btn" :disabled="loading">
            <span>🔐</span> 
             Начать сейчас
          </button>
        </div>
        <img src="/src/images/example1.jpg" alt="Пример анализа питания" class="example-image-horizontal">
      </div>
    </div>
  </div>
  </div>
</template>

<script>
export default {
  name: 'MainPage',
  data() {
    return {
      loading: false,
    }
  },
  methods: {
    async isloadedForward() {
      const component = await import('../views/AuthCard.vue');
      return true;
    },
    async goAuth() {
      this.loading = true;
      const loaded = await this.isloadedForward();
      if (loaded === true) {
        this.loading = false;
        setTimeout(() => {
          this.$router.push('/authcard');
        }, 500);
      }
    }
  }
}
</script>

<style scoped>
@import '@/assets/styles/header.css';
.band-header {
  width: 100%;
  background: #f5f5f5;
  border-bottom: 1px solid #e0e0e0;
}

.site-header {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}

.header-left {
  display: flex;
  gap: 10px;
  flex: 1;
}

.header-title {
  flex: 2;
  text-align: center;
  margin: 0;
  font-size: 1.5rem;
}

.header-right {
  flex: 1;
}

/* Кнопки без стилей (минимальные) */
.nav-button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  padding: 0;
  margin: 0;
}

.nav-button:hover {
  /* Легкое затемнение при наведении, но без стилей */
  opacity: 0.7;
}

/* Анимация переходов */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.auth-btn {
  width: 100%;
  background: linear-gradient(135deg, #0a800a, #1e3a1e);;
  color: white;
  border: none;
  padding: 14px 20px;
  font-size: 1rem;
  font-weight: 700;
  border-radius: 60px;
  cursor: pointer;
  transition: all 0.2s;
  margin-top: 0.5rem;
  letter-spacing: 0.3px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.auth-btn:hover:not(:disabled) {
  background: #1f481d;
  transform: scale(0.98);
}

.auth-btn:active:not(:disabled) {
  transform: scale(0.96);
}

.auth-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
  margin-bottom: 2rem;
}

.horizontal-card {
  max-width: 1200px;
  width: 100%;
  min-height: 400px;

  background: #ffffff;
  border-radius: 2rem;
  box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
  padding: 2rem;
}

.auth-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2d3748;
  text-align: center;
  margin-bottom: 2rem;
}

.horizontal-content {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 3rem;
  justify-content: center;
  flex-wrap: wrap;
}

.example-image-horizontal {
  width: 400px;
  height: auto;
  max-width: 100%;
  border-radius: 1rem;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.example-image-horizontal:hover {
  transform: scale(1.02);
}

.description-text-horizontal {
  font-size: 1.1rem;
  line-height: 1.6;
  color: #4a5568;
  font-weight: normal;
  margin: 0;
  flex: 1;
  min-width: 250px;
}

.text-group {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.highlight {
  color: #4c7a3a;
  font-weight: 500;
  background: #f0fdf4;
  padding: 0.5rem;
  border-radius: 0.5rem;
}

@media (max-width: 768px) {
  .horizontal-content {
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .example-image-horizontal {
    width: 100%;
    max-width: 300px;
  }
  
  .description-text-horizontal {
    text-align: center;
  }
  
  .auth-title {
    font-size: 1.2rem;
  }
}
</style>