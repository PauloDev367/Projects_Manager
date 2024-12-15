<template>
  <form @submit.prevent="create">
    <div class="form-group">
      <label>Titulo</label>
      <input type="text" v-model="title" class="form-control" />
      <div class="mt-3 text-right">
        <button class="btn btn-sm btn-info">Adicionar</button>
      </div>
    </div>
  </form>
</template>


<script setup>
import { createProject } from "@/services/projects";
import { ref } from "vue";

const title = ref(null);

const create = () => {
  if (title.value == null || title.value == "") {
    alert("Preencha todos os campos para continuar");
    return;
  }
  createProject(title.value)
    .then((result) => {
      window.location.reload();
    })
    .catch((err) => {
      alert("Erro ao tentar cadastrar novo projeto");
    });
};
</script>
