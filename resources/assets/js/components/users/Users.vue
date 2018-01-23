<template>
    <div>
        <div class="flex-container center">
            <user-show v-for="user in users" :user="user" :key="user.id"></user-show>
        </div>

        <div class="flex-container center">
            <user-create></user-create>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: []
            }
        },
        created() {
            this.fetchData();
            $('*').click(function(event) {
                event.stopPropagation();
                $('.editing').removeClass('editing');
            });
        },
        methods: {
            fetchData () {
                axios.get('api/users').then((response) => {
                    this.users = response.data;
                });
            }
        }
    }
</script>
