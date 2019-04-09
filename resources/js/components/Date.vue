<template>
     <div class="container" >  
      <div class="row mt-5" v-if="$gate.isAdmin()">  
            <div class="col-md-12"> 
            <div class="card-footer">
                <div class="row">
                  <div class="col-sm-6 col-6">
                    <div class="description-block border-right">  
                      <b v-text="currentTime"></b><br>
                      <span class="description-text">Current Time</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6 col-6">
                    <div class="description-block  "> 
                      <h5 class="description-header">{{ time }}</h5>
                      <span class="description-text">TOTAL</span>
                    </div>
                    <!-- /.description-block -->
                  </div>  
                </div>
                <!-- /.row -->
              </div> 

            <div class="card">  
                <div class="card-header">
                   <h3 class="card-title">Daily TimeSheet  </h3> <b> Name :  {{ this.form.name }} </b> <td> </td>  
                   <div class="card-tools">
                          <button v-show="this.form.status == 'Out' ? true : false" class="btn btn-success" @click.prevent="Punch_in">In<i class="fas fa-user-plus"></i></button>
                          <button v-show="this.form.status == 'In' ? true : false" class="btn btn-danger" @click.prevent="Punch_out">Out<i class="fas fa-user-minus"></i></button>
                  </div>                  
                </div>
                <div class="card-body table-responsive p-0">
                  
                  <table class="table table-hover"   >
                        <tbody>
                            <tr>
                              <th>Day(Date)</th>
                              <th>In</th>
                              <th>Out</th>
                              <th>Delete</th>
                          
                          </tr>  <!-- <a :href="'/date?=' + user.created_at"> Select Date </a> -->
                          <tr v-for="user in users" :key="user.id">  
                              <td >{{ user.created_at | myDate }}  </td> 
                              <td> {{ user.punch_in  | formateDate }} <i :class="{'fas fa-times-circle red': user.punch_in == null }"></i>  </td>
                              <td> {{ user.punch_out  | formateDate  }}  <i :class="{'fas fa-times-circle red': user.punch_out == null }"></i> </td> 
                              <td>  
                          <!-- <a href="#" @click="EditUserModel(user)"> 
                              <i class="fa fa-edit"></i>
                          </a>   /    -->
                          <a href="#" @click="deleteUser(user.id)">
                              <i class="fa fa-trash red"></i>
                          </a> 
                      </td>
                          </tr>
                      </tbody>  
                  </table>
                </div> 
                 <!-- <div class="card-footer">
                    <pagination :data="users" @pagination-change-page="getResults"></pagination>
                </div>  -->  
          </div> 
         
    </div>  
</div> </div>
</template>
<script>
 
export default {
  
        data(){   
              return {
                    id: 0 ,
                    chk:'',
                    currentTime :'',
                    form : new Form({id :'',status:'',name:''}),
                    users:{ }, 
                    in:'',
                    out:'',
                    total:[],
                    time : moment.duration(0)
              } 
        },   

        methods:{  

                   load(){ 
                        axios.get("/api/authuser?id=" + this.id)
                        .then(({ data }) => (this.form.fill(data[0])));
                    },

                    Punch_in(){
                        this.$Progress.start();
                         swal.fire({
                                title: 'Wanna Inn?',
                                text: this.currentTime,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, Im INNNN!'
                              }).then((result) => {
                                if (result.value) {
                                  this.form.status = 'In'; 
                                  this.form.put('/api/adminpunch?id=' + this.id) 
                                  .then(()=>{
                                          toast.fire({
                                                      type: 'success',
                                                      title: 'Youre INN' 
                                                  });
                                          Fire.$emit('load');
                                          this.$Progress.finish(); 
                                  })
                                  .catch(()=>{
                                      this.$Progress.fail(); 
                                  })
                                }
                             })
                      this.$Progress.finish();
                },
                Punch_out(){
                  this.$Progress.start();
                         swal.fire({
                                title: 'Wanna Leave?',
                                text: this.currentTime,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, Im OUTT!'
                              }).then((result) => {
                                if (result.value) {
                                  this.form.status = 'Out';   
                                  this.form.put('/api/adminpunch?id=' + this.id)
                                  .then(()=>{ 
                                          toast.fire({
                                              type: 'error',
                                              title: 'Your Out'
                                        });          
                                          Fire.$emit('load');  
                                          
                                          this.$Progress.finish();
                                  })
                                  .catch(()=>{
                                      this.$Progress.fail();
                                  }) 
                                }
                             })
                      this.$Progress.finish(); 
                },

                    fetchtimsheet() {
                      if(this.$gate.isAdmin()){
                               axios.get("/api/date?id="+this.id).then(({ data }) => { 
                                // axios.get("api/date",{ params : { id : this.id }}).then(({ data }) => { 
                                this.users = data 
                                this.time =  moment.duration(0)
                                data.forEach(function(calculate) 
                                { 
                                  if(calculate.punch_in)
                                     data = calculate.punch_in.toString()  
                                  if(calculate.punch_out)  {
                                     this.time.add(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                     this.total.push(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                   }
                               }.bind(this));  
                              }) 
                      }
                    },
                    updateCurrentTime() {
                                    this.currentTime = moment().format('LTS');
                },  


                deleteUser(id){
                  this.$Progress.start();
                   swal.fire({
                                title: 'Are you sure?',
                                text: "You won't be able to revert this!",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                              }).then((result) => {
                                if (result.value) {  
                                  this.form.delete('/api/time/'+id).then(()=>{
                                    Fire.$emit('CreateUser'); //Used For update table with event
                                   swal.fire(
                                              'Deleted!',
                                              'Punch Timing',
                                              'success'
                                            );
                                      this.$Progress.fail();
                                  }).catch(()=>{
                                            swal.fire(
                                            'Failed!',
                                            'There Was Somthing Wrong ! ',
                                            'warning'  
                                            );
                                  }); 
                                }
                             })
                      
                 }
          
        },
 
        created(){
            this.$Progress.start();  
            this.id = this.$route.params.id; 
            this.fetchtimsheet();

            this.load();
          
            Fire.$on('CreateUser',() => {
                this.load();
                this.fetchtimsheet();  //Trigger EVent when CreateUser is fired 
            });

            Fire.$on('load',() => {
                this.load();  //Trigger EVent when CreateUser is fired 
                this.fetchtimsheet();
            });

            this.currentTime = moment().format('LTS');
            setInterval(() => this.updateCurrentTime(), 1 * 1000);
            this.$Progress.finish();
        },
        mounted() {
            console.log('Component mounted.'); 
        }
    }
</script>


<style scoped>
   
</style>