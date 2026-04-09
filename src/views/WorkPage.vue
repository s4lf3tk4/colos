<template>
 <div name="WorkPage">
  <div class="band-header">
      <header class="site-header">
        <div v-if="response && response.auth === true">
        <h1> Hello, {{ response.username }}!</h1>
        </div>

      </header>
    </div>
    
  <div class="auth-container">
    <div class="auth-card">
      <div class="file-upload-minimal">
        <label class="file-label">
          <input
            type="file"
            ref="fileInput"
            @change="handleFileUpload"
            class="file-input"
          />
          <div class="file-label-content">
            <span class="file-icon">📎</span>
            <span class="file-text">{{ fileName || 'Выберите картинку' }}</span>
            <span class="file-browse">Обзор</span>
          </div>
        </label>
      </div>
      
      <div class="button-group">
        <button type="submit" @click="senasdGetRequest()" class="auth-btn register-submit-btn" :disabled="loading">
          <span>{{ loading ? '⏳' : '✅' }}</span> 
          {{ loading ? 'Анализируем...' : 'Получить анализ' }}

        </button>
      </div>
        <div>
        {{ responseAnalysis}}
                  <button @click="goPy">
            Отправить
          </button>
          {{ responsePy}}
      </div>    
    </div>
  </div>
</div>

</template>


<script>
export default {
  data() {
    return {
      response: null,
      error: null,
      loading: false,
      selectedFile: null,
      fileName: '',
      responseAnalysis: null,
      responsePy: null,
      sessionCheckInterval: null
    }
  },
  async mounted() {
    await this.check_auth();
   this.sessionCheckInterval = setInterval(() => {
    this.check_session_time();
  }, 500);
  },
  beforeUnmount() {
  if (this.sessionCheckInterval) {
    clearInterval(this.sessionCheckInterval);
  }
},

  methods: {
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
        const jsonData = await fetchResponse.json(); // Прямое получение JSON
      this.response = jsonData;

      if (jsonData.auth === false) {
        this.$router.push('/errorpage');
      }

      this.error = null;
    } catch (err) {
      console.error('Ошибка запроса:', err);
      // Опционально: показать уведомление об ошибке сети
    }
    },
    async goPy() {
        const url = 'http://localhost/colos/bridge.php';
        
        try {
            const fetchResponse = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({text: 'Привет из Vue!'})
            });

            const text = await fetchResponse.text();
            let jsonData;
            try {
                jsonData = JSON.parse(text);
            } catch(e) {
                this.responsePy = 'Ошибка: не JSON';
                return;
            }
            
            if (jsonData && jsonData.reply) {
                this.responsePy = jsonData.reply;
            } else {
                this.responsePy = 'Ошибка: нет reply';
            }
            
        } catch (error) {
            console.error('❌ Ошибка в catch:', error);
            this.responsePy = 'Ошибка: ' + error.message;
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
          
          if (jsonData.auth === false) {
            setTimeout(() => {
            this.$router.push('/authcard')
            }, 1000);
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
    
    handleFileUpload(event) {
      this.selectedFile = event.target.files[0];
      this.fileName = this.selectedFile ? this.selectedFile.name : '';
    },
    

    async senasdGetRequest() {

      if (!this.selectedFile) {
            this.responseAnalysis = "Сначала выберите файл";
            return;
        }
        const formData = new FormData();
        formData.append('fileToUpload', this.selectedFile);

        fetch('http://localhost/colos/php_pics.php', {
          method: 'POST',
          credentials: 'include',
          body: formData
        })
        .then(responseAnalysis => {
            if (!responseAnalysis.ok) {
                throw new Error(`HTTP error! status: ${responseAnalysis.status}`);
            }
            return responseAnalysis.json();
          })
            .then(data => {
            if (data && typeof data === 'object') {
                this.responseAnalysis = data.success 
                    ? "Файл успешно загружен" 
                    : (data.message || "Ошибка загрузки");
                
              
            } else {
                throw new Error('Непредвиденная ошибка');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            this.responseAnalysis = "Ошибка: " + error.message;
            
        });
    }
  }
}
</script>

<style scoped>
@import '@/assets/styles/auth-card.css';
@import '@/assets/styles/header.css';
@import'@/assets/styles/message.css';
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