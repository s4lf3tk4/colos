<template>
  <div>
    <div class="band-header">
      <header class="site-header">
        <h1>Colos - simple food analisys</h1>
      </header>
    </div>

    <div class="auth-container">
      <div class="auth-card">
        <div class="card-icon">
          <span>📝</span>
        </div>
        <div class="auth-title">Регистрация</div>
        <div class="auth-sub">Создайте аккаунт для анализа питания</div>

        <form class="auth-form" @submit.prevent="handleRegister">
          <div class="input-group">
            <label for="reg-name">Имя пользователя</label>
            <input 
              type="text" 
              id="reg-name" 
              v-model="formData.name"
              placeholder="Ваше имя" 
              required
            >
          </div>
          
          <div class="input-group">
            <label for="reg-email">Email</label>
            <input 
              type="email" 
              id="reg-email" 
              v-model="formData.email"
              placeholder="example@mail.com" 
              required
            >
          </div>
          
          <div class="input-group">
            <label for="reg-password">Пароль</label>
            <div style="position: relative;">
              <input 
                :type="showPassword ? 'text' : 'password'"
                id="reg-password" 
                v-model="formData.password"
                placeholder="Минимум 6 символов" 
                required
              >
              <span 
                @click="showPassword = !showPassword"
                style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); cursor: pointer; user-select: none;"
              >
                {{ showPassword ? '🙈' : '👁️' }}
              </span>
            </div>
          </div>
          
          <div class="input-group">
            <label for="reg-confirm">Подтвердите пароль</label>
            <div style="position: relative;">
              <input 
                :type="showConfirmPassword ? 'text' : 'password'"
                id="reg-confirm" 
                v-model="confirmPassword"
                placeholder="Повторите пароль" 
                required
              >
              <span 
                @click="showConfirmPassword = !showConfirmPassword"
                style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); cursor: pointer; user-select: none;"
              >
                {{ showConfirmPassword ? '🙈' : '👁️' }}
              </span>
            </div>
          </div>
          
          <div class="terms-group" style="margin: 0.5rem 0;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem;">
              <input type="checkbox" v-model="agreeTerms" required style="width: 18px; height: 18px;">
              <span>Я согласен с <a href="#" @click.prevent="showTerms" style="color: #4c7a3a;">условиями использования</a></span>
            </label>
          </div>
          
          <div class="button-group">
            <button type="submit" class="auth-btn register-submit-btn" :disabled="loading">
              <span>✅</span> 
              {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
            </button>
            
            <button type="button" class="auth-btn back-btn" @click="goBack" :disabled="loading">
              <span>←</span> 
              Вернуться
            </button>
          </div>
        </form>
        
        <div v-if="message" class="auth-message" :class="{ success: messageType === 'success' }">
          {{ message }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterPage',
  data() {
    return {
      formData: {
        name: '',
        email: '',
        password: ''
      },
      confirmPassword: '',
      agreeTerms: false,
      showPassword: false,
      showConfirmPassword: false,
      loading: false,
      message: '',
      messageType: ''
    }
  },
  methods: {
    handleRegister() {
      this.message = '';
      
      if (!this.formData.name.trim()) {
        this.showMessage('❌ Введите имя пользователя', 'error');
        return;
      }
      
      if (this.formData.name.length < 2) {
        this.showMessage('❌ Имя должно содержать минимум 2 символа', 'error');
        return;
      }
      
      if (!this.formData.email.trim()) {
        this.showMessage('❌ Введите email', 'error');
        return;
      }
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(this.formData.email)) {
        this.showMessage('❌ Введите корректный email', 'error');
        return;
      }
      
      if (!this.formData.password.trim()) {
        this.showMessage('❌ Введите пароль', 'error');
        return;
      }
      
      if (this.formData.password.length < 6) {
        this.showMessage('❌ Пароль должен содержать минимум 6 символов', 'error');
        return;
      }
      
      if (this.formData.password !== this.confirmPassword) {
        this.showMessage('❌ Пароли не совпадают', 'error');
        return;
      }
      
      if (!this.agreeTerms) {
        this.showMessage('❌ Необходимо согласиться с условиями использования', 'error');
        return;
      }
      
      this.loading = true;
      
      setTimeout(() => {
        this.loading = false;
        this.showMessage('✅ Регистрация успешна! Теперь вы можете войти в систему.', 'success');
        
        const users = JSON.parse(localStorage.getItem('users') || '[]');
        users.push({
          name: this.formData.name,
          email: this.formData.email,
          password: this.formData.password
        });
        localStorage.setItem('users', JSON.stringify(users));
        
        setTimeout(() => {
          this.goBack();
        }, 2000);
      }, 1500);
    },
    
    goBack() {
      this.$router.push('/');
    },
    
    showTerms() {
      alert('Условия использования:\n\n1. Используйте сервис ответственно\n2. Не передавайте свои данные третьим лицам\n3. Мы не несем ответственности за результаты анализа\n\nНажимая "Зарегистрироваться", вы соглашаетесь с этими условиями.');
    },
    
    showMessage(text, type) {
      this.message = text;
      this.messageType = type;
      
      setTimeout(() => {
        if (this.message === text) {
          this.message = '';
        }
      }, 3000);
    }
  }
}
</script>

<style scoped>
@import '@/assets/styles/auth-card.css';
</style>