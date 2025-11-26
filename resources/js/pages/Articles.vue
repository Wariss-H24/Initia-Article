<template>
  <div class="max-w-3xl mx-auto p-6">
    <!-- Formulaire de création -->
    <h1 v-if="!selectedArticle" class="text-2xl font-bold mb-4 text-gray-800">Créer un article</h1>
    <h1 v-else class="text-2xl font-bold mb-4 text-gray-800">Modifier un article</h1>
    <form v-if="!selectedArticle" @submit.prevent="submit"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">

      <input v-model="title" placeholder="Titre"
        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />

      <textarea v-model="content" placeholder="Contenu"
        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>

      <input type="file" @change="onFileChange"
        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
               file:rounded-md file:border-0 file:text-sm file:font-semibold
               file:bg-black file:text-white hover:file:bg-gray-800" />

      <button type="submit"
        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
        Créer
      </button>
    </form>

    <!-- Formulaire de modification -->
    <form v-else @submit.prevent="updateArticle(selectedArticle.id)"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">

      <input v-model="title" placeholder="Titre"
        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none" />

      <textarea v-model="content" placeholder="Contenu"
        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none"></textarea>

      <input type="file" @change="onFileChange"
        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
               file:rounded-md file:border-0 file:text-sm file:font-semibold
               file:bg-black file:text-white hover:file:bg-gray-800" />

      <div class="flex space-x-2">
        <button type="submit"
          class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">
          Enregistrer
        </button>
        <button type="button" @click="cancelEdit"
          class="bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500 transition">
          Annuler
        </button>
      </div>
    </form>

    <!-- Liste des articles -->
    <h2 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Liste des articles</h2>
    <ul class="space-y-4">
      <li v-for="article in articles" :key="article.id"
          class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
        <strong class="text-lg text-gray-900">{{ article.title }}</strong>
        <p class="text-gray-700">{{ article.content }}</p>

        <div v-if="article.image" class="mt-2">
          <img :src="`/storage/${article.image}`" alt="image"
               class="rounded-md shadow-md max-h-48 object-cover" />
        </div>

        <button @click="editArticle(article)"
          class="mt-3 mr-2 bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition">
          Modifier
        </button>

        <button @click="deleteArticle(article.id)"
          class="mt-3 bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition">
          Supprimer
        </button>
      </li>
    </ul>
  </div>
</template>



<script setup>
import { ref } from 'vue'
import axios from 'axios'

const title = ref('')
const content = ref('')
const image = ref(null)
const articles = ref([])
const selectedArticle = ref(null)

// Charger les articles au montage
axios.get('/api/articles').then(res => {
  articles.value = res.data
})

function onFileChange(e) {
  image.value = e.target.files[0]
}

async function submit() {
  const formData = new FormData()
  formData.append('title', title.value)
  formData.append('content', content.value)
  if (image.value) formData.append('image', image.value)

  await axios.post('/api/articles', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  })

  const res = await axios.get('/api/articles')
  articles.value = res.data
  resetForm()
  selectedArticle.value = null // 🔑 assure qu'on revient en mode création
}

function editArticle(article) {
  selectedArticle.value = article
  title.value = article.title
  content.value = article.content
  image.value = null
}

async function updateArticle(id) {
  const formData = new FormData()
  formData.append('title', title.value)
  formData.append('content', content.value)
  if (image.value) formData.append('image', image.value)

  await axios.post(`/api/articles/${id}?_method=PUT`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  })

  const res = await axios.get('/api/articles')
  articles.value = res.data
  resetForm()
  selectedArticle.value = null
}

function cancelEdit() {
  resetForm()
  selectedArticle.value = null
}

function resetForm() {
  title.value = ''
  content.value = ''
  image.value = null
}

async function deleteArticle(id) {
  await axios.delete(`/api/articles/${id}`)
  articles.value = articles.value.filter(a => a.id !== id)
}
</script>
