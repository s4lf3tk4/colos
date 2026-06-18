<template>

  <div class="auth-container">
    <div class="auth-card">
      <div class="card-icon">
        <span>🥗</span>
      </div>
      <div class="auth-title">Добро пожаловать</div>
      <div class="auth-sub">Войдите для анализа питания</div>
        <div v-if="message" :class="['message', messageType]">
          {{ message }}
        </div>

      <form class="auth-form" @submit.prevent="handleLogin">
        <div class="input-group">
          <label for="username">Имя пользователя</label>
          <input 
            type="text" 
            id="username" 
            v-model="formData.username"
            placeholder="Ваше имя" 
            required
          >
        </div>
        
        <div class="input-group">
          <label for="password">Пароль</label>
          <input 
            type="password" 
            id="password" 
            v-model="formData.password"
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
          <!-- <button @click="goForward">
            НА СТРАНИЦУ
          </button> -->
        </div>
      </form>
      

    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthCard',
  data() {
    return {
      
      response: null,
      formData: {
        username: '',
        password: ''
      },
      loading: false,
      message: '',
      messageType: '',
      
    }
  },
  async mounted() {
    
    await this.check_auth();
    
  },
  methods: {
      async goForward(){
        // const url = 'http://localhost/colos/login.php';
        
        // const fetchResponse = await fetch(url, {
        //   method: 'GET',
        //   credentials: 'include',
        //   headers: {
        //     'Content-Type': 'application/json',
        //   }
        // });
    this.$router.push('/workpage');
  },

 async check_auth() {
      try {
        const url = 'http://localhost/colos/check_auth.php';
        
        const fetchResponse = await fetch(url, {
          method: 'GET',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/json',
          }
          
        });
        
        const textResponse = await fetchResponse.text();
        try {
          const jsonData = JSON.parse(textResponse);
          this.response = jsonData;
          if (jsonData.auth === true) {
            if (jsonData.station == 'admin'){
                
              setTimeout(() => {
                this.$router.push('/adminmanager');
                }, 700);
            }
            else if (jsonData.station == 'guest'){      
              setTimeout(() => {
                this.$router.push('/workpage');
              }, 700);
             }
            else if (jsonData.station == 'prem'){      
              setTimeout(() => {
                this.$router.push('/workpage');
              }, 700);
             }

          }
        } catch (e) {
          console.log("нет активной сессии")
        }
        this.error = null;
      } catch (err) {
        console.error('Ошибка запроса:', err);
      }
    },
    registrate() {
      this.$router.push('/register');
    },
async isloadedForward() {
    const component = await import('../views/WorkPage.vue');
    
    return true;
  },
    
    async handleLogin() {
      this.response = null;
      
      if (!this.formData.username.trim()) {
        this.showMessage('❌ Пожалуйста, введите ваше имя', 'error');
        return;
      }
      
      if (!this.formData.password.trim()) {
        this.showMessage('❌ Введите пароль для продолжения', 'error');
        return;
      }
      
      const params = new URLSearchParams();
      params.append('name', this.formData.username);
      params.append('password', this.formData.password);
      
      this.loading = true;
      
      try {
        const fetchResponse = await fetch('http://localhost/colos/php_mysql_login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            
          },
          credentials: 'include',
          body: params
        });
        
        if (!fetchResponse.ok) {
          throw new Error(`HTTP ошибка: ${fetchResponse.status}`);
        }
        
        const data = await fetchResponse.json();
        this.response = data; 
        
        if (data.status === 'success') {
            this.formData = { username: '', password: '' };
            this.showMessage(data.message || '✅ Успешный вход!', 'success');
            try{
              
              if (data.station == 'guest'){
                const loaded = await this.isloadedForward();
                if (loaded === true){
                  
                      setTimeout(() => {
                        this.$router.push('/workpage');
                      }, 500);
                }
              }else if (data.station == 'admin'){
                
                      setTimeout(() => {
                        this.$router.push('/adminmanager');
                      }, 500);
                
              }
              else if(data.station == 'prem'){
                const loaded = await this.isloadedForward();
                    setTimeout(() => {
                        this.$router.push('/workpage');
                      }, 500);
              }
          }
          catch(error){
            this.showMessage(data.message || 'НЕ Успешный вход!', 'error');
          }
          
        } else {
          this.showMessage(data.message || '❌ Ошибка авторизации', 'error');
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
    }
  }
}
</script>

<style scoped>
@import '@/assets/styles/header.css';
@import '@/assets/styles/auth-card.css';
@import'@/assets/styles/message.css';

</style>