<template>
   <div class="container">
      <div class="row mt-5" >
            <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                   <h3 class="card-title">Daily TimeSheet</h3>  <td> <a href= "date"> Select Date </a></td> 
                   <i v-text="currentTime"></i>
                   <div class="card-tools">
                          <button v-show="this.form.status == 'Out' ? true : false" class="btn btn-success" @click.prevent="Punch_in">In<i class="fas fa-user-plus"></i></button>
                          <button v-show="this.form.status == 'In' ? true : false" class="btn btn-danger" @click.prevent="Punch_out">Out<i class="fas fa-user-minus"></i></button>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                        <tbody>
                            <tr>
                              <th>Day  (Date)</th>
                              <th>In</th>
                              <th>Out</th>
                              <th>Total</th>
                          </tr>
                          <tr v-for="user in users" :key="user.id">
                              <td> {{ user.created_at | myDate }}  </td> 
                              <td> {{ user.punch_in | formatDate }} <i :class="{'fas fa-times-circle red': user.punch_in == null }"></i>  </td>
                              <td> {{ user.punch_out | formatDate }} <i :class="{'fas fa-times-circle red': user.punch_out == null }"></i> </td> 
                              <td> {{ calculate_time(user.punch_out,user.punch_in )  }}  </td> 
                          </tr>
                      </tbody> 
                  </table>
                </div> 
                <div> {{ total }} </div>
          </div>
    </div>  
</div> </div>
</template>
<script>

export default {
        data(){
                return{
                      chk:'',
                      currentTime :'',
                      form : new Form({id :'',status:''}),
                      users:{ }, 
                      in:'',
                      out:'',
                      diff:'',
                      total: [ ]
                }   
        }, 

         computed: { 
                
        },
        methods:{
              calculate_time : function(end,start)
                { 
                      if(start!=null){
                          this.$in = start; 
                      }
                      else if(end!=null){
                          this.$out = end; 
                          this.$diff = moment.utc(moment(this.$out).diff(moment(this.$in))).format("HH:mm:ss");
                          // this.total.push(this.$diff) ;
                          return this.$diff;
                      } 
                },

                fetchtimsheet(){
                               axios.get("api/date").then(({ data }) => (this.users = data)) 
                },
                updateCurrentTime() { 
                                    this.currentTime = moment().format('LTS');
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
                                  this.form.put('api/punch/')
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
                                  this.form.put('api/punch/')
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
                load(){                       
                     axios.get("api/profile/")
                      .then(({ data }) => (this.form.fill(data)));
                      if(this.form.status == '')
                      {
                            this.form.status = 'Out';
                      }
                } 
        },
        created(){
            this.$Progress.start();  
            this.load();
            this.fetchtimsheet();

            this.currentTime = moment().format('LTS');
            setInterval(() => this.updateCurrentTime(), 1 * 1000);

            Fire.$on('load',() => {
                this.load();  //Trigger EVent when CreateUser is fired 
                this.fetchtimsheet();
            } );
            this.$Progress.finish();
        },
        mounted() {
            console.log('Component mounted.');
        }
    }
</script>