<template>
    <div class="container">
      <div class="row mt-5">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Active Developer'$</h3>       
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                    <tbody><tr>                      
                      <th>Name</th>
                      <th>E-Mail</th>
                      <th>Bio</th>
                      <th>Status </th> 
                    </tr>                       
                    <tr v-for="user in users.data" :key="user.id">
                       
                      <td><img class="defaultImage" :src="'./image/profile/' + user.photo" /> {{user.name}}</td>                    
                      <td>{{user.email}}  </td>                    
                      <td>{{user.bio}} <i :class="{'fas fa-times-circle red': user.bio == null }"></i></td>                     
                     
                      <td>  
                          <i :class="{'fas fa-coffee fa-3x  red': user.status == 'Out' }"></i>  
                          <i :class="{'fas fa-laptop-code fa-3x  green': user.status == 'In' }"></i>
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
            console.log('Component mounted.')
        }
    }

</script>