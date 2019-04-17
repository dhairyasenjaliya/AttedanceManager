<template>
   <div class="container">
       <div class="container-fluid">
      <div class="row mt-2" > 
          <div class="col-md-12">
              <div class="card-footer">
                  <div class="row">
                      <div class="col-sm-6 col-6">
                                <div class="description-block border-right"> 
                                   <vue-clock /> 
                      </div>
                                
                      </div>
                            
                      <div class="col-sm-6 col-6">
                          <div class="description-block  ">    
                            <div class="small-box bg-info">
                              <div class="inner">
                                <h3>{{ time | custom }}</h3> 
                                <p>Daily Hours</p>
                              </div>
                              <div class="icon">
                                <i class="fas fa-hourglass-start"></i>
                              </div> 
                            </div>

                         </div>
                              
                      </div>                 
                  </div>
                             
              </div> 

            <div class="card">
                <div class="card-header">
                   <h3 class="card-title">TimeSheet    </h3> 
                 
                      <datepicker :highlighted="state.highlighted" :disabledDates="state.disabledDates"  @closed="calldate" v-model="state.date">  </datepicker> 
                   
                  <td> </td> 
                  <div class="card-tools">
                          <button v-show="this.form.status == 'Out' ? true : false" class="btn btn-success" @click.prevent="Punch_in">In<i class="fas fa-user-plus"></i></button>
                          <button v-show="this.form.status == 'In' ? true : false" class="btn btn-danger" @click.prevent="Punch_out">Out<i class="fas fa-user-minus"></i></button>
                  </div> 
                </div>
         
            <div class='bk' v-show=" users.length == 0" >
              <div class='mid'>
                <div class='fore'>
                  <div class='figure'></div>
                </div>
              </div>
            </div>   

                <div class="card-body table-responsive p-0"  v-show=" users.length !== 0" >
                  <table class="table table-hover" >
                        <tbody>
                            <tr>
                              <th>Day(Date)</th>
                              <th>In</th>
                              <th>Out</th> 
                          </tr>  
                          <tr v-for="user in users" :key="user.id" >  
                              <td >{{ user.created_at | myDate }}  </td> 
                              <td> {{ user.punch_in  }} <i :class="{'fas fa-times-circle red': user.punch_in == null }"></i>  </td>
                              <td> {{ user.punch_out }} <i :class="{'fas fa-times-circle red': user.punch_out == null }"></i> </td>                              
                          </tr>
                      </tbody>
                  </table>
                </div> 
                <!-- <div class="card-footer">
                    <pagination :data="users" @pagination-change-page="getResults"></pagination>
                </div> -->
          </div>
    </div>  </div>
</div> </div>
</template>
<script> 
import VueClock from '@dangvanthanh/vue-clock'; 
import Datepicker from 'vuejs-datepicker';
export default {
        components: { VueClock ,Datepicker},
        data(){   
                return{  
                      chk:'',
                      // currentTime :'',
                      form : new Form({id :'',status:''}),
                      users:{ },   
                      in:'',
                      out:'',
                      total:[],
                      time : moment.duration(0).data,
                      state : {
                                date: moment.now(),
                                disabledDates: {
                                  from : new Date(),
                                  // days: [0]
                                },
                                highlighted: {
                                  days: [0]
                                },
                              }  
                }   
        },   

        methods:{  
                // getResults(page = 1) {
                //         axios.get('api/timesheet?page=' + page)
                //             .then(response => { 
                //                 this.users = response.data;
                //             });
                // }, 

                calldate(){
                          this.$Progress.start();
                          this.load();
                          this.fetchtimsheet();
                          this.$Progress.finish();
                },
             
                fetchtimsheet() {
                               axios.get('/api/timesheet?date=' + moment( this.state.date).format('YYYY-MM-DD')).then(({ data }) => { 
                                this.users =   data   
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
                },
                // updateCurrentTime() {
                //                     this.currentTime = moment().format('LTS');
                // }, 
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

            // this.currentTime = moment().format('LTS');
            // setInterval(() => this.updateCurrentTime(), 1 * 1000);

            Fire.$on('load',() => {
                this.load();  //Trigger EVent when CreateUser is fired 
                this.fetchtimsheet();
            });
            this.$Progress.finish();
        },
        mounted() {
         
        }
    }
</script> 
 
<style scoped> 
 

.container1 {
  margin: 30px auto;
  border: 1px solid #333;
  background: #55c1e7;
}

/*--extend--*/
.area {
  width: 600px;
  height: 288px;
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000;
}

.fore, .mid, .bk, .container { 
  @extend .area; 
}

.figure {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/dog-walk.svg") no-repeat;
  width: 220px;
  height: 266px;
  transform: translate3D(200px, 50px, 0);
  animation: walk 0.78s steps(9) infinite;
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000;
}
.no-svg .figure {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/dog-walk.png");
}

.fore {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/foreground.svg");
  transform: translateZ(0);
  animation: bk 7s -5s linear infinite;
}
.no-svg .fore {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/foreground.png");
}

.mid {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/midground.svg");
  animation: bk 15s -5s linear infinite;
  transform: translateZ(0);
}
.no-svg .mid {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/midground.png");
}

.bk {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/background.svg");
  animation: bk 20s -5s linear infinite;
  transform: translateZ(0);
}
.no-svg .bk {
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/57786/background.png");
}

@keyframes walk {
  100% { background-position: 0 -2376px; }
}

@keyframes bk {
  100% { background-position: 200% 0; }
}

@media screen and ( max-width: 600px ) {
  .container { 
    width: 100%; 
    margin-top: 0;
  }
  .figure { transform: translate3D(40%, 50px, 0); }
}
</style>