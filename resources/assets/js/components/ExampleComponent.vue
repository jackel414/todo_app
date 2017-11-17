<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">To Do List</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <input v-on:change="addTask($event.target.value)" type="text" class="form-control" placeholder="New task..." aria-label="New task...">
                                <br />
                                <p class="lead">This Week</p>
                                <ul class="list-group">
                                    <li v-show="tasks" v-for="task in tasks" class="list-group-item">
                                        <span class="badge badge-pill badge-primary">{{ task.marker }}</span> {{ task.name }} <i v-on:click="addTaskToToday(task)" class="fa fa-arrow-right" aria-hidden="true"></i>
                                    </li>
                                </ul>
                            </div>
                            <div class="col">
                                <p class="lead">Today</p>
                                <ul class="list-group">
                                    <li v-show="todaysTasks" v-for="task in todaysTasks" class="list-group-item">
                                        <span class="badge badge-pill badge-primary">{{ task.marker }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Weekly Ledger - Week of </div>

                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tasks: [
                    {
                        marker: '1',
                        name: "Work on Site",
                        recurring: false,
                        done: false,
                    }
                ],
                todaysTasks: []
            }
        },
        methods: {
            addTask: function (task) {
                var newTask = {
                    name: task
                }

                this.tasks.push(newTask);
            },
            addTaskToToday: function(task) {
                this.todaysTasks.push(task);
            }
        }
    }
</script>
