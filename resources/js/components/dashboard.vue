<template>
  <md-card>
    <md-card-actions>
      <!-- <div class="md-subhead">
        <span>Hello !!! Have a Nyc Day</span> 
      </div>      -->
    </md-card-actions>
    <md-card-media class="swiper-inner">
      <!-- swiper -->
      <swiper :options="swiperOption"> 
          <!-- <img :src="'/image/slider/1.jpg'"> --> 
        <swiper-slide><img :src="'/image/slider/2.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/1.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/3.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/4.jpg'"></swiper-slide> 
        <swiper-slide><img :src="'/image/slider/5.jpg'"></swiper-slide> 
        <swiper-slide><img :src="'/image/slider/2.jpg'"></swiper-slide>
        <swiper-slide><img :src="'/image/slider/3.jpg'"></swiper-slide>
        <!-- <div class="swiper-pagination" slot="pagination"></div> -->
      </swiper>
    </md-card-media> 
          <!-- Small Box (Stat card) -->
        <h5 class="mb-2 mt-4">Dashboard</h5>
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ time | formateDate }}</h3> 
                <p>Daily Hours</p>
              </div>
              <div class="icon">
                <i class="fas fa-hourglass-start"></i>
              </div> 
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3> {{ weektime | formateDate  }} </h3>
                <!-- <sup style="font-size: 20px">%</sup> -->
                <p>Weekly Time</p>
              </div>
              <div class="icon">
               <i class="fas fa-clock"></i>
              </div> 
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ monthtime | formateDate }} </h3>

                <p>Monthly</p>
              </div>
              <div class="icon">
                <i class="fas fa-business-time"></i>
              </div> 
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ yeartime | formateDate }}</h3>

                <p>Total</p>
              </div>
              <div class="icon">
                <i class="fas fa-calendar-week"></i>
              </div> 
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row --> 

<div class="row">

   <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-black">
              <div class="inner">
                <h3> {{ this.form.leaves }}  </h3>
                <p>Leaves Taken </p>
              </div>
              <div class="icon">
                <i class="fas fa-glass-cheers icon-a"></i> 
              </div> 
            </div>
          </div>
    </div> 
  </md-card> 
</template>

<script> 
export default {
  
        data(){  
                return{  

                      chk:'',
                      currentTime :'',
                      form : new Form({name :'',leaves :''}),
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
                      weektime : moment.duration(0).data,
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
                               axios.get("api/timesheet").then(({ data }) => {  
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
            // this.load();
            this.today();
            this.year();
            this.month();
            this.week();
            this.leave();

            // this.currentTime = moment().format('LTS');
            // setInterval(() => this.updateCurrentTime(), 1 * 1000);

            // Fire.$on('load',() => {
            //     this.load();  //Trigger EVent when CreateUser is fired 
            //     this.today();
            // } );
            this.$Progress.finish();
        },
        mounted() {
            console.log('Component mounted.'); 
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