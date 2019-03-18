<template>
    <div class="container">
        <!-- <div class="row mt-3"> 
            <date-range-picker :from="$route.query.from" :to="$route.query.to" :panel="$route.query.panel" @update="update"/>
                <div class="week-picker"></div>
    <br /><br />
    <label>Week :</label> <span id="startDate"></span> - <span id="endDate"></span>
       
        </div> -->
         <div class="card">
                <div class="card-header"> 
                   <h3 class="card-title">TimeSheet Manager</h3> 
                   <div class="card-tools"> 
                  </div> 
                </div> 
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                    <tbody><tr>
                      <th>User Id</th>
                      <th>In</th>
                      <th>Out</th>
                      <th>Total</th>
                    </tr>  
                     <tr v-for="user in users" :key="user.id">
                      <td>{{ user.user_id }} </td> 
                      <td>{{ user.punch_in | formatDate }} <i :class="{'fas fa-times-circle red': user.punch_in == null }"></i> </td> 
                      <td>{{ user.punch_out | formatDate }} <i :class="{'fas fa-times-circle red': user.punch_out == null }"></i> </td> 
                      <td>{{ calculate_time(user.punch_out,user.punch_in ) }}  <i :class="{'fas fa-times-circle red': user.punch_out == null }"></i></td>                      
                    </tr>
                  </tbody></table>
                </div></div>   
    </div>
</template>
  
<script>
    export default {

        mounted() {
            console.log('Component mounted.')
        },
        data (){
            return{
                from:'',
                to:'',
                form : new Form({id :''}),
                users:{ },  
                total:'',
                in:'',
                out:'',
                diff:'' 
            }
        },
         methods: {
              calculate_time : function(end,start)
                    { 
                          if(start!=null){
                              this.$in = start; 
                          }
                          else if(end!=null){
                              this.$out = end; 
                              this.$diff = moment.utc(moment(this.$out).diff(moment(this.$in))).format("HH:mm:ss"); 
                              return this.$diff;
                          } 
                    },

                    update(values) {
                                    this.$router.push({ query: Object.assign({}, this.$route.query, {
                                        to: values.to,
                                        from: values.from,
                                        panel: values.panel
                                                        }) 
                                     })
                     },

                     fetchtimsheet(){
                               axios.get("api/timesheetmanager").then(({ data }) => (this.users = data));
                     },
                 },
                 
                 created(){
                            this.$Progress.start();
                            this.fetchtimsheet(); 
                            this.$Progress.finish();  
                },
}

</script>