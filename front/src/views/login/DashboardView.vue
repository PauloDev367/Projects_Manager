<template>
  <LoginLayout>
    <header>
      <div class="col-12 mb-4 main">
        <h2>Dashboard</h2>
        <div class="infos">
          <h3>Total: 12 projetos</h3>

          <button class="btn btn-sm btn-info">
            <i class="fa-regular fa-square-plus"></i> Adicionar
          </button>
        </div>
      </div>
    </header>

    <main>
      <div class="container">
        <div class="row" v-if="projects != null">
          <div
            class="col-12 col-md-4"
            v-for="project in projects.data"
            :key="project.id"
          >
            <CardProjectComponent :project="project" />
          </div>
        </div>
      </div>
    </main>
  </LoginLayout>
</template>

<script setup>
import LoginLayout from "./layouts/LoginLayout.vue";
import { getAllProjects } from "./../../services/projects.js";
import { onMounted, ref } from "vue";
import CardProjectComponent from "./../../components/login/projects/CardProjectComponent.vue";

const projects = ref([]);

onMounted(() => {
  getAllProjects()
    .then((result) => {
      projects.value = result.data.success;
      console.log(projects.value);
    })
    .catch((err) => {
      console.log(err);
    });
});
</script>



<style scoped>
header .infos {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.main h2 {
  font-size: 1.5rem;
}
.main h3 {
  font-size: 1rem;
}
</style>