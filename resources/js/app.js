
require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
 
//Vue  typer https://cngu.github.io/vue-typer/        npm install --save vue-typer

import VueTyperPlugin from 'vue-typer'
 
Vue.use(VueTyperPlugin) 

// Different Types of Packages  https://github.com/vuejs/awesome-vue

// Seeding the data sql   https://mockaroo.com/

//npm install axios --save axios

//Vue-forms  npm i vue-form

window.Form =  Form;
import { Form , HasError, AlertError } from 'vform'
  
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// Upload and convert base64 string to image  http://image.intervention.io/getting_started/installation
//php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"


//Filter Data npm install moment

Vue.filter('upText', function(text){
  return text.charAt(0).toUpperCase() + text.slice(1);
});

import moment from 'moment' 

window.moment =  moment;

Vue.filter('myDate',function(created){
  return moment(created).format(' dddd (Do MMMM YY)');
});
 
Vue.filter('formateDate', function(value) {
  if (value) {
    return moment(String(value),"HH:mm:ss").format('hh:mm:ss');
  }
});


Vue.filter('newDate', function(value) {
  if (value) {
    return moment(String(value),"HH:mm:ss").format('hh:mm:ss');
  } 
});
 
// For Dyanmic Table and pagination   npm install laravel-vue-pagination   https://github.com/gilbitron/laravel-vue-pagination

Vue.component('pagination', require('laravel-vue-pagination'));


//For Authentaction user


import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);


//Progress Bar   npm install vue-progressbar

import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '13px'
})


//ToggleButton  https://github.com/euvl/vue-js-toggle-button

import { ToggleButton } from 'vue-js-toggle-button'
Vue.component('ToggleButton', ToggleButton)

// Sweet alerts npm install sweetalert2

import swal from 'sweetalert2'
window.swal =  swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast =  toast;
 

//Passport in vue

Vue.component(
  'passport-clients',
  require('./components/passport/Clients.vue').default
);

Vue.component(
  'passport-authorized-clients',
  require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
  'passport-personal-access-tokens',
  require('./components/passport/PersonalAccessTokens.vue').default
);


// Best 404 page designs https://undraw.co/illustrations SVG

Vue.component(
  'not-found',//Creating Tags
  require('./components/NotFound.vue').default
); 


import DateRangePicker from 'vue-mj-daterangepicker'
import 'vue-mj-daterangepicker/dist/vue-mj-daterangepicker.css'

Vue.use(DateRangePicker)

 //Fetch Data using Custom event

window.Fire = new Vue();

// Swiper Image   https://github.com/surmon-china/vue-awesome-swiper/blob/master/examples/24-effect-coverflow.vue
 
import Vue from 'vue'
import VueAwesomeSwiper from 'vue-awesome-swiper'

// require styles
import 'swiper/dist/css/swiper.css'

Vue.use(VueAwesomeSwiper, /* { default global options } */)


//Route npm install vue-router

import VueRouter from 'vue-router'
Vue.use(VueRouter)

let routes = [
    { path: '/timesheet', component: require('./components/Timesheet.vue').default  },
    { path: '/dashboard', component: require('./components/Dashboard.vue').default  },
    { path: '/users', component: require('./components/Users.vue').default   },
    { path: '/profile', component: require('./components/Profile.vue').default   },
    { path: '/developer', component: require('./components/Developer.vue').default   },
    { path: '/timesheetmanager', component: require('./components/TimesheetManager.vue').default   },
    { path: '/leavemanager', component: require('./components/LeaveManager.vue').default   },
    { path: '/activedeveloper', component: require('./components/ActiveDeveloper.vue').default   },
    { path: '/date/:id', component: require('./components/Date.vue').default   }, 
    { path: '/*', component: require('./components/NotFound.vue').default}  
  ]

  const router = new VueRouter({
    mode : 'history',
    routes // short for `routes: routes`
  })
 
  
const app = new Vue({
    el: '#app',
    router,
    data : {
            search : ''
    },
    

    methods: {
      searchIt : _.debounce(() => {
        Fire.$emit('searching');
          },1000),

          // For Printing 
          printme() {
            window.print(); 
          }
    },
})
