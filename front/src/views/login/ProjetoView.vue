<template>
  <LoginLayout>
    <header>
      <h1># Projeto 1</h1>
      <button @click="addList" class="btn btn-sm btn-warning">
        <i class="fa-regular fa-square-plus"></i>
        Adicionar coluna
      </button>
    </header>

    <div class="area-cards">
      <div v-for="(list, index) in lists" :key="list.id" class="list">
        <div class="list-header">
          <template v-if="canUpdateListName && listToUpdateId == list.id">
            <input
              v-model="list.title"
              class="input-change-title"
              ref="inputField"
              @keypress.enter="confirmListNameUpdate(list.id)"
            />
          </template>
          <h3 v-else>{{ list.title }}</h3>
          <div>
            <button
              v-if="index > 0"
              @click="moveList(index, -1)"
              class="btn btn-sm btn-outline-primary"
            >
              <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button
              v-if="index < lists.length - 1"
              @click="moveList(index, 1)"
              class="btn btn-sm btn-outline-primary"
            >
              <i class="fa-solid fa-arrow-right"></i>
            </button>
            <button
              class="btn btn-sm btn-outline-secondary ml-2"
              @click="updateListName(list.id)"
            >
              <i class="fa-solid fa-gear"></i>
            </button>
          </div>
        </div>
        <div
          class="cards"
          @dragover.prevent="onDragOverEmpty($event, list.id)"
          @drop="onDrop($event, null, list.id)"
        >
          <div
            v-for="(card, cardIndex) in list.cards"
            :key="card.id"
            class="card"
            draggable="true"
            @dragstart="onDragStart($event, card, list.id)"
            @dragover.prevent="onDragOver($event, cardIndex, list.id)"
            @drop="onDrop($event, cardIndex, list.id)"
            @click="showInfos(card)"
          >
            {{ card.text }}
          </div>
          <div
            v-if="!list.cards.length"
            class="empty-drop-zone"
            @dragover.prevent
            @drop="onDrop($event, null, list.id)"
          >
            Solte aqui
          </div>
        </div>

        <div class="list-footer">
          <button
            @click="addCard(list.id)"
            class="btn btn-sm btn-block btn-outline-info"
          >
            <i class="fa-regular fa-square-plus"></i>
            Adicionar card
          </button>
        </div>
      </div>
    </div>

    <div class="modal-seecard" v-if="listCardToSee != null">
      <div class="modal-area-base">
        <div class="head">
          <h2>{{ listCardToSee.text }}</h2>
          <button @click="closeModal">
            <i class="fa-regular fa-circle-xmark"></i>
          </button>
        </div>
        <div class="body">
          <div class="main-block">
            <h6>Descrição</h6>
            <ul class="etiquetas">
              <li>
                <span>Etiqueta 1</span>
              </li>
              <li>
                <span>Etiqueta 2</span>
              </li>
              <li>
                <span>Etiqueta 3</span>
              </li>
              <li>
                <span>Etiqueta 4</span>
              </li>
            </ul>
            <textarea rows="15"></textarea>
            <div class="text-right mt-2">
              <button class="btn btn-sm btn-danger mr-2">Cancelar</button>
              <button class="btn btn-sm btn-info">Salvar</button>
            </div>
          </div>
          <div class="right-block">
            <ul class="actions">
              <li>
                <a href="#">
                  <i class="fa-solid fa-people-group"></i> Membros
                </a>
              </li>
              <li>
                <a href="#"> <i class="fa-solid fa-tags"></i> Etiquetas </a>
              </li>
              <li>
                <a href="#"> <i class="fa-regular fa-clock"></i> Vencimento </a>
              </li>
            </ul>
            <hr />
            <ul class="membros">
              <li>
                <img
                  src="https://plus.unsplash.com/premium_photo-1689530775582-83b8abdb5020?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cmFuZG9tJTIwcGVyc29ufGVufDB8fDB8fHww"
                  alt="Membro"
                />
              </li>
              <li>
                <img
                  src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMdBuvbsYu7WYAAUY2AqSQRGNESsYdkucDkQ&s"
                  alt="Membro"
                />
              </li>
              <li>
                <img
                  src="https://i.redd.it/i-got-bored-so-i-decided-to-draw-a-random-image-on-the-v0-4ig97vv85vjb1.png?width=1280&format=png&auto=webp&s=7177756d1f393b6e093596d06e1ba539f723264b"
                  alt="Membro"
                />
              </li>
              <li>
                <img
                  src="https://plus.unsplash.com/premium_photo-1689530775582-83b8abdb5020?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cmFuZG9tJTIwcGVyc29ufGVufDB8fDB8fHww"
                  alt="Membro"
                />
              </li>
              <li>
                <img
                  src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMdBuvbsYu7WYAAUY2AqSQRGNESsYdkucDkQ&s"
                  alt="Membro"
                />
              </li>
              <li>
                <img
                  src="https://i.redd.it/i-got-bored-so-i-decided-to-draw-a-random-image-on-the-v0-4ig97vv85vjb1.png?width=1280&format=png&auto=webp&s=7177756d1f393b6e093596d06e1ba539f723264b"
                  alt="Membro"
                />
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </LoginLayout>
</template>
<script setup>
import LoginLayout from "./layouts/LoginLayout.vue";
import { reactive, ref } from "vue";
const listToUpdateId = ref(null);
const canUpdateListName = ref(false);
const listCardToSee = ref(null);

