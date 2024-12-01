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
  </LoginLayout>
</template>
<script setup>
import LoginLayout from "./layouts/LoginLayout.vue";
import { reactive, ref } from "vue";
const listToUpdateId = ref(null);
const canUpdateListName = ref(false);

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
</script>

<style scoped>
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

