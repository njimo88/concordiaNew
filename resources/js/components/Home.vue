<template>
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                    <h4 class="mb-2 border p-2 rounded">All Posts</h4>
                    <div
                        class="list-group"
                        v-for="post in data.posts.data"
                        :key="post.id"
                    >
                        <li class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6>
                                    {{ post.title }}
                                </h6>
                            </div>
                            <div class="mb-1">
                                <div>
                                    <p>
                                        {{ post.body }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, onUnmounted, reactive } from 'vue'

const data = reactive({
    posts: [],
})

const fetchPosts = async () => {
    try {
        const response = await axios.get('/api/posts')
        data.posts = response.data
    } catch (error) {
        console.log(error)
    }
}

const fetchNextPosts = async () => {
    try {
        const response = await axios.get(
            `/api/posts?page=${(data.posts.current_page += 1)}`,
        )
        response.data.data.map((item) => {
            data.posts.data.push(item)
        })
    } catch (error) {
        console.log(error)
    }
}

const scroll = (e) => {
    if (data.posts.data.length < data.posts.total) {
        let bottomOfWindow =
            Math.round(
                document.documentElement.scrollTop + window.innerHeight,
            ) === document.documentElement.offsetHeight
        if (bottomOfWindow) {
            fetchNextPosts()
        }
    }
}

onMounted(() => {
    window.addEventListener('scroll', scroll)
})

onBeforeMount(() => fetchPosts())

onUnmounted(() => window.removeEventListener('scroll', scroll))
</script>

<style></style>
