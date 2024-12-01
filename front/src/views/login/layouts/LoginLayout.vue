<template>
  <div class="conteudo">
    <div class="lateral" :class="hideMenu == true ? 'hide' : ''">
      <ul>
        <li class="head">
          <div class="user">
            <img
              src="https://clipart-library.com/data_images/6103.png"
              alt="Foto cliente"
            />
            <h2>Nome da Silva</h2>
          </div>

          <button @click="changeMenuStatus">
            <i class="fa-solid fa-table-columns"></i>
          </button>
        </li>

        <li class="li-item">
          <a href="#"> <i class="fa-solid fa-house"></i> Dashboard </a>
        </li>
        <li>
          <hr />
        </li>
        <li class="li-item li-title">
          <div class="projetos">
            <span>Meus projetos</span>
            <button>
              <i class="fa-solid fa-circle-plus"></i>
            </button>
          </div>
        </li>

        <li class="li-item li-proj">
          <a href="#"># Projeto 1 </a>
        </li>
      </ul>
    </div>
    <div class="principal" :class="hideMenu == true ? 'show' : ''">
      <div class="head">
        <button v-if="hideMenu == true" @click="changeMenuStatus">
          <i class="fa-solid fa-table-columns"></i>
        </button>
      </div>
      <div class="area-conteudo">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const hideMenu = ref(false);

const changeMenuStatus = () => {
  hideMenu.value = !hideMenu.value;
};
</script>

<style scoped>
.conteudo {
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  height: 100vh;
}
.conteudo .lateral {
  height: 100%;
  background-color: #fcfaf8;
  width: 20%;
  padding: 20px;
  transition: all 0.3s;
}

.conteudo .principal {
  height: 100%;
  width: 80%;
  background-color: #ffffff;
  transition: all 0.3s;
}

.conteudo .lateral.hide {
  position: absolute;
  left: -100%;
}
.conteudo .principal.show {
  width: 100%;
}
.conteudo .lateral ul {
  padding: 0;
  margin: 0;
  list-style: none;
}
.conteudo .lateral .li-item a {
  font-size: 1rem;
  padding: 5px;
  border-radius: 5px;
  margin-bottom: 5px;
  cursor: pointer;
  display: inline-block;
  text-decoration: none;
  width: 100%;
  color: #000;
}
.conteudo .lateral .li-item a:hover {
  background-color: #ccc;
}
.conteudo .lateral .li-item i {
  color: #dc5148;
  margin-right: 5px;
}
.conteudo .lateral .li-title {
  font-weight: 600;
  margin-bottom: 5px;
  padding: 5px;
  margin-bottom: 5px;
}
.conteudo .lateral .li-proj {
  font-weight: 400;
}
.conteudo .lateral .li-item .projetos {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.conteudo .lateral .li-item .projetos button {
  background-color: transparent;
  border: none;
  font-size: 1.3rem;
}
.conteudo .lateral .head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 5px;
  border-radius: 5px;
  margin-bottom: 5px;
  padding-left: 0;
}
.conteudo .lateral .head button {
  background-color: transparent;
  padding: 5px;
  width: 35px;
  border-radius: 5px;
  height: 35px;
  border: none;
}
.conteudo .lateral .head button:hover {
  background-color: #ccc;
}
.conteudo .lateral .head h2 {
  font-size: 0.9rem;
  margin-bottom: 0;
  margin-left: 10px;
}
.conteudo .lateral .head .user {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.conteudo .lateral .head .user img {
  width: 30px;
  height: 30px;
  padding: 5px;
  display: inline-block;
  border-radius: 50%;
  object-fit: cover;
}

.conteudo .principal .head button {
  background-color: transparent;
  padding: 5px;
  width: 35px;
  border-radius: 5px;
  height: 35px;
  border: none;
}
.conteudo .principal .head button:hover {
  background-color: #ccc;
}
.conteudo .principal .head {
  padding: 5px;
  margin-bottom: 5px;
}
.conteudo .principal .area-conteudo {
  padding: 20px;
  max-height: 100%;
  overflow-y: scroll;
}
</style>