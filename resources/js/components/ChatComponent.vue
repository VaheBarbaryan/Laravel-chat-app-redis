<template>
    <div class="wrapper">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row">
                    <!-- start chat users-->
                    <div class="col-xxl-3 col-xl-6 order-xl-1">
                        <div class="card">
                            <div class="card-body p-0">
                                <ul class="nav nav-tabs nav-bordered">
                                    <li class="nav-item">
                                        <a href="#allUsers" data-bs-toggle="tab" aria-expanded="false"
                                           class="nav-link active py-2">
                                            All
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#favUsers" data-bs-toggle="tab" aria-expanded="true"
                                           class="nav-link py-2">
                                            Favourties
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#friendUsers" data-bs-toggle="tab" aria-expanded="true"
                                           class="nav-link py-2">
                                            Friends
                                        </a>
                                    </li>
                                </ul> <!-- end nav-->
                                <div class="tab-content">
                                    <div class="tab-pane show active card-body pb-0" id="newpost">

                                        <!-- start search box -->
                                        <div class="app-search">
                                            <form>
                                                <div class="mb-2 position-relative">
                                                    <input type="text" class="form-control"
                                                           placeholder="People, groups & messages..."
                                                           v-model="searchInput"/>
                                                    <span class="mdi mdi-magnify search-icon"></span>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- end search box -->
                                    </div>

                                    <!-- users -->
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-body py-0 mb-3" data-simplebar style="max-height: 546px">
                                                <a v-for="(user,index) in filteredCommands" :key="user.id"
                                                   href="javascript:void(0);"
                                                   class="text-body" @click="changeChatInfo(user.id)">
                                                    <div class="d-flex align-items-start mt-1 p-2"
                                                         :class="user.id === friendId ? 'bg-light' : ''">
                                                        <div class="w-100 overflow-hidden">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span class="float-end text-muted font-12">4:30am</span>
                                                                <span :ref="'user_icon_'+user.id" class="online-icon"
                                                                      :class="'user_icon_'+user.id"
                                                                      :style="(user.id in online_users) ? 'background: green' : ''"></span>
                                                                {{ user.name }}
                                                            </h5>
                                                            <p class="mt-1 mb-0 text-muted font-14">
                                                                <span v-if="user.unreadMessages > 0" class="w-25 float-end text-end">
                                                                    <span
                                                                        class="badge badge-danger-lighten">{{ user.unreadMessages }}</span>
                                                                </span>
                                                                <span class="w-75">{{ user.lastMessage ? user.lastMessage : "" }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>

                                            </div> <!-- end slimscroll-->
                                        </div> <!-- End col -->
                                    </div> <!-- end users -->
                                </div> <!-- end tab content-->
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div>
                    <!-- end chat users-->

                    <!-- chat area -->
                    <div class="col-xxl-9 col-xl-12 order-xl-2">
                        <div class="card">
                            <div class="card-body px-0 pb-0">
                                <ul class="conversation-list px-3 chatvue simplebar-content" style="max-height: 554px;overflow-y: auto">
                                    <li v-for="(message,index) in messages" class="clearfix"
                                        :class="message.sender_id === user_id ? 'odd' : ''">
                                        <div class="conversation-text">
                                            <div class="ctext-wrap">
                                                <i>{{ message.sender_name ? message.sender_name : message.user.name}}</i>
                                                <p>
                                                    {{ message.content ? message.content : message.messages.message }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <p class="px-3" v-if="friendId && isWriting">{{ friendName }} is writing...</p>
                            </div> <!-- end card-body -->
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col">
                                        <div class="mt-2 bg-light p-3">
                                            <form @submit.prevent class="needs-validation" name="chat-form"
                                                  id="chat-form">
                                                <div class="row">
                                                    <div class="col mb-2 mb-sm-0">
                                                        <input type="text" class="form-control border-0"
                                                               placeholder="Enter your text" v-model="chatInput">
                                                        <div class="invalid-feedback">
                                                            Please enter your messsage
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-auto">
                                                        <div class="btn-group">
                                                            <!--                                                            <a href="#" class="btn btn-light"><i-->
                                                            <!--                                                                    class="uil uil-paperclip"></i></a>-->
                                                            <!--                                                            <a href="#" class="btn btn-light"> <i-->
                                                            <!--                                                                    class='uil uil-smile'></i> </a>-->
                                                            <div class="d-grid">
                                                                <button type="button" class="btn btn-success chat-send"
                                                                        @click.prevent="sendMessage">
                                                                    <i class='uil uil-message'></i></button>
                                                            </div>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row-->
                                            </form>
                                        </div>
                                    </div> <!-- end col-->
                                </div>
                                <!-- end row -->
                            </div>
                        </div> <!-- end card -->
                    </div>
                    <!-- end chat area-->
                    <!-- end user detail -->
                </div> <!-- end row-->

            </div> <!-- container -->

        </div>
    </div>
</template>

<script>
    import {io} from 'socket.io-client'


    const options = {transports: ['websocket']};
    const socket = io('ws://localhost:8005', options);

    export default {
        props: {
            user_id: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                formData: new FormData(),
                friendId: 0,
                users: [],
                messages: [],
                online_users: {},
                chatInput: '',
                searchInput: '',
                friendName: '',
                writers: {}
            }
        },
        methods: {
            getData() {
                axios.get('/get-data/' + this.user_id).then((response) => {
                    console.log("response", response);
                    this.users = response.data.users;
                    // if(this.friendId == 0) {
                    //     this.friendId = response.data.users[0].id;
                    // }
                }).catch((error) => {
                    console.log("error", error);
                })
            },
            changeChatInfo(id) {
                this.friendId = id;
                // alert("user_id: " + this.user_id + ", receiver_id: " + this.friendId);
                axios.get(`/get-messages/${this.user_id}/${this.friendId}`).then((response) => {
                    // console.log("response", response);
                    this.messages = response.data.messages;
                    this.getData();
                    // var container = this.$el.querySelector(".chatvue .simplebar-wrapper");
                    // console.log("container", container);
                    // container.scrollTop = container.scrollHeight - container.clientHeight;
                }).catch((error) => {
                    console.log("error", error);
                })
            },
            sendMessage() {
                this.formData.append('message', this.chatInput);
                this.formData.append('receiver_id', this.friendId);

                axios.post("/send-message", this.formData).then((response) => {
                    console.log("response", response);
                    this.chatInput = '';
                    this.messages.push(response.data.data);
                    this.getData();
                }).catch((error) => {
                    console.log("error", error);
                })
            }
        },
        mounted() {
            this.getData();
            var timeout

            socket.on('connect', () => {
                console.log("EMIT user_connected " + this.user_id);
                // alert("user id "+this.user_id);
                socket.emit('user_connected', this.user_id);
            });

            socket.on("updateUserStatus", (data) => {
                console.log("data", data);
                // alert(JSON.stringify(data));
                this.online_users = data;
            });

            socket.on("private-channel:App\\Events\\PrivateMessageEvent", (message) => {
                console.log("new message", message);
                if(message.sender_id == this.friendId) {
                    this.changeChatInfo(this.friendId)
                }
                this.getData();
                console.log("this.messages", JSON.stringify(this.messages));
            })

            socket.on('write', (data) => {
                console.log("write data", data);

                clearTimeout(timeout);
                this.writers[data.user_id] = data.user_id;
                console.log("this.writers", this.writers);
                timeout = setTimeout(() => {
                    delete this.writers[data.user_id];
                    console.log("TIMEOUUOT", this.writers);
                }, 5000)
            })
        },
        computed: {
            filteredCommands() {
                if (this.searchInput) {
                    let self = this;
                    return this.users.filter(function (user) {
                        return user.name.toLowerCase().includes(self.searchInput.toLowerCase());
                    })
                } else {
                    return this.users;
                }
            }
        },
        watch: {
            chatInput() {
                socket.emit('write', {user_id: this.user_id, receiver_id: this.friendId});
            },
            friendId() {
                let user = this.users.find((user) => user.id === this.friendId)
                if(user) {
                    this.friendName = user.name
                }
            }
        },
        updated() {
            var container = this.$el.querySelector(".chatvue");
            container.scrollTop = container.scrollHeight - container.clientHeight;
        }
    }
</script>

<style scoped>
    .online-icon {
        display: inline-block;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        height: 8px;
        width: 8px;
        background: red;
    }

    .chatvue::-webkit-scrollbar {
        width: 10px;
    }

    .chatvue::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chatvue::-webkit-scrollbar-thumb {
        background: #888;
    }
</style>