const lists = reactive([
  {
    id: 1,
    title: "To Do",
    cards: [
      { id: 1, text: "Task 1" },
      { id: 2, text: "Task 2" },
    ],
  },
  {
    id: 2,
    title: "In Progress",
    cards: [],
  },
]);

const addList = () => {
  const columnName = prompt("Digite o nome da coluna:");
  const newListId = lists.length + 1;
  lists.push({ id: newListId, title: columnName, cards: [] });
};

const addCard = (listId) => {
  const list = lists.find((l) => l.id === listId);
  const newCardId = Date.now();
  const cardText = prompt("Digite o nome do card:");
  if (cardText) {
    list.cards.push({ id: newCardId, text: cardText });
  }
};

const moveList = (index, direction) => {
  const newIndex = index + direction;
  const [movedList] = lists.splice(index, 1);
  lists.splice(newIndex, 0, movedList);
};

let draggedCard = null;
let draggedFromListId = null;

const onDragStart = (event, card, fromListId) => {
  draggedCard = card;
  draggedFromListId = fromListId;
};

const onDragOver = (event, targetIndex, listId) => {
  const targetCard = event.target;
  const targetRect = targetCard.getBoundingClientRect();
  const mouseY = event.clientY;

  const middle = targetRect.top + targetRect.height / 2;
  if (mouseY < middle) {
    targetCard.classList.add("drag-over-top");
    targetCard.classList.remove("drag-over-bottom");
  } else {
    targetCard.classList.add("drag-over-bottom");
    targetCard.classList.remove("drag-over-top");
  }
};

const onDragOverEmpty = (event, listId) => {
  const list = lists.find((l) => l.id === listId);
  if (list && list.cards.length === 0) {
    event.target.classList.add("empty-drop-zone-active");
  }
};

const onDrop = (event, targetIndex, toListId) => {
  const fromList = lists.find((l) => l.id === draggedFromListId);
  const toList = lists.find((l) => l.id === toListId);
  const cardIndex = fromList.cards.findIndex((c) => c.id === draggedCard.id);
  if (cardIndex !== -1) fromList.cards.splice(cardIndex, 1);

  if (targetIndex === null) {
    toList.cards.push(draggedCard);
  } else {
    const targetCard = event.target;
    const targetRect = targetCard.getBoundingClientRect();
    const mouseY = event.clientY;

    const middle = targetRect.top + targetRect.height / 2;
    if (mouseY < middle) {
      toList.cards.splice(targetIndex, 0, draggedCard);
    } else {
      toList.cards.splice(targetIndex + 1, 0, draggedCard);
    }
  }

  draggedCard = null;
  draggedFromListId = null;
  document
    .querySelectorAll(".card, .empty-drop-zone")
    .forEach((el) =>
      el.classList.remove(
        "drag-over-top",
        "drag-over-bottom",
        "empty-drop-zone-active"
      )
    );
};

