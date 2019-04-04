<template>
    <div class="container">
      <div class="row mt-5" v-if="$gate.isAdmin()">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Leave Manager</h3>       
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                    <tbody><tr>                      
                      <th>Name</th>
                      <th>Leave's </th>
                      <th>Manage</th> 
                    </tr>            
                    <tr v-for="user in users.data" :key="user.id">
                       
                      <td><img class="defaultImage" :src="'./image/profile/' + user.photo" /> {{user.name}}</td>
                    
                     <td>{{user.leaves}}</td>
                      <td>                        
                          <a href="#" @click="EditUserModel(user)">
                              <i class="fa fa-edit"></i>
                          </a>     
                         
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

            <div v-if="!$gate.isAdmin()">
                  <not-found></not-found>
            </div>

                <!-- MOdel -->
                <div class="modal" id="addNew" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                <div class="modal-header">
                
                    <h5  class="modal-title" id="addNew">Add Leaves </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="UpdateUser()" @keydown="form.onKeydown($event)">
                <div class="modal-body"> 
 
                    Manage Leaves

                    <div class="form-group">  
                    <input v-model="form.leaves" type="text" name="leaves" placeholder="Add Leaves" class="form-control" >
                    <has-error :form="form" field="leaves"></has-error>
                    </div>  
                    <toggle-button  
                         v-model="form.rm_leave"   
                         :sync="true"
                        :labels="{checked: 'Add', unchecked: 'Deduct' }" 
                        :switch-color = "{checked: 'linear-gradient(green, yellow)', unchecked: 'linear-gradient(red, yellow)'}"
                        :width = "70"
                    />
                 </div>
                   
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
                </form>
                </div>
            </div>
            </div>  
    </div>
    
</template>

<script>

    export default {  
    data(){ 
            return {                
                editmode:false,
                users:{},
                total:'',  
                form : new Form({
                id : '',
                name: '',
                leaves:'', 
                rm_leave:false,
                check:'',
                photo: ''
                })
            }            
        },

        methods:{

                getResults(page = 1) {
                        axios.get('api/user?page=' + page)
                            .then(response => {
                                this.users = response.data;
                            });
                },
                
                UpdateUser()
                {                      
                      this.$Progress.start();
                      if(this.form.rm_leave == null)
                        { this.form.rm_leave = false }
                      this.form.put('api/user/'+this.form.id )
                      .then(()=>{       
                                          // Fire.$emit('CreateUser'); //Create Custom Event                                          
                                          $('#addNew').modal('hide')
                                          toast.fire({
                                                      type: 'success',
                                                      title: 'Leaves Updated'                                              
                                                    });
                                                    this.loadUser();                                 
                                          this.$Progress.finish();
                                })
                                .catch(()=>{                                    
                                          toast.fire({
                                                      type: 'error',
                                                      title: 'Not Enough Leave To Deduct !!!!'       
                                                    });                                 
                                          this.$Progress.fail();
                                })
                      
                }, 

                EditUserModel(user){
                      this.form.reset();
                      this.form.clear();
                      this.editmode = true;
                      $('#addNew').modal('show');
                      this.form.fill(user); //As we used v-form gives many built in functions
                },
                
                loadUser(){
                            if(this.$gate.isAdmin()){
                                axios.get("api/user").then(({ data }) => (this.users = data));
                            }                            
                }, 
                
        },

        created(){
            this.$Progress.start();
            this.loadUser();  
          
            // For updating user table every 3 second METHOD - 1 (PErformance Issues) Applicable for small app
            // setInterval(()=>this.loadUser(),3000);
     
            this.$Progress.finish();
        },

        mounted() {
            console.log('Component mounted.')
        }
    }

</script>