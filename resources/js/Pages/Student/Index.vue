<template #content>
    <div class="container">
        <div class="row">
            <!-- header -->
            <div class="col-12 text-center mt-5">
                <h1>Student Management System</h1>
                <hr class="mt-3" />
            </div>
            <!-- header -->

            <!-- Add new student -->
            <div class="col-12 mt-4">
                <h2>Add New Student :</h2>
            </div>

            <div class="col-12">
                <form @submit.prevent="insertStudent">
                    <div class="row">
                        <div class="col-6 offset-3">
                            <div class="form-group">
                                <input
                                    class="form-control"
                                    type="text"
                                    name="name"
                                    v-model="student_form.name"
                                    placeholder="Student name"
                                    aria-label="default input example"
                                    required
                                />
                                <input
                                    type="file"
                                    id="img"
                                    class="d-none"
                                    name="image"
                                    accept="image/jpg, image/jpeg, image/png"
                                />
                                <label
                                    class="btn btn-primary mt-4 col-12"
                                    for="img"
                                >
                                    Select an image
                                </label>
                                <input
                                    class="form-control mt-4"
                                    type="text"
                                    name="age"
                                    v-model="student_form.age"
                                    placeholder="Student age"
                                    aria-label="default input example"
                                    required
                                />
                                <button
                                    type="submit"
                                    class="btn btn-success col-8 offset-2 mt-4"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Add new student -->

            <!-- view students -->
            <div class="col-12 mt-5">
                <h2>View Students :</h2>
            </div>

            <div class="col-12 mt-3">
                <div>
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Image</th>
                                <th scope="col">Age</th>
                                <th scope="col">Status</th>
                                <th scope="col">Update</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(student, key) in student_list">
                                <td scope="row">{{ ++key }}</td>
                                <td>{{ student.id }}</td>
                                <td>{{ student.name }}</td>
                                <td><img src="" /></td>
                                <td>{{ student.age }}</td>
                                <td>
                                    <button
                                        v-if="student.status == 'active'"
                                        class="btn btn-success"
                                        @click.prevent="
                                            statusChange(student.id)
                                        "
                                    >
                                        Active
                                    </button>

                                    <button
                                        v-else
                                        class="btn btn-danger"
                                        @click.prevent="
                                            statusChange(student.id)
                                        "
                                    >
                                        Inactive
                                    </button>
                                </td>

                                <td>
                                    <button
                                        class="btn btn-secondary"
                                        @click.prevent="editStudent(student.id)"
                                    >
                                        Update
                                    </button>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-danger"
                                        @click.prevent="
                                            deleteStudent(student.id)
                                        "
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- view students -->
        </div>
    </div>

    <!-- Modal -->
    <div
        class="modal fade"
        id="studentEdit"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Edit Student Details
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body" id="taskEditContent">
                    <form @submit.prevent="updateStudent">
                        <div class="row">
                            <div class="col-6 offset-3">
                                <div class="form-group">
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="name"
                                        v-model="student_update_form.name"
                                        placeholder="Student name"
                                        aria-label="default input example"
                                        required
                                    />
                                    <!-- <input
                                        type="file"
                                        id="img"
                                        class="d-none"
                                    /> -->
                                    <!-- <label
                                    class="btn btn-primary mt-4 col-12"
                                    for="img"
                                >
                                    Select an image
                                </label> -->
                                    <input
                                        class="form-control mt-4"
                                        type="text"
                                        name="age"
                                        v-model="student_update_form.age"
                                        placeholder="Student age"
                                        aria-label="default input example"
                                        required
                                    />
                                    <button
                                        type="submit"
                                        class="btn btn-primary col-8 offset-2 mt-4"
                                    >
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
</template>

<script>
import axios from "axios";

export default {
    components: {
        // AppLayout,
    },
    data() {
        return {
            student_form: {
                name: "",
                age: "",
                status: "active",
            },
            student_update_form: {
                name: "",
                age: "",
            },
            student_list: [],
        };
    },
    beforeMount() {
        this.getStudents();
    },
    methods: {
        // editStudent(id) {},

        async getStudents() {
            const students = (await axios.get(route("dashboard.list"))).data;
            this.student_list = students;
        },
        async insertStudent() {
            await axios.post(route("dashboard.store"), this.student_form);
            this.getStudents();
            this.student_form.name = "";
            this.student_form.age = "";
        },
        async deleteStudent(id) {
            await axios.delete(route("dashboard.delete", id));
            this.getStudents();
        },
        async statusChange(id) {
            await axios.get(route("dashboard.statusChange", id));
            this.getStudents();
        },
        async editStudent(id) {
            const student = (await axios.get(route("dashboard.get", id))).data;
            this.student_update_form = student;
            $("#studentEdit").modal("show");
        },
        async updateStudent() {
            // console.log(this.student_update_form.id);
            // console.log(this.student_update_form.name);
            // console.log(this.student_update_form.age);
            await axios.post(
                route("dashboard.update", this.student_update_form.id),
                this.student_update_form
            );
            this.getStudents();
            $("#studentEdit").modal("hide");
        },
    },
};
</script>

<style name="css" scoped>
/* .page-title {
    padding-top: 5vh;
    color: green;
} */
</style>
