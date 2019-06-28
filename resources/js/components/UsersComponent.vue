<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'posts'}" class="btn btn-primary">
                <i class="fa fa-angle-double-left"></i>
                Back
            </router-link>

            <!--<router-link :to="{name: 'posts'}" class="btn btn-success">Users</router-link>-->
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Users</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="150">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user, index in users">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <span v-if="user.is_admin == 1">Admin</span>
                            <span v-else-if="user.is_admin == 0">User</span>
                        </td>
                        <td class="action">

                            <router-link v-if="admin.is_admin == 1" :to="{name: 'user/edit', params: {id: user.id}}" class="btn btn-primary">
                                <i class="fa fa-pencil"></i>
                            </router-link>
                            <a href="#" v-if="admin.is_admin == 1"
                               class="btn  btn-danger"
                               v-on:click="deleteUser(user.id, index)">
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

<script>
    export default {
        data() {
            return {
                users: [],
                admin: {}
            }
        },
        created() {
            axios.get('/users').then(response => {
                this.users = response.data.users;
                this.admin =  response.data.admin
            });
        },
        methods: {
            deleteUser(id, index) {
                if (confirm("Do you really want to delete this user?")) {

                    axios.post('/user/delete/' + id)
                        .then(response =>  {
                            this.users.splice(index, 1);
                        })
                        .catch(response =>  {
                            alert("Could not delete user");
                        });
                }
            }
        }
    }
</script>