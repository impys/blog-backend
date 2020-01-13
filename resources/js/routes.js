import Home from './components/Home/Home.vue'
import Posts from './components/Post/Posts.vue'
import Post from './components/Post/Post.vue'
import Search from './components/Search/Search.vue'
import Tags from './components/Tag/Tags.vue'

const routes = [
    {
        path: '/',
        redirect: '/posts',
        component: Home,
        name: 'home',
        meta: {
            keepAlive: true
        }
    },
    {
        path: '/posts',
        component: Posts,
        props: (route) => ({ tag_id: route.query.tag_id, tag_name: route.query.tag_name }),
        name: 'posts',
        meta: {
            keepAlive: true
        }
    },
    {
        path: '/posts/:id',
        component: Post,
        props: true,
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
    {
        path: '/tags',
        component: Tags,
        name: 'tags',
        meta: {
            keepAlive: true
        }
    },
]

export default routes
