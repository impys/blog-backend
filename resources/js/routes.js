import Feeds from './components/Feed/Feeds.vue'
import Post from './components/Post/Post.vue'
import Search from './components/Search/Search.vue'

const routes = [
    {
        path: '/',
        component: Feeds,
        name: 'home',
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
            keepAlive: false
        }
    },
]

export default routes
