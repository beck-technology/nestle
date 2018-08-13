<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0">
                    <div class="card-header">
                        Company
                        <router-link :to="{ name: 'Companies List'}">
                            <button type="button"  class="btn btn-sm btn-warning pull-right">Cancel</button>
                        </router-link>
                        <router-link :to="{ name: 'Edit Company'}">
                            <button type="button" style="margin-right:10px" class="btn btn-sm btn-info pull-right">Edit</button>
                        </router-link>
                    </div>
                    <div class="card-block">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <TextInput  fieldName="Name" v-model="company.name" disabled="disabled" />
                            </div>
                            <div class="form-group col-sm-4">
                                <TextInput fieldName="Website" v-model="company.website" disabled="disabled" />
                            </div>
                            <div class="form-group col-sm-4">
                                <TextInput fieldName="Phone Number" v-model="company.phone_number" disabled="disabled" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <TextInput fieldName="Primary Address" v-model="company.address" disabled="disabled" />
                            </div>
                            <div class="form-group col-sm-6">
                                <TextInput fieldName="Post Code" v-model="company.post_code" disabled="disabled" />
                            </div>
                        </div>
                        <div class="form-row" v-show="image">
                            <img :src="'/storage/' + image" class="img-fluid img-thumbnail" style="height: 200px; margin: 0 0 15px 6px;">
                            <div class="col-md-12"></div>
                        </div>
                        <div class="form-group form-actions">
                            <!-- <router-link :to="{ name: 'Edit Company'}">
                                <button type="button" class="btn btn-sm btn-info">Update</button>
                            </router-link>
                            <router-link :to="{ name: 'Companies List'}">
                                <button type="button" class="btn btn-sm btn-warning">Cancel</button>
                            </router-link> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-sm-6" v-show="users && users.length > 0">
                <div class="card">
                    <div class="card-header">
                        Users
                    </div>
                    <div class="card-block">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Sitting Contact</th>
                                    <th scope="col">User Company name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{ user.first_name }}</td>
                                    <td>{{ user.last_name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.sitting_contact }}</td>
                                    <td>{{ user.user_company_name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
             -->
        </div>
           <div class="col-sm-12">
            <div class="accordion" id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Contacts
                            </button>
                            <router-link :to="{ path: `/contact/${$route.params.id}/create`, params: $route.params.id}">
                                <i class="far fa-plus-square" title="Add New Contact" style="font-size:20px;  color: black;"></i>
                            </router-link>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">First Name</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">User Name</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Contact Type</th>
                                            <th scope="col">Company Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contact in contacts" v-bind:key="contact.id">
                                            <td>{{ contact.first_name }}</td>
                                            <td>{{ contact.last_name }}</td>
                                            <td>{{ contact.username }}</td>
                                            <td>{{ contact.email }}</td>
                                            <td>{{ contact.phone_number }}</td>
                                            <td>{{ contact.contact_type }}</td>
                                            <td>{{ contact.user_company_name }}</td>
                                            <td>
                                                <router-link :to="{ name: 'edit-contact', params: { data: contact.id }}">
                                                     <i class="fas fa-edit" title="Edit Contact" style="font-size:20px;  color: black;"></i>
                                                </router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo">
                                Locations
                            </button>
                            <router-link :to="{ path: `/location/${$route.params.id}/create`, params: $route.params.id}">
                                <i class="far fa-plus-square" style="font-size:20px;  color: black;"></i>
                            </router-link>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Address</th>
                                            <th scope="col">Post Code</th>
                                            <th scope="col">phone</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="location in locations" v-bind:key="location.id">
                                            <td>{{ location.address }}</td>
                                            <td>{{ location.post_code }}</td>
                                            <td>{{ location.phone_number }}</td>
                                            <td>
                                                <router-link :to="{ name: 'edit-ocation', params: { data: location.id }}">
                                                    <button class="btn btn-warning mb-2">Edit</button>
                                                </router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import TextInput from '../partials/TextInput';
    import VueGoogleAutocomplete from '../partials/InputGeolocation';

    export default {
        components: {
            TextInput,
            VueGoogleAutocomplete
        },
        name: 'forms',
        data() {
            return {
                companies: [],
                distributors: [],
                contacts: [],
                locations: [],
                company: {

                },
                name: '',
                website: '',
                address: '',
                post_code: '',
                phone_number: '',
                is_customer: 0,
                image: null,
                users: []
            };
        },
        created() {
            this.get();
            this.fetchCompaniesBytype();
            this.fetchLocations();
            this.fetchContactByCompany();
        },
        methods: {
             fetchCompaniesBytype() {
                this.isLoading = true
                axios.get("/api/distributors", { headers: this.$headers })
                    .then(response => {
                        this.distributors = response.data.Companies;
                        console.log("distributors", this.distributors)
                    })
                setTimeout(this.fetchCompaniesBytype, 1000);
            },
            fetchLocations() {
                this.isLoading = true
                axios.get("/api/locations", { headers: this.$headers })
                    .then(response => {
                        this.locations = response.data;
                        console.log("locations", this.locations)
                    })
                setTimeout(this.fetchLocations, 1000);
            },
            fetchContactByCompany() {
                this.isLoading = true
                axios.get(`/api/contacts/${this.$route.params.id}/company`, { headers: this.$headers })
                    .then(response => {
                        this.contacts = response.data.contacts;
                        console.log("contacts", this.contacts)
                    })
                setTimeout(this.fetchContactByCompany, 1000);
            },
            deleteContact(id) {
                if (confirm("Are You Sure?")) {
                    axios.delete(`/api/contact-delete/${id}`, { headers: this.$headers })
                        .then(response => {
                            alert("Contact Removed");
                            this.fetchContactByCompany();
                        })
                }
            },
            get() {
                axios.get(`/api/company/${this.$route.params.id}`, { headers: this.$headers })
                    .then(response => response.data)
                    .then((response) => {
                        console.log(response.data)
                        this.company = response.data
                    })

            }
        }
    }
</script>