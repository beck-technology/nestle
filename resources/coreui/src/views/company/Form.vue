<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ name: 'Company List'}">
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
                                    <select class="form-control" v-model="company.type">
                                        <option value="0">Select Type</option>
                                        <option value="Distributor">Distributor</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Supplier">Supplier</option>
                                        <option value="Manufacturer">Manufacturer</option>
                                    </select>
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
                                    <label for="">Parent</label>
                                    <select class="form-control" v-model="company.is_parent" vValidate="required">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Enable</label>
                                    <select class="form-control" v-model="company.is_enable" vValidate="required">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    
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
            <div role="tablist" class="company-collapse">
                <b-card no-body class="mb-1">
                  <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-btn block href="#" v-b-toggle.accordion1 variant="info">Accounts</b-btn>
                  </b-card-header>
                  <b-collapse id="accordion1" visible accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <datatable v-bind="tblData1">
                            <table-navigation :selection='tblData1.selection' :query='tblData1.query' :createRoute="'Add Account'" :editRoute="'Edit Account'" :tableClass="'tblData1'" :deleteUrl="'/api/account/deletetin'">
                            </table-navigation>
                        </datatable>
                    </b-card-body>
                  </b-collapse>
                </b-card>
                <b-card no-body class="mb-1">
                  <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-btn block href="#" v-b-toggle.accordion2 variant="info">Locations</b-btn>
                  </b-card-header>
                  <b-collapse id="accordion2" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                      <p class="card-text">
                        <datatable v-bind="tblData2">
                            <table-navigation :selection='tblData2.selection' :query='tblData2.query' :createRoute="'Add Location'" :editRoute="'Edit Location'" :tableClass="'tblData2'" :deleteUrl="'/api/location/deletetin'">
                            </table-navigation>
                        </datatable>
                      </p>
                    </b-card-body>
                  </b-collapse>
                </b-card>
              </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios';
    import TextInput from '../partials/TextInput';
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    import VeeValidate from 'vee-validate'
    import Datatable from 'vue2-datatable-component'
    import components from '../comps'
    import TableNavigation from '../partials/TableNavigation'

    Vue.use(VeeValidate);
    Vue.use(Datatable);
    export default {
        name: 'forms',
        components: {
            TextInput,
            VueGoogleAutocomplete,
            TableNavigation
        },
        data:() => ({
            tblData1: {
                tblClass: 'table-responsive table-bordered tblData1',
                tblStyle: 'color: #666',
                tblId: 'dataTable1',
                pageSizeOptions: [5, 10, 15, 20],
                columns: (() => {
                    const cols = [
                        { title: 'Name', field: 'name', thComp: 'FilterTh', sortable: false, tdStyle: { fontStyle: 'italic' } },
                        { title: 'Enable', field: 'is_enable', visible: true, sortable: true, thComp: '', tdComp: 'ShowBooleanValueTd' }
                    ]
                    const groupsDef = {
                        Normal: ['email', 'name', 'website', 'address', 'post_code', 'phone_number'],
                        Sortable: [],
                        Extra: []
                    }
                    return cols.map(col => {
                            Object.keys(groupsDef).forEach(groupName => {
                                if (groupsDef[groupName].includes(col.title)) {
                                    col.group = groupName
                                }
                            })
                        return col
                    })
                })(),
                data: [],
                total: 0,
                selection: [],
                query: {},
                xprops: {
                    eventbus: new Vue()
                }
            },
            tblData2: {
                supportBackup: false,
                supportNested: false,
                tblClass: 'table-responsive table-bordered tblData2',
                tblStyle: 'color: #666',
                pageSizeOptions: [5, 10, 15, 20],
                columns: (() => {
                    const cols = [
                        { title: 'Account Name', field: 'account_name', sortable: false, thComp: 'FilterTh' },
                        { title: 'Address', field: 'address', sortable: true, thComp: 'FilterTh' },
                        { title: 'Post code', field: 'post_code', sortable: true, thComp: 'FilterTh' },
                        { title: 'Phone', field: 'phone_number', sortable: true, thComp: 'FilterTh' }
                    ]
                    const groupsDef = {
                        Normal: ['email', 'name', 'website', 'address', 'post_code', 'phone_number'],
                        Sortable: [],
                        Extra: []
                    }
                    return cols.map(col => {
                            Object.keys(groupsDef).forEach(groupName => {
                                if (groupsDef[groupName].includes(col.title)) {
                                    col.group = groupName
                                }
                            })
                        return col
                    })
                })(),
                data: [],
                total: 10,
                selection: [],
                query: {},
                xprops: {
                    eventbus: new Vue()
                }
            },
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
                is_parent: 0,
                is_enable: 1,
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
        }),
        watch: {
            has_company(value) {
                if (value == true) {
                    if (!this.editable)
                        this.is_customer = 1;
                }
            },
            'tblData1.query': {
                handler () {
                    this.getAccounts();
                },
                deep: true
            },'tblData2.query': {
                handler () {
                    this.getLocations();
                },
                deep: true
            },
        },
        created() {
            // window.localStorage.setItem("authUser",JSON.stringify(response.data))
//            let authUser = JSON.parse(window.localStorage.getItem('authUser'))

//            console.log('authUser', (authUser.user.account_id))
            //this.company.account_id = authUser.user.account_id


            this.fetchCompaniesBytype();
            this.fetchContactByCompany();
            this.fetchLocationByCompany();
            var self = this;
            setTimeout(function() {
                self.getLocations();
                self.getAccounts();
            }, 500);
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
                        formData.append('is_enable', this.company.is_enable);
                        formData.append('is_parent', this.company.is_parent);
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
            getAccounts() {
                axios.get('/api/accounts', {
                    params:  {...this.tblData1.query },
                        headers: this.$headers
                    }).then(response => {
                        this.tblData1.data = response.data.accounts;
                        this.tblData1.total = response.data.total;
                        this.accounts = response.data.data;
                });
            },
            getLocations() {
                axios.get('/api/locations', {
                    params:  {...this.tblData2.query },
                        headers: this.$headers
                    }).then(response => {
                    this.tblData2.data = response.data.locations;
                    this.tblData2.total = response.data.total;
                });
            }
        }
    }
</script>