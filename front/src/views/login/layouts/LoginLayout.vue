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
          <a href="/dashboard"> <i class="fa-solid fa-house"></i> Dashboard </a>
        </li>
        <li>
          <hr />
        </li>
        <li class="li-item li-title">
          <div class="projetos">
            <span>Meus projetos</span>
            <button @click="changeModalToShow">
              <i class="fa-solid fa-circle-plus"></i>
            </button>
          </div>
        </li>

        <li class="li-item li-proj">
          <a href="/projeto"># Projeto 1 </a>
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

  <div class="modal-seecard" v-if="showmodal != null">
    <div class="modal-area-base">
      <div class="head">
        <h2>Adicionar novo projeto</h2>
        <button @click="changeModalToHidde">
          <i class="fa-regular fa-circle-xmark"></i>
        </button>
      </div>
      <div class="body">
        <div class="main-block">
          <FormAddNewProjectComponent />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import FormAddNewProjectComponent from "@/components/login/projects/FormAddNewProjectComponent.vue";

const showmodal = ref(null);
const hideMenu = ref(false);

const changeModalToShow = () => {
  console.log("clicou");
  showmodal.value = true;
  console.log(showmodal);
};
const changeModalToHidde = () => {
  showmodal.value = null;
};

const changeMenuStatus = () => {
  hideMenu.value = !hideMenu.value;
};
</script>

<style scoped>
.modal-seecard {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.3);
}

.modal-seecard .modal-area-base {
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  width: 50%;
  height: 600px;
  z-index: 99;
}
.modal-seecard .modal-area-base .head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 10px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}
.modal-seecard .modal-area-base .head h2 {
  margin-bottom: 0;
  font-size: 1.3rem;
}
.modal-seecard .modal-area-base .head button {
  background-color: transparent;
  font-size: 1.5rem;
  border: none;
  transition: all 0.3s;
}
.modal-seecard .modal-area-base .head button:hover {
  color: #dc5148;
}
.modal-seecard .modal-area-base .body {
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  height: 100%;
}
.modal-seecard .modal-area-base .body .main-block {
  overflow-y: scroll;
  width: 100%;
  height: 90%;
  min-height: 400px;
  padding: 20px;
}

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
  width: 100%;
  height: 100%;
}
</style>