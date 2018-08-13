<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0">
                    <!-- <div class="card-header">
                        Companies
                    </div> -->
                    <div class="card-block">
                        <div class="table-container">
                            <div class="table-container">
                                <datatable v-bind="tblData1">
                                    <table-navigation :selection='tblData1.selection' :query='tblData1.query' :createRoute="'Add Company'" :editRoute="'Edit Company'" :deleteUrl="'/api/company/deletetin'">
                                    </table-navigation>
                                </datatable>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="table-container">
                            <div class="table-container">
                                <datatable v-bind="tblData2">
                                    
                                </datatable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import components from '../comps'
    import Vue from 'vue'
    import Datatable from 'vue2-datatable-component'
    import Axios from 'axios'
    import TableNavigation from '../partials/TableNavigation'

    Vue.use(Datatable);
    export default {
        components: {
            ...components,
            TableNavigation
        },
        name: 'CompanyList', // `name` is required as a recursive component
            //props: ['row'], // from the parent FriendsTable (if exists)
        data:() => ({
            locations: [],
            tblData1: {
                tblClass: 'table-responsive table-bordered',
                tblStyle: 'color: #666',
                pageSizeOptions: [5, 10, 15, 20],
                columns: (() => {
                    const cols = [
                        { title: 'Account Name', field: 'account', thComp: '', sortable: true, tdStyle: { fontStyle: 'italic' }, tdComp: 'showAccountNameTd' },
                        { title: 'Company Name', field: 'name', thComp: 'FilterTh', sortable: true, tdStyle: { fontStyle: 'italic' }, tdComp: 'showCompanyEditLinkTd' },
                        { title: 'Company Logo', field: 'image', visible: true, sortable: false, thComp: '', tdComp: 'ShowImageTd' },
                        { title: 'Website', field: 'website', visible: true, sortable: true, thComp: 'FilterTh', tdComp: 'showLinkWebsiteTd' },
                        { title: 'Address', field: 'address', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Post Code', field: 'post_code', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Company Type', field: 'type', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Phone Number', field: 'phone_number', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Parent company', field: 'is_parent', visible: true, sortable: true, thComp: '', tdComp: 'showBooleanValueTd' },
                        { title: 'Enable', field: 'is_enable', visible: true, sortable: true, thComp: '', tdComp: 'showBooleanValueTd' }
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
                tblClass: 'table-responsive table-bordered',
                tblStyle: 'color: #666',
                pageSizeOptions: [5, 10, 15, 20],
                columns: (() => {
                    const cols = [
                        { title: 'Account Name', field: 'account', thComp: '', sortable: true, tdStyle: { fontStyle: 'italic' }, tdComp: 'showAccountNameTd' },
                        { title: 'Company Name', field: 'name', thComp: 'FilterTh', sortable: true, tdStyle: { fontStyle: 'italic' }, tdComp: 'showCompanyEditLinkTd' },
                        { title: 'Company Logo', field: 'image', visible: true, sortable: false, thComp: '', tdComp: 'ShowImageTd' },
                        { title: 'Website', field: 'website', visible: true, sortable: true, thComp: 'FilterTh', tdComp: 'showLinkWebsiteTd' },
                        { title: 'Address', field: 'address', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Post Code', field: 'post_code', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Company Type', field: 'type', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Phone Number', field: 'phone_number', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Parent company', field: 'is_parent', visible: true, sortable: true, thComp: '', tdComp: 'showBooleanValueTd' },
                        { title: 'Enable', field: 'is_enable', visible: true, sortable: true, thComp: '', tdComp: 'showBooleanValueTd' }
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
            }
        }),
        watch: {
            query: {
                handler () {
                    this.handleQueryChange()
                },
                deep: true
            }
        },
        created() {
            var self = this;
            setTimeout(function() {
                self.getLocations();
                self.handleQueryChange();
            }, 1000);
        },
        methods: {
            handleQueryChange () {
                Axios.get('/api/companies/total', {
                    params:  {...this.query },
                    headers: this.$headers
                    }).then(response => {
                        this.tblData1.total = response.data
                        this.tblData2.total = response.data
                });
                Axios.get('/api/companies', {
                            params:  {...this.tblData1.query },
                            headers: this.$headers
                })
                .then(response => {
                    this.tblData1.data = response.data;
                    this.tblData2.data = response.data;
                });

            },
            getLocations() {
                Axios.get('/api/locations', {
                    params:  {},
                        headers: this.$headers
                    }).then(response => {
                        this.locations = response.data;
                });
            }
        }
    }
</script>

<style>
    .w-240 {
        width: 240px;
    }
</style>