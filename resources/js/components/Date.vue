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
                   <h3 class="card-title">Daily TimeSheet  </h3>  <td> </td> 
                  
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
                              <!-- <th>Total</th> -->
                          </tr>  <!-- <a :href="'/date?=' + user.created_at"> Select Date </a> -->
                          <tr v-for="user in users" :key="user.id">  
                              <td >{{ user.created_at | myDate }}  </td> 
                              <td> {{ user.punch_in  | formateDate }} <i :class="{'fas fa-times-circle red': user.punch_in == null }"></i>  </td>
                              <td> {{ user.punch_out  | formateDate  }}  <i :class="{'fas fa-times-circle red': user.punch_out == null }"></i> </td> 
                              <!-- <td v-if= "user.punch_out"> {{ time }}  </td>  
                              <td v-if= "user.punch_in">  </td>    -->
                              <!-- calculate_time(user.punch_out,user.punch_in)       -->
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
                    form : new Form({id :'',status:''}),
                    users:{ },   
                    in:'',
                    out:'',
                    total:[],
                    time : moment.duration(0)
              } 
        },   

        methods:{  
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
          
        },
 
        created(){
            this.$Progress.start();  
            this.id = this.$route.params.id; 
            this.fetchtimsheet();
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