<template>
  <LoginLayout>
    <div class="base">
      <header>
        <div class="col-12 mb-4 main">
          <h2>Dashboard</h2>
          <div class="infos">
            <h3>Total: 12 projetos</h3>
          </div>
        </div>
      </header>

      <main>
        <div class="container">
          <div class="row" v-if="searchInfo != null">
            <div
              class="col-12 col-md-4"
              v-for="project in projects"
              :key="project.id"
            >
              <CardProjectComponent :project="project" />
            </div>

            <div class="col-12 text-center mt-4 mb-4">
              <button class="btn btn-info" @click="loadMorePages">
                <i class="fa-solid fa-rotate"></i> Buscar mais
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </LoginLayout>
</template>

<script setup>
import LoginLayout from "./layouts/LoginLayout.vue";
import { getAllProjects } from "./../../services/projects.js";
import { onMounted, ref } from "vue";
import CardProjectComponent from "./../../components/login/projects/CardProjectComponent.vue";

const projects = ref([]);
const searchInfo = ref([]);
const page = ref(1);

const loadMorePages = () => {
  if (searchInfo.value.next_page_url != null) {
    page.value = page.value + 1;
    loadProjects(page.value);
  }
};
const loadProjects = (page) => {
  getAllProjects(page)
    .then((result) => {
      searchInfo.value = result.data.success;
      result.data.success.data.forEach((element) => {
        projects.value.push(element);
      });
    })
    .catch((err) => {});
};
onMounted(() => {
  loadProjects(page.value);
});
</script>


<style scoped>
.base {
  height: 100%;
  overflow-y: scroll;
}
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