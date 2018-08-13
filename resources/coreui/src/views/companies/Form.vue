<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ name: 'Companies List'}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <input type="checkbox" value="1" v-model="company.is_customer" name="is_customer" checked disabled v-show="false">
                                    <TextInput fieldName="name" vValidate="required|min:2" v-model="company.name" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <TextInput fieldName="website" v-model="company.website" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Company Type</label>
                                    <select class="form-control" v-model="company.type" v-on:change="onTypeChanged">
                                        <option value="0">Select Type</option>
                                        <option value="Distributor">Distributor</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Supplier">Supplier</option>
                                        <option value="Manufacturer">Manufacturer</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Distributor List</label>
                                    <select :disabled="company.type != 'Customer'" class="form-control" v-model="company.agent">
                                        <option value="">Select Distributor</option>
                                        <option v-for="distributor in distributors" v-bind:key="distributor.name" v-bind:value="distributor.name">{{distributor.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Primary Address</label>
                                    <vue-google-autocomplete ref="address" id="map" classname="form-control" :placeholder="company.address" :country="['nz', 'au']"
                                        v-on:placechanged="getAddressData">
                                        ></vue-google-autocomplete>
                                </div>
                                <div class="form-group col-sm-6">
                                    <TextInput fieldName="post_code" v-model="company.post_code" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <TextInput fieldName="phone_number" v-model="company.phone_number" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Account</label>
                                    <select class="form-control" v-model="company.account_id" vValidate="required">
                                        <option value="">Select Accounts</option>
                                        <option v-for="account in accounts" v-bind:key="account.id" v-bind:value="account.id">{{ account.name }}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <img :src="userImage" class="img-fluid img-thumbnail" style="height: 200px" v-show="image!=null">
                                    <img src="/img/company.png" class="img-fluid img-thumbnail" style="height: 200px" v-show="image==null">
                                    <div class="col-md-12 mb-3">
                                        <label for="">image</label>
                                        <input type="file" ref="files" placeholder="Spare image" :class="{'form-control': true,'is-invalid': errors.first('image')}"
                                               v-on:change="handleFilesUpload" name="image" v-validate="!edit ?'': ''">
                                        <div class="invalid-feedback" v-show="errors.first('image')">{{ errors.first('image')  }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- {{company}} {{edit}} -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{contacts}}
        <div :hidden="!edit" class="col-sm-12">
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fa" aria-hidden="true"></i>
                                Contact
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="card-header">
                                <router-link :to="{ path: `/contact/${$route.params.id}/create`, params: $route.params.id}">
                                    <i class="far fa-plus-square pull-right" title="Add Contact" style="font-size:20px;  color: black;"></i>
                                </router-link>
                                <br>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">First Name</th>
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
                                            <td>{{ contact.id }}</td>
                                            <td>{{ contact.last_name +' , '+ contact.first_name }}</td>
                                            <td>{{ contact.username }}</td>
                                            <td>{{ contact.phone_number }}</td>
                                            <td>{{ contact.email }}</td>
                                            <td>{{ contact.contact_type }}</td>
                                            <td>{{ contact.user_company_name }}</td>
                                            <td>
                                                <router-link :to="{ path: `/contacts/${contact.id}/edit`}">
                                                    <i class="fas fa-edit" title="Edit Contact" style="font-size:20px;  color: black;"></i>
                                                </router-link>
                                                <!-- <button @click="deleteContact(contact.user_id)" class="btn btn-danger">Delete</button> -->
                                                <router-link :to="{ path: `/companies/${company.id}/edit`}">
                                                   <span v-on:click="deleteContact(contact.user_id)"> <i class="fas fa-trash-alt"  title="Edit Location" style="font-size:20px;  color: black;"></i></span>
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
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fa" aria-hidden="true"></i>
                                Location
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="card-header">
                                <router-link :to="{ path: `/location/${$route.params.id}/create`, params: $route.params.id}">
                                    <i class="far fa-plus-square pull-right" title="Add Location" style="font-size:20px;  color: black;"></i>
                                </router-link>
                                <br>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Contact Person</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Post Code</th>
                                            <th scope="col">phone</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="location in locations" v-bind:key="location.id">
                                            <td>{{ location.user.last_name+' , '+location.user.first_name }}</td>
                                            <td>{{ location.address }}</td>
                                            <td>{{ location.post_code }}</td>
                                            <td>{{ location.phone_number }}</td>
                                            <td>
                                                <router-link :to="{ path: `/locations/${location.id}/edit`}">
                                                    <i class="fas fa-edit" title="Edit Location" style="font-size:20px;  color: black;"></i>
                                                </router-link>
                                                <!-- <button @click="deleteLocation(location.id)" class="btn btn-danger">Delete</button> -->
                                                <router-link :to="{ path: `/companies/${company.id}/edit`}">
                                                   <span v-on:click="deleteLocation(location.id)"> <i class="fas fa-trash-alt"  title="Edit Location" style="font-size:20px;  color: black;"></i></span>
                                                </router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fa" aria-hidden="true"></i>
                                Customer
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Website</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Post Code</th>
                                            <th scope="col">Phone Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>name</td>
                                            <td>website</td>
                                            <td>address</td>
                                            <td>post_code</td>
                                            <td>phone_number</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        {{locations}}

    </div>
</template>

<script>
    import axios from 'axios';
    import TextInput from '../partials/TextInput';
    import VueGoogleAutocomplete from 'vue-google-autocomplete'

    export default {
        mounted() {
            this.populate()
        },
        name: 'forms',
        components: {
            TextInput,
            VueGoogleAutocomplete
        },
        data() {
            return {
                selected_account: null,
                accounts: [],
                companies: [],
                distributors: [],
                contacts: [],
                locations: [],
                company: {
                    id: '',
                    account_id: '',
                    name: '',
                    website: '',
                    address: '',
                    post_code: '',
                    type: '0',
                    distributor: '0',
                    is_customer: 0,
                    phone_number: '',
                    image: null,
                    selectedFile: null
                },
                name: '',
                website: '',
                address: '',
                post_code: '',
                phone_number: '',
                distributor: '',
                is_customer: 0,
                image: null,
                editable: false,
                has_company: false,
                id: '',
                uploadedImage: null,
                isLoading: false,
                edit: false
            }
        },
        watch: {
            has_company(value) {
                if (value == true) {
                    if (!this.editable)
                        this.is_customer = 1;
                }
            }
        },
        created() {
            // window.localStorage.setItem("authUser",JSON.stringify(response.data))
//            let authUser = JSON.parse(window.localStorage.getItem('authUser'))

//            console.log('authUser', (authUser.user.account_id))
            //this.company.account_id = authUser.user.account_id


            this.fetchCompaniesBytype();
            this.fetchContactByCompany();
            this.fetchLocationByCompany();

            if (this.$route.params.id) {
                axios.get(`/api/company/${this.$route.params.id}`, { headers: this.$headers })
                    .then(response => {
                        this.company = response.data.data
                        console.log("company", this.company)
                        this.placeChanged()
                    }).catch(error => {
                    });
                this.edit = true
            }
        },
        computed: {
            userImage() {
                if (this.uploadedImage) {
                    return this.uploadedImage;
                }
                if (this.edit && this.company.image != '') {
                    this.image = '/img/' + this.company.image;
                    return '/img/' + this.company.image;
                } else {
                    return "/storage/" + this.image;
                }
            }
        },
        methods: {
            fetchCompaniesBytype() {
                this.isLoading = true
                axios.get("/api/distributors", { headers: this.$headers })
                    .then(response => {
                        this.distributors = response.data.Companies;
                        console.log("distributors", this.distributors)
                    })
            },
            fetchContactByCompany() {
                this.isLoading = true
                axios.get(`/api/contacts/${this.$route.params.id}/company`, { headers: this.$headers })
                    .then(response => {
                        this.contacts = response.data.contacts;
                        console.log("contacts", this.contacts)
                    })
            },
            fetchLocationByCompany() {
                this.isLoading = true
                axios.get(`/api/company/${this.$route.params.id}/locations`, { headers: this.$headers })
                    .then(response => {
                        console.log("locations result", response.data)
                        this.locations = response.data.data;
                        console.log("locations", this.locations)
                    })
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
            deleteLocation(id) {
                if (confirm("Are You Sure?")) {
                    axios.delete(`/api/location-delete/${id}`, { headers: this.$headers })
                        .then(response => {
                            alert("location Removed");
                            this.fetchLocationByCompany();
                        })
                }
            },
            placeChanged() {
                this.$refs.address.update(this.address);
            },
            getAddressData(addressData, placeResultData, id) {
                this.company.address = addressData.route + ", " + addressData.country;
                this.company.post_code = addressData.postal_code;
            },
            submitForm() {
                this.$validator.validate().then(result => {
                    if (!result) {
                        return false;
                    }
                    else {
                        let formData = new FormData();
                        if (this.image) {
                            formData.append('image', this.image, this.image.name);
                        }
                        formData.append('account_id', this.company.account_id);
                        formData.append('name', this.company.name);
                        formData.append('website', this.company.website);
                        formData.append('address', this.company.address);
                        formData.append('post_code', this.company.post_code);
                        formData.append('type', this.company.type);
                        formData.append('phone_number', this.company.phone_number);
                        this.company.image = this.image;
                        if (this.edit) {
                            formData.append('id', this.company.id);
                            formData.append('_method', 'PUT');
                            axios.post("/api/company", formData, { headers: this.$headers })
                                .then(response => {
                                    this.$router.push('/companies')
                                })
                                .catch(error => {
                                    this.$setErrorsFromResponse(err.response.data);
                                    this.$emit('error', err.response.data);
                                });

                        } else {
                            axios.post("/api/company", formData, { headers: this.$headers })
                                .then(response => {
                                    this.$router.push('/companies')
                                })
                                .catch(error => {
                                    this.$setErrorsFromResponse(err.response.data);
                                    this.$emit('error', err.response.data);
                                });
                        }

                    }
                });
            },
            handleFilesUpload($event) {
                let uploadedFiles = this.$refs.files.files;

                var files = $event.target.files || $event.dataTransfer.files;

                for (var i = 0; i < uploadedFiles.length; i++) {
                    this.image = uploadedFiles[i];
                    this.createImage(this.image, uploadedFiles[i]);
                }
            },
            createImage(item, file) {
                var image = new Image();
                var reader = new FileReader();

                reader.onload = (e) => {
                    this.uploadedImage = e.target.result;
                };

                reader.readAsDataURL(file);
            },
            removeImage: function (item) {
                item.image = false;
            },

            onTypeChanged: function (e) {
                //this.params.type = event.srcElement.value
                console.log(event.srcElement.value)
                if (event.srcElement.value == 'Distributor')
                    this.fetchCompaniesBytype()
            },
            populate(){
                axios.get("/api/accounts", { headers: this.$headers })
                        .then(response => {
                        this.accounts = response.data;
                });
            }
        }
    }
</script>