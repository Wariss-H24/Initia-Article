<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'

// Variables réactives
const title = ref('')
const content = ref('')
const image = ref(null)
const articles = ref([])
const selectedArticle = ref(null)

// Charger les articles au montage
axios.get('/api/articles').then(res => {
  articles.value = res.data
})

// Méthodes
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