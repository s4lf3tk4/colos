<template>
  <div class="auth-container">
    <div class="auth-card">
      <div class="card-icon">
        <span>🥗</span>
      </div>
      <div class="auth-title">Добро пожаловать</div>
      <div class="auth-sub">Войдите для анализа питания</div>

      <form class="auth-form" @submit.prevent="handleLogin">
        <div class="input-group">
          <label for="username">Имя пользователя</label>
          <input 
            type="text" 
            id="username" 
            v-model="username"
            placeholder="Ваше имя" 
            required
          >
        </div>
        
        <div class="input-group">
          <label for="password">Пароль</label>
          <input 
            type="password" 
            id="password" 
            v-model="password"
            placeholder="Пароль" 
            required
          >
        </div>
        
        <div class="button-group">
          <button type="submit" class="auth-btn login-btn" :disabled="loading">
            <span>🔐</span> 
            {{ loading ? 'Вход...' : 'Войти' }}
          </button>
          
          <button type="button" class="auth-btn register-btn" @click="registrate" :disabled="loading">
            <span>🔑</span>     
            Регистрация
          </button>
        </div>
      </form>
      
      <div v-if="message" class="auth-message" :class="{ success: messageType === 'success' }">
        {{ message }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthCard',
  data() {
    return {
      username: '',
      password: '',
      loading: false,
      message: '',
      messageType: ''
    }
  },
  methods: {
    registrate() {
      this.$router.push('/register');
    },
    
    handleLogin() {
      this.message = '';
      
      if (!this.username.trim()) {
        this.showMessage('❌ Пожалуйста, введите ваше имя', 'error');
        return;
      }
      
      if (!this.password.trim()) {
        this.showMessage('❌ Введите пароль для продолжения', 'error');
        return;
      }
      
      this.loading = true;
      
      setTimeout(() => {
        this.loading = false;
        const welcomeName = this.username.length > 20 ? this.username.substring(0, 18) + '..' : this.username;
        this.showMessage(`✅ Добро пожаловать, ${welcomeName}! Вы успешно авторизованы.`, 'success');
        
        this.$emit('login-success', {
          username: this.username,
          password: this.password
        });
      }, 1000);
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