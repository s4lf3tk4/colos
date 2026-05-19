<template>
  <div>
    
    <div class="auth-container">
      <div class="auth-card">
        <div class="card-icon">
          <span>📝</span>
        </div>
        <div class="auth-title">Регистрация</div>
        <div class="auth-sub">Создайте аккаунт для анализа питания</div>

        <!-- ✅ Улучшенное отображение сообщений -->
        <div v-if="message" :class="['message', messageType]">
          {{ message }}
        </div>

        <form class="auth-form" @submit.prevent="handleRegister">
          <div class="input-group">
            <label for="reg-name">Имя пользователя</label>
            <input 
              type="text" 
              id="reg-name" 
              v-model="formData.name"
              placeholder="Ваше имя" 
              required
              :disabled="loading"
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
              :disabled="loading"
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
                :disabled="loading"
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
                :disabled="loading"
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
              <input type="checkbox" v-model="agreeTerms" required style="width: 18px; height: 18px;" :disabled="loading">
              <span>Я согласен с <a href="#" @click.prevent="showTerms" style="color: #4c7a3a;">условиями использования</a></span>
            </label>
          </div>
          
          <div class="button-group">
            <button type="submit" class="auth-btn register-submit-btn" :disabled="loading">
              <span>{{ loading ? '⏳' : '✅' }}</span> 
              {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
            </button>
            
            <button type="button" class="auth-btn back-btn" @click="goBack" :disabled="loading">
              <span>←</span> 
              Вернуться
            </button>
          </div>
        </form>
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
      messageType: '',
      response: null  // ✅ исправлено: response вместо responce
    }
  },
  methods: {
    async handleRegister() {
      this.response = null;
      
      // Валидация
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


      const params = new URLSearchParams();
      params.append('name', this.formData.name);
      params.append('email', this.formData.email);
      params.append('password', this.formData.password);
      
      this.loading = true;
      
      try {
        const fetchResponse = await fetch('http://localhost/colos/php_mysql.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: params
        });
        
        if (!fetchResponse.ok) {
          throw new Error(`HTTP ошибка: ${fetchResponse.status}`);
        }
        
        const data = await fetchResponse.json();
        this.response = data;  // ✅ исправлено: сохраняем в response
        
        if (data.status === 'success') {
          this.showMessage(data.message || '✅ Регистрация успешна!', 'success');
          
          this.formData = { name: '', email: '', password: '' };
          this.confirmPassword = '';
          this.agreeTerms = false;
            setTimeout(() => {
            this.$router.push('/authcard')
            }, 500);
          
        } else {
          this.showMessage(data.message || '❌ Ошибка регистрации', 'error');
        }
        
      } catch (error) {
        console.error('Ошибка:', error);
        
        let errorMessage = '❌ Ошибка соединения с сервером';
        if (error.message.includes('Failed to fetch')) {
          errorMessage = '❌ Не удалось подключиться к серверу. Проверьте: 1) Сервер запущен 2) Путь к файлу правильный';
        } else if (error.message.includes('JSON')) {
          errorMessage = '❌ Сервер вернул некорректный ответ. Проверьте PHP код на наличие ошибок.';
        } else {
          errorMessage = `❌ Ошибка: ${error.message}`;
        }
        
        this.showMessage(errorMessage, 'error');
        
      } finally {
        this.loading = false;
      }
    },
    showMessage(text, type) {
    this.message = text;
    this.messageType = type;
  },
    
    goBack() {
      this.$router.push('/authcard');
    },
    
    showTerms() {
      alert('Условия использования:\n\n1. Используйте сервис ответственно\n2. Не передавайте свои данные третьим лицам\n3. Мы не несем ответственности за результаты анализа\n\nНажимая "Зарегистрироваться", вы соглашаетесь с этими условиями.');
    },
  }
}
</script>

<style scoped>
@import '@/assets/styles/header.css';
@import '@/assets/styles/auth-card.css';
@import'@/assets/styles/message.css';
</style>