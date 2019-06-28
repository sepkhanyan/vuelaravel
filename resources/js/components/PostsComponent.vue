<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'post/create'}" class="btn btn-primary">Add new Post</router-link>

            <router-link :to="{name: 'users'}" class="btn btn-success">Users</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Post list</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>description</th>
                        <th>Image</th>
                        <th width="150">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="post, index in posts">
                        <td>{{ post.title }}</td>
                        <td>{{ post.description }}</td>
                        <td><img :src="'/images/' + post.image" /></td>
                        <td class="action">

                                <router-link v-if="user.is_admin == 1" :to="{name: 'post/edit', params: {id: post.id}}" class="btn btn-primary">
                                    <i class="fa fa-pencil"></i>
                                </router-link>
                                <router-link  v-else-if="user.id == post.user_id" :to="{name: 'post/edit', params: {id: post.id}}" class="btn btn-primary">
                                    <i class="fa fa-pencil"></i>
                                </router-link>
                                <router-link  :to="{name: 'post/show', params: {id: post.id}}" class="btn btn-success">
                                    <i class="fa fa-eye"></i>
                                </router-link>
                                <a href="#" v-if="user.is_admin == 1"
                                   class="btn  btn-danger"
                                   v-on:click="deletePost(post.id, index)">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                                <a href="#" v-else-if="user.id == post.user_id"
                                   class="btn  btn-danger"
                                   v-on:click="deletePost(post.id, index)">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>


                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style>
    img {
        width: 50px;
        border-radius: 2px;
        box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.5);
        transition: width 1s;
    }
</style>

<script>
    export default {
        data() {
            return {
                posts: [],
                user: {}
            }
        },
        created() {
           axios.get('/posts').then(response => {
                this.posts = response.data.posts;
                this.user =  response.data.user
            });
        },
        methods: {
            deletePost(id, index) {
                if (confirm("Do you really want to delete this post?")) {

                    axios.post('/post/delete/' + id)
                        .then(response =>  {
                            this.posts.splice(index, 1);
                        })
                        .catch(response =>  {
                            alert("Could not delete post");
                        });
                }
            }
        }
    }
</script>