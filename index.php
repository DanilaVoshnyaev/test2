<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chaat</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assec/css/style.css?ver=<?=time()?>">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="/assec/js/main.js" type="module"></script>
</head>

<body>
    <div class="app" id="app">
        <div class="wrapper">
            <form class="autification" v-if="">
                <span>Введите логин</span>
                <input type="text" v-model="login" placeholder="Введите логин">

            </form>
        </div>
        <div class="wrepper">
            <div class="chatBox">
                <div class="chatBox__messages" v-for="message in messages" :key="message.id">
                    <span class="chatBox__messages-user">
                        {{message.login}}
                    </span>
                    <div class="chatBox__messages-message">
                        {{message.message}}
                        <button class="delete" @click="remoweMassage(message.id)"><svg
                                xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                fill="none">
                                <path
                                    d="M9.70499 9L13.85 4.855C13.9319 4.75935 13.9747 4.63631 13.9698 4.51047C13.965 4.38464 13.9128 4.26527 13.8238 4.17622C13.7347 4.08717 13.6154 4.03501 13.4895 4.03015C13.3637 4.02528 13.2406 4.06809 13.145 4.15L8.99999 8.295L4.85499 4.145C4.76083 4.05085 4.63314 3.99796 4.49999 3.99796C4.36684 3.99796 4.23914 4.05085 4.14499 4.145C4.05083 4.23915 3.99794 4.36685 3.99794 4.5C3.99794 4.63315 4.05083 4.76085 4.14499 4.855L8.29499 9L4.14499 13.145C4.09265 13.1898 4.05014 13.245 4.02012 13.307C3.99011 13.369 3.97325 13.4366 3.97059 13.5055C3.96793 13.5743 3.97953 13.643 4.00467 13.7072C4.0298 13.7713 4.06793 13.8296 4.11666 13.8783C4.16539 13.9271 4.22366 13.9652 4.28783 13.9903C4.35199 14.0155 4.42065 14.0271 4.48951 14.0244C4.55837 14.0217 4.62594 14.0049 4.68797 13.9749C4.75 13.9449 4.80516 13.9023 4.84999 13.85L8.99999 9.705L13.145 13.85C13.2406 13.9319 13.3637 13.9747 13.4895 13.9699C13.6154 13.965 13.7347 13.9128 13.8238 13.8238C13.9128 13.7347 13.965 13.6154 13.9698 13.4895C13.9747 13.3637 13.9319 13.2407 13.85 13.145L9.70499 9Z"
                                    fill="#969696" />
                            </svg></button>

                    </div>
                </div>
                <form @submit.prevent="sendMessage()" class="chatBox__form">
                    <input v-model="txt" class="chatBox__form-txt" type="text" id="message" autocomplete="off"
                        placeholder="Напишите сообщение...">
                    <button title="send__message" class="chatBox__form-btn" type="submit"><img
                            src="./assec/img/carbon_send.svg" alt=""></button>
                </form>
            </div>

        </div>
    </div>

    </div>

    </div>
    <script>

        const app = new Vue({
            el: '#app',
            data: {
                messages: [],
                txt: '',
                login: '',
            },
            mounted() {
                if (localStorage.getItem('messages')) {
                    try {
                        this.messages = JSON.parse(localStorage.getItem('messages'));
                    } catch (e) {
                        localStorage.removeItem('messages');
                    }
                }
            },
            beforeMount() {
                    this.getMessages();
            },
            methods: {
                sendMessage() {
                    const newMessage = {
                        login: this.login,
                        message: this.txt,
                    }


                    axios({
                        method: 'post',
                        url: '/action.php',
                        data: { 'action': 'save_msg', 'login': this.login, 'message': this.txt }
                    }).then((result) => {
                        if (result.statusText != 'OK') {
                            throw new Error("Could not save the data");
                        } else {
                            return result.data;
                        }
                    }).then((data) => {
                        if (data.status == "success") {
                            newMessage['id'] = data.data.id
                            console.log(newMessage);
                            this.messages.push(newMessage);
                        }
                    })


                },
                remoweMassage(id) {
                    let index = null;
                    this.messages.forEach(function (element, i) {

                        if (element.id == id) {
                            index = i
                        }
                    });
                    this.messages.splice(index, 1);
                    axios({
                        method: 'post',
                        url: '/action.php',
                        data: { 'action': 'delete_msg', 'id': id }
                    }).then((result) => {
                        if (result.statusText != 'OK') {
                            throw new Error("Could not save the data");
                        } else {
                            return result.data;
                        }
                    }).then((data) => {
                        if (data.status == "success") {

                        }
                    })

                },
                getMessages() { 
                    axios({
                        method: 'post',
                        url: '/action.php',
                        data: { 'action': 'select_msg' }
                    }).then((result) => {
                        if (result.statusText != 'OK') {
                            throw new Error("Could not save the data");
                        } else {
                            return result.data;
                        }
                    }).then((data) => {
                        if (data.status == "success") {
                            this.messages = JSON.parse(data.data);
                            console.log(this.messages);
                        }
                    })
                }
            }
        })
    </script>
</body>

</html>