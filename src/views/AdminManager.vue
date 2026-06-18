<template>
  <div name="WorkPage">
    <div class="band-header">
      <header class="site-header">
        <div>
          <h1>Панель админимтратора</h1>
        </div>
      </header>
    </div>
  <button @click="logout()">Выход</button> 
    <div class="auth-container">
      <div class="auth-card">
        <div class="file-upload-minimal">
          <p>ПАНЕЛЬ АДМИНА</p>

          <!-- Поля для пользователя -->
          <p><input v-model="newUser.username" placeholder="Логин" /></p>
          <p><input v-model="newUser.password" placeholder="Пароль" /></p>
          <p><input v-model="newUser.email" placeholder="Почта" /></p>

          <p><select v-model="selectedStation">
            <option value="" disabled selected>Выберите статус</option>
            <option value="guest">Статус guest</option>
            <option value="admin">Статус admin</option>
            <option value="prem">Статус prem</option>
            <option value="none">Не выбран</option>
          </select></p>

          <p><select v-model="selectedAction">
            <option value="" disabled selected>Выберите действие</option>
            <option value="add">Добавить пользователя</option>
            <option value="changePass">Изменить пароль</option>
            <option value="changeEmail">Изменить email</option>
            <option value="changeStation">Изменить статус</option>
            <option value="delete">Удалить пользователя</option>
          </select></p>

          <p><button type="button" @click="changeUser">Внести изменения</button></p>


        </div>
            <div v-if="message" :class="['message', messageType]">
            {{ message }}
          </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: '',
      messageType: '',
      response: null,
      error: null,
      loading: false,
      selectedFile: null,
      fileName: '',
      responseAnalysis: null,
      sessionCheckInterval: null,
      analysisResult: null,

      newUser: {
        username: '',
        password: '',
        email: '',
        station: ''
      },
      selectedStation: '', 
      selectedAction: '' 
    }
  },
  async mounted() {
    await this.check_auth();
    this.sessionCheckInterval = setInterval(() => {
      this.check_session_time();
    }, 5000);
  },
  beforeUnmount() {
    if (this.sessionCheckInterval) {
      clearInterval(this.sessionCheckInterval);
    }
  },
  methods: {
        async logout(){
      const url = 'http://localhost/colos/logout.php';
        
        const fetchResponse = await fetch(url, {
          method: 'GET',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/json',
          }
          
        });
        const jsonData = await fetchResponse.json();
      this.response = jsonData;

      if (jsonData.logout === true) {
          setTimeout(() => {
            this.$router.push('/authcard')
          }, 500);
      }

    },
    async changeUser() {
      switch (this.selectedAction) {
        case 'add':
          if (!this.newUser.username || !this.newUser.password || !this.newUser.email) {
            this.showMessage('Заполните логин, пароль и email', 'error');
            return;
          }
          await this.addUser();
          break;

        case 'changePass':
          if (!this.newUser.username || !this.newUser.password) {
            this.showMessage('Укажите логин и новый пароль', 'error');
            return;
          }
          await this.changePassword();
          break;

        case 'changeStation':
          if (!this.newUser.username || !this.selectedStation) {
            this.showMessage('Укажите логин и выберите статус', 'error');
            return;
          }
          await this.changeUserStation();
          break;

        case 'changeEmail':
            if(!this.newUser.username || !this.newUser.email){
                 this.showMessage('Укажите логин и почту', 'error');
            return;
            }
            await this.changeUserEmail();
          break;

        case 'delete':
          if (!this.newUser.username) {
            this.showMessage('Укажите логин пользователя для удаления', 'error');
            return;
          }
          if (confirm(`Удалить пользователя ${this.newUser.username}?`)) {
            await this.deleteUser();
          }
          break;

        default:
          this.showMessage('Неизвестное действие', 'error');
      }
    },
async changeUserEmail() {
      const params = new URLSearchParams();
      params.append('name', this.newUser.username);
      params.append('email', this.newUser.email);

      try {        
        const fetchResponse = await fetch('http://localhost/colos/email_change.php', {
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
        this.response = data;
        
        if (data.status === 'success') {
            this.showMessage(data.message, 'success');
            this.clearForm();
        } else {
          this.showMessage(data.message, 'error');
        }
    }catch (err) {
        this.showMessage('Ошибка соединения', 'error');
      }
    },
async deleteUser() {
      const params = new URLSearchParams();
      params.append('name', this.newUser.username);

      try {        
        const fetchResponse = await fetch('http://localhost/colos/user_delete.php', {
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
        this.response = data;
        
        if (data.status === 'success') {
            this.showMessage(data.message, 'success');
            this.clearForm();
        } else {
          this.showMessage(data.message, 'error');
        }
    }catch (err) {
        this.showMessage('Ошибка соединения', 'error');
      }
    },
    async changeUserStation() {
      const params = new URLSearchParams();
      params.append('name', this.newUser.username);
      params.append('station', this.selectedStation);

      try {        
        const fetchResponse = await fetch('http://localhost/colos/station_change.php', {
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
        this.response = data;
        
        if (data.status === 'success') {
            this.showMessage(data.message, 'success');
            this.clearForm();
        } else {
          this.showMessage(data.message, 'error');
        }
    }catch (err) {
        this.showMessage('Ошибка соединения', 'error');
      }
    },
async changePassword() {
      const params = new URLSearchParams();
      params.append('name', this.newUser.username);
      params.append('password', this.newUser.password);

      try {        
        const fetchResponse = await fetch('http://localhost/colos/pass_change.php', {
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
        this.response = data;
        
        if (data.status === 'success') {
            this.showMessage(data.message, 'success');
            this.clearForm();
        } else {
          this.showMessage(data.message, 'error');
        }
    }catch (err) {
        this.showMessage('Ошибка соединения', 'error');
      }
    },
    async addUser() {
      const params = new URLSearchParams();
      params.append('name', this.newUser.username);
      params.append('email', this.newUser.email);
      params.append('password', this.newUser.password);

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
        this.response = data;
        
        if (data.status === 'success') {
            this.showMessage('Пользователь добавлен', 'success');
            this.clearForm();
        } else {
          this.showMessage(data.message || 'Ошибка добавления', 'error');
        }
    }catch (err) {
        this.showMessage('Ошибка соединения', 'error');
      }
    },

    clearForm() {
      this.newUser = { username: '', password: '', email: '' };
      this.selectedStation = '';
    },

    showMessage(text, type) {
      this.message = text;
      this.messageType = type;
      setTimeout(() => { this.message = ''; }, 3000);
    },
        async check_session_time(){
        try {
            const url = 'http://localhost/colos/session_time.php';
            
            const fetchResponse = await fetch(url, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
            }
            
            });
            const jsonData = await fetchResponse.json();
        this.response = jsonData;

        if (jsonData.auth === false) {
            this.$router.push('/errorpage');
        }

        this.error = null;
    } catch (err) {
      console.error('Ошибка запроса:', err);
    }
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
          
          if (jsonData.auth === false || jsonData.station != 'admin') {
            this.$router.push('/authcard')
          } 
        } catch (e) {
          console.error('❌ Ошибка парсинга JSON:', e);
          this.response = textResponse;
        }
        
        this.error = null;
      } catch (err) {
        console.error('❌ Ошибка запроса:', err);
        this.error = err.message;
      }
    },

  }
}
</script>
<style>
@import '@/assets/styles/auth-card.css';
@import '@/assets/styles/header.css';
@import'@/assets/styles/message.css';
.image-preview {
  margin-top: 1rem;
  text-align: center;
}

.image-preview img {
  max-width: 100%;
  max-height: 250px;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border: 1px solid #e2e8f0;
}
.site-header h1 {
  display: flex;
  color: rgb(0, 0, 0);
  font-size: 26px;
  margin: 0;  
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  align-self: center;
  font-weight: 600;
  letter-spacing: -0.3px;
  white-space: nowrap;
  background: linear-gradient(135deg, #0a800a, #1e3a1e);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  position: static;
  transform: none;
  
  animation: floatUp 0.8s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards;
  opacity: 0;
  transform: translateY(20px);
}


@keyframes floatUp {
  0% {
    opacity: 0;
    transform: translateY(50px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.file-upload-minimal {
  margin: 1rem 0;
}

.file-input {
  display: none;
}

.file-label {
  display: block;
  cursor: pointer;
}

.file-label-content {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  background: linear-gradient(145deg, #f0f4f0 0%, #e2e8e0 100%);
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  transition: all 0.2s;
}

.file-label-content:hover { 
  border-color: #4c7a3a;
  background: #f0fdf4;
}

.file-icon {
  font-size: 1.2rem;
}

.file-text {
  flex: 1;
  font-size: 0.9rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.file-browse {
  color: #4c7a3a;
  font-weight: 500;
  font-size: 0.9rem;
  padding: 0.25rem 0.75rem;
  background: white;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.file-label-content:hover .file-browse {
  background: #4c7a3a;
  color: white;
}

.auth-container {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  min-height: calc(95vh - 100px);     
  padding: 1rem;
  margin-top: 40px;
}

.auth-card {
  width: 100%;
  max-width: 900px;
  aspect-ratio: 16 / 9;
  background: #ffffff;
  border-radius: 2rem;
  box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25), 0 2px 6px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  padding: 2rem 2rem 2.5rem;
  overflow-y: auto;
}
</style>