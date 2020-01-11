import Home from './components/Home/Home.vue'
import Posts from './components/Post/Posts.vue'
import Post from './components/Post/Post.vue'
import Search from './components/Search/Search.vue'

const routes = [
    {
        path: '/',
        component: Home,
        name: 'home',
        meta: {
            keepAlive: true
        }
    },
    {
        path: '/posts',
        component: Posts,
        name: 'posts',
        meta: {
            keepAlive: true
        }
    },
    {
        path: '/posts/:id',
        component: Post,
        name: 'post',
        meta: {
            keepAlive: false
        }
    },
    {
        path: '/search',
        component: Search,
        name: 'search',
        meta: {
            keepAlive: true
        }
    },
]

export default routes
