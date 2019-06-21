<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <router-link :to="{name: 'posts'}" class="btn btn-primary">
                    <i class="fa fa-angle-double-left"></i>
                    Back
                </router-link>
                <div class="panel-heading">
                    <h1>Post Detail</h1>
                </div>

                <div class="panel-body">
                    <br/>
                    <h2>{{post.title}}</h2>
                    <br/>
                    <p><img :src="'/images/' + post.image" id="postDetailImage"/></p>
                    <br/>
                    <p>{{post.description}}</p>
                    <br/>
                </div>
            </div>
        </div>
        <br/>
            <router-link :to="{name: 'comments'}" class="nav-link">Comments</router-link>

        <br/>
        <!--<router-view name="companiesIndex"></router-view>-->
        <!--<router-view></router-view>-->
        <transition name="comments">
            <router-view></router-view>
        </transition>
    </div>
</template>

<style>
    #postDetailImage {
        width: 200px;
        border-radius: 2px;
        box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.5);
        transition: width 1s;
    }
</style>

<script>
    export default {
        data() {
            return {
                post: {},
                comments: []
            }
        },
        created() {
            axios.get(`/post/edit/${this.$route.params.id}`).then((response) => {
                this.post = response.data;
                this.comment = response.comments;
            });
        },
        methods : {

        }
    }
</script>