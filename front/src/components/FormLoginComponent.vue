<template>
  <form class="login" @submit.prevent="enter" autocomplete="off">
    <div class="login__field">
      <i class="login__icon fas fa-user"></i>
      <input
        type="text"
        v-model="email"
        class="login__input"
        placeholder="E-mail"
      />
    </div>
    <div class="login__field">
      <i class="login__icon fas fa-lock"></i>
      <input
        type="password"
        v-model="password"
        class="login__input"
        placeholder="Password"
      />
    </div>
    <button class="button login__submit" type="submit">
      <span class="button__text">Entrar</span>
      <i class="ml-auto fa-solid fa-chevron-right"></i>
    </button>
  </form>
</template>



<script setup>
import { ref } from "vue";
import { login } from "./../services/user.js";

const email = ref(null);
const password = ref(null);

const enter = () => {
  if (email.value == null || password.value == null) {
    alert("Preencha todos os campos para continuar");
    return;
  }
  if (email.value == "" || password.value == "") {
    alert("Preencha todos os campos para continuar");
    return;
  }

  login(email.value, password.value)
    .then((result) => {
      const token = result.data.access_token;
      window.localStorage.setItem("token", token);
      window.location.href = "/dashboard";
    })
    .catch((err) => {
      alert("Dados inválidos!");
    });
};
</script>




<style scoped>
.login {
  width: 320px;
  padding: 30px;
  padding-top: 156px;
}

.login__field {
  padding: 20px 0px;
  position: relative;
}

.login__icon {
  position: absolute;
  top: 30px;
  color: #7875b5;
}

.login__input {
  border: none;
  border-bottom: 2px solid #d1d1d4;
  background: none;
  padding: 10px;
  padding-left: 24px;
  font-weight: 700;
  width: 75%;
  transition: 0.2s;
}

.login__input:active,
.login__input:focus,
.login__input:hover {
  outline: none;
  border-bottom-color: #6a679e;
}

.login__submit {
  background: #fff;
  font-size: 14px;
  margin-top: 30px;
  padding: 16px 20px;
  border-radius: 26px;
  border: 1px solid #d4d3e8;
  text-transform: uppercase;
  font-weight: 700;
  display: flex;
  align-items: center;
  width: 100%;
  color: #4c489d;
  box-shadow: 0px 2px 2px #5c5696;
  cursor: pointer;
  transition: 0.2s;
}

.login__submit:active,
.login__submit:focus,
.login__submit:hover {
  border-color: #6a679e;
  outline: none;
}

.button__icon {
  font-size: 24px;
  margin-left: auto;
  color: #7875b5;
}

.social-login {
  position: absolute;
  height: 140px;
  width: 160px;
  text-align: center;
  bottom: 0px;
  right: 0px;
  color: #fff;
}

.social-icons {
  display: flex;
  align-items: center;
  justify-content: center;
}

.social-login__icon {
  padding: 20px 10px;
  color: #fff;
  text-decoration: none;
  text-shadow: 0px 0px 8px #7875b5;
}

.social-login__icon:hover {
  transform: scale(1.5);
}
</style>