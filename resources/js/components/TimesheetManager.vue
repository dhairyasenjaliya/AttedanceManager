<template>
    <div class="container"  v-if="$gate.isAdmin()">
      <div class="row mt-5">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Daily Timesheet Manager</h3> 
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                    <tbody><tr> 
                      <th>Name</th> 
                    </tr> 
                    <tr v-for="user in users.data" :key="user.id">
                    <td>
                        <router-link :to="'/userid/' + user.id ">
                         <!-- <router-link :to="{name: 'date', params: {id: user.id}}"  > -->
                             <img class="defaultImage" :src="'./image/profile/' + user.photo" /> {{user.name}}  
                        </router-link> 
                    </td> 
                    </tr>  
                   
                  </tbody></table>  
                </div>
                <!-- /.card-body -->
                 <div class="card-footer">
                    <pagination :data="users" @pagination-change-page="getResults"></pagination>
                </div>
              </div>
              <!-- /.card -->
            </div>
        </div>  
    </div>
    
</template>

<script>

    export default {  
      data(){ 
            return {  
                users:{}, 
            }            
        },

        methods:{ 

                getResults(page = 1) {
                        axios.get('api/user?name=' + page)
                            .then(response => {
                                this.users = response.data;
                            });
                },
 
                loadUser(){ 
                            axios.get("api/name").then(({ data }) => (this.users = data)); 
                }  
        },

        created(){
            this.$Progress.start();
            this.loadUser();   
            this.$Progress.finish();
        },  

        mounted() {
            
        }
    }

</script>