const updateListName = (listId) => {
  listToUpdateId.value = listId;
  canUpdateListName.value = true;
};
const confirmListNameUpdate = (listId) => {
  listToUpdateId.value = null;
  canUpdateListName.value = false;
};

const showInfos = (card) => {
  listCardToSee.value = card;
};
const closeModal = () => {
  listCardToSee.value = null;
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
  width: 75%;
  height: 800px;
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
  width: 70%;
  height: 90%;
  min-height: 400px;
  padding: 20px;
}
.modal-seecard .modal-area-base .body .main-block textarea {
  width: 100%;
  border: 1px solid rgba(0, 0, 0, 0.1);
  outline: none;
  padding: 10px;
  border-radius: 5px;
}
.modal-seecard .modal-area-base .body .main-block ul.etiquetas {
  padding: 0;
  list-style: none;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  flex-wrap: wrap;
}
.modal-seecard .modal-area-base .body .main-block ul.etiquetas li span {
  padding: 2px 3px;
  margin: 2px;
  border-radius: 5px;
  display: inline-block;
  font-size: 0.8rem;
}
.modal-seecard
  .modal-area-base
  .body
  .main-block
  ul.etiquetas
  li:nth-child(1)
  span {
  background-color: rosybrown;
}
.modal-seecard
  .modal-area-base
  .body
  .main-block
  ul.etiquetas
  li:nth-child(2)
  span {
  background-color: crimson;
}
.modal-seecard
  .modal-area-base
  .body
  .main-block
  ul.etiquetas
  li:nth-child(3)
  span {
  background-color: darkgoldenrod;
}
.modal-seecard
  .modal-area-base
  .body
  .main-block
  ul.etiquetas
  li:nth-child(4)
  span {
  background-color: cornflowerblue;
}
.modal-seecard .modal-area-base .body .right-block ul.membros {
  padding: 0;
  margin: 0;
  list-style: none;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  flex-wrap: wrap;
}
.modal-seecard .modal-area-base .body .right-block ul.membros li {
  margin-right: 10px;
  margin-bottom: 5px;
}
.modal-seecard .modal-area-base .body .right-block ul.membros li img {
  width: 40px;
  height: 40px;
  display: inline-block;
  object-fit: cover;
  border-radius: 50%;
}

.modal-seecard .modal-area-base .body .right-block {
  width: 30%;
  height: 90%;
  border-left: 1px solid rgba(0, 0, 0, 0.1);
  padding: 10px;
}
.modal-seecard .modal-area-base .body .right-block ul.actions {
  padding: 0;
  list-style: none;
  margin: 0;
}
.modal-seecard .modal-area-base .body .right-block ul.actions li a {
  font-size: 1.1rem;
  display: inline-block;
  color: #000;
  background-color: #fcfaf8;
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 5px;
  width: 100%;
  text-decoration: none;
  transition: all 0.3s;
}
.modal-seecard .modal-area-base .body .right-block ul.actions li a:hover {
  background-color: #bababa;
}
.area-cards {
  display: flex;
  align-items: flex-start;
  height: 95%;
  gap: 20px;
  padding: 20px;
  overflow-x: auto;
}

.list {
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 300px;
  min-width: 300px;
  padding: 10px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.list h3 {
  font-size: 1.2rem;
}
.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cards {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.card {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  padding: 10px;
  cursor: grab;
  transition: background-color 0.2s ease;
}

.drag-over-top {
  border-top: 3px solid #0079bf;
}

.drag-over-bottom {
  border-bottom: 3px solid #0079bf;
}

.empty-drop-zone {
  height: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f4f5f7;
  border: 2px dashed #e0e0e0;
  border-radius: 4px;
  color: #a0a0a0;
}

.empty-drop-zone-active {
  border-color: #0079bf;
  color: #0079bf;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

header h1 {
  font-size: 1.5rem;
  margin-bottom: 0;
}
.input-change-title {
  border-radius: 3px;
  border: 1px solid rgba(0, 0, 0, 0.1);
  padding: 5px;
  outline: none;
}
</style>

