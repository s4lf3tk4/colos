<template>

  <div class="auth-container">
    <div class="auth-card">
      <div class="card-icon">
        <span>😥</span>
      </div>
      <div class="auth-title">Вы были перенаправленны на данную страницу из-за неактивности(</div>
      <div class="auth-sub">перейдите снова на страницу авторизации по кнопке ниже👇</div>
        <div v-if="message" :class="['message', messageType]">
          {{ message }}
        </div>

        
        <div class="button-group">
          <button type="submit" @click = "goBack()"class="auth-btn login-btn" :disabled="loading">
            <span>🔐</span> 
            {{ loading ? 'Переход...' : 'Перейти' }}
          </button>
        </div>
      

    </div>
  </div>
</template>

<script>
export default {
  name: 'ErrorPage',
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
    async goBack(){
        this.loading = true;
        const loaded = await this.isloadedForward();
            if (loaded === true){
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
@import '@/assets/styles/auth-card.css';
@import'@/assets/styles/message.css';

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
  max-width: auto;
  aspect-ratio: auto;
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