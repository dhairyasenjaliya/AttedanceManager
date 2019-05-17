<template>
  <md-card>
    
    <md-card-media class="swiper-inner">
      <!-- swiper -->
      <swiper :options="swiperOption"> 
          <!-- <img :src="'/image/slider/1.jpg'"> --> 
        <swiper-slide><img :src="'/image/slider/6.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/8.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/1.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/9.jpg'"></swiper-slide> 
        <swiper-slide><img :src="'/image/slider/10.jpg'"></swiper-slide> 
        <swiper-slide><img :src="'/image/slider/11.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/3.jpg'"></swiper-slide>
        <!-- <div class="swiper-pagination" slot="pagination"></div> -->
      </swiper>
    </md-card-media> 


     <h5 class="mt-4 mb-2">Hello !! <i>{{ this.form.name  }}</i>  </h5>
        <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-info-gradient"> 
              <span class="info-box-icon"><i class="fas fa-hourglass-start"></i></span>  
              <div class="info-box-content">
                <span class="info-box-text"> <h4> Daily Hour</h4></span>
                 <div class="progress">
                  <div class="progress-bar" style="width:100%"></div>
                </div>
                <span class="info-box-number"> <h4> {{ time | custom }} </h4> </span> 
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-success-gradient">
              <span class="info-box-icon"><i class="fas fa-clock"></i></span>  
              <div class="info-box-content">
                <span class="info-box-text"> <h4> Weekly </h4> </span> 
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                <span class="info-box-number"> <h4> {{ weektime | custom   }} </h4> </span> 
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-warning-gradient">
              <span class="info-box-icon"><i class="fas fa-business-time"></i></span>
 
              <div class="info-box-content">
                <span class="info-box-text"> <h4> Monthly </h4> </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="info-box-number"> <h4> {{ monthtime | custom }} </h4> </span> 
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-danger-gradient">
              <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span> 

              <div class="info-box-content">
                <span class="info-box-text"> <h4> Annualy </h4> </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="info-box-number"><h5>{{ yeartime | custom  }}</h5></span> 
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row --> 

 <h5 class="mt-4 mb-2">Leaves  </h5> 
        <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-info-gradient"> 
              <span class="info-box-icon"><i class="fas fa-glass-cheers icon-a"></i></span>  
              <div class="info-box-content"> 
                <span class="info-box-text"> <h4> Casual Leaves</h4></span>
                 <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="info-box-number"> <h4> {{ this.form.leaves }}  </h4> </span> 
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-success-gradient">
              <span class="info-box-icon"><i class="fas fa-briefcase-medical"></i></span>   
              <div class="info-box-content">
                <span class="info-box-text"> <h4> Medical Taken  </h4> </span> 
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                <span class="info-box-number"> <h4> {{ this.form.medical_leaves }} </h4> </span> 
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-warning-gradient">
              <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>  
              <div class="info-box-content">
                <span class="info-box-text"> <h4> Unpaid Leaves  </h4> </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="info-box-number"> <h4> {{ this.form.unpaid_leaves }} </h4> </span> 
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        
 
  </md-card> 
</template>

<script> 
export default {
  
        data(){  
                return{    
                      id : '',
                      chk:'',
                      currentTime :'',
                      form : new Form({ id:'',name :'',leaves :'',medical_leaves:'',unpaid_leaves:'' }),
                      users:{ },   
                      in:'',
                      out:'',
                      total:[],  
                      time : moment.duration(0).data,
                      yeartotal:[],                      
                      yeartime : moment.duration(0).data,
                      monthtotal:[],                      
                      monthtime : moment.duration(0).data,
                      weektotal:[],                      
                      weektime : '',
                      swiperOption: {
                                        effect: 'coverflow',
                                        mousewheel: true, 
                                        grabCursor: true, 
                                        centeredSlides: true,
                                        slidesPerView: 'auto',
                                        coverflowEffect: {
                                        rotate: 50,
                                        stretch: 0,
                                        depth: 100,
                                        modifier: 1,
                                        slideShadows : true
                                    },
                                    pagination: {
                                        el: '.swiper-pagination'
                                    }
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
             
                today() {
                            axios.get("api/daily").then(({ data }) => {  
                              this.users =   data  
                              this.time =  moment.duration(0)
                              data.forEach(function(calculate) 
                              { 
                                if(calculate.punch_in)
                                  data = calculate.punch_in.toString()   
                                if(calculate.punch_out)  {
                                  this.time.add(moment.utc(moment(calculate.punch_out.toString() ,"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                  this.total.push(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                }
                              }.bind(this));  
                            }) 
                },

                year() {
                    
                               axios.get("api/year").then(({ data }) => {  
                                this.yeartime =  moment.duration(0)
                                data.forEach(function(calculate) 
                                { 
                                  if(calculate.punch_in)
                                     data = calculate.punch_in.toString()  
                                  if(calculate.punch_out)  {
                                     this.yeartime.add(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                     this.yeartotal.push(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                   }
                               }.bind(this));  
                              }) 
                },


                month() {
                               axios.get("api/month").then(({ data }) => {  
                                this.monthtime =  moment.duration(0)
                                data.forEach(function(calculate) 
                                { 
                                  if(calculate.punch_in)
                                     data = calculate.punch_in.toString()  
                                  if(calculate.punch_out)  {
                                     this.monthtime.add(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                     this.monthtotal.push(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                   }
                               }.bind(this));  
                              }) 
                },

                week() {
                               axios.get("api/week").then(({ data }) => {  
                                this.weektime =  moment.duration(0)
                                data.forEach(function(calculate) 
                                { 
                                  if(calculate.punch_in)
                                     data = calculate.punch_in.toString()  
                                  if(calculate.punch_out)  {
                                     this.weektime.add(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                     this.weektotal.push(moment.utc(moment(calculate.punch_out.toString(),"HH:mm:ss").diff(moment(data,"HH:mm:ss"))).format("HH:mm:ss"))
                                   }
                               }.bind(this));
                              }) 
                },

                leave(){
                      axios.get("api/leave")
                      .then(({ data }) => (this.form.fill(data)));   
                },  

                updateCurrentTime() {
                                    this.currentTime = moment().format('LTS');
                }, 
            
                // load(){   
                //      axios.get("api/profile/")
                //       .then(({ data }) => (this.form.fill(data)));  
                //       if(this.form.status == '')
                //       {
                //             this.form.status = 'Out';
                //       }
                      
                // } 
        },
        created(){
            this.$Progress.start();  
            //this.load();
            this.leave();
            this.today();
            this.year();
            this.month();
            this.week();
             
            this.$Progress.finish();
        },
        mounted() {
            
        }
    } 

</script> 

<style scoped>

.icon-a {
    color : whitesmoke;
} 
  .swiper-inner {
    width: 100%;
    height: 400px;
    padding-top: 50px;
    padding-bottom: 50px;
  }
  .swiper-slide {
    background-position: center;
    background-size: cover;
    width: 300px;
    height: 300px;
  } 
  
</style>