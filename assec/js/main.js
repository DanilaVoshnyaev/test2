
const ajaxSend = async (formData) => {
    const response = await fetch("/action.php", {
        method: "POST",
        body: formData
        });
        if (!response.ok) {
            throw new Error(`Ошибка по адресу ${url}, статус ошибки ${response.status}`);
        }
        return await response.text();
    };

/*

createApp({
    date() {
      return {
        login:'',
      }
    },
    methods:{
          checklogin:function(e){
        if(this.login.length===0){
            console.log('Логин не введен');
            return false;
            
        }else{
            console.log('логин введен верно');

        }
    }
    }
  

}).mount('#app')

const app = new Vue({
    el:'#app',
    data:{
      messages:[],
      login:null,
    },
    methods:{
      checkLogin:function(e) {
        if(this.login.length===0){
            console.log('Логин не введен');
            return false;
            
        }else{
            console.log('логин введен верно');
        }
        e.preventDefault();/* 
        }
    }
})
 *//*       checkLogin:function(e) {
        if(this.login.length===0){
            console.log('Логин не введен');
            return false;
            
        }else{
            console.log('логин введен верно');
        }
        e.preventDefault();
        } */