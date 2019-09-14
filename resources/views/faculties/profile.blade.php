@extends('layout.app')


@section('title', 'Profile')

@section('content')
    <div id="app" v-cloak>
        <h1 class="page-header"><i class="fa fa-user"></i> Profile</h1>
        <div class="card shadow">
            <div class="card-body py-4">
                <h5 class="text-info mb-3"><i class="fa fa-user-tie"></i> Faculty Information</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="d-flex">
                            <div class="mr-2">Last Name: </div>
                            <div class="font-weight-bold text-dark">@{{ user.last_name }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex">
                            <div class="mr-2">First Name: </div>
                            <div class="font-weight-bold text-dark">@{{ user.first_name }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex">
                            <div class="mr-2">Midle Name: </div>
                            <div class="font-weight-bold text-dark">@{{ user.middle_name }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex">
                            <div class="mr-2">Date of Birth: </div>
                            <div class="font-weight-bold text-dark">@{{ parseDate(user.date_of_birth) }}</div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex">
                            <div class="mr-2">College: </div>
                            <div class="font-weight-bold text-dark">@{{ faculty.college.name }}</div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex">
                            <div class="mr-2">User Type: </div>
                            <div class="font-weight-bold text-dark">@{{ user.user_type_id == 'dean' ?  'Dean' : 'Professor' }}</div>
                        </div>
                    </li>

                    
                </ul>
                
                
            </div>
        </div>


        <ul class="nav nav-tabs mt-4" id="main-nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-cog"></i> Account Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#change-password" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-lock"></i> Change Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-cog"></i> Basic Information</a>
            </li>
        </ul>
        <div class="tab-content shadow" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="mt-3">
                    <form v-on:submit.prevent="changeAccountInformation" v-on:keydown="formAccountInfo.onKeydown($event)">
                        <div class="form-group">
                          <label><i class="fa fa-user"></i> Username</label>
                          <input v-on:keydown="formAccountInfoHasChanged" v-model="formAccountInfo.username" type="text" name="username"
                            class="form-control" :class="{ 'is-invalid': formAccountInfo.errors.has('username') }">
                          <has-error :form="formAccountInfo" field="username"></has-error>
                        </div>

                        <div class="form-group">
                          <label><i class="fa fa-envelope"></i> Email</label>
                          <input v-on:keydown="formAccountInfoHasChanged" v-model="formAccountInfo.email" type="email" name="email"
                            class="form-control" :class="{ 'is-invalid': formAccountInfo.errors.has('email') }">
                          <has-error :form="formAccountInfo" field="email"></has-error>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button :disabled="formAccountInfo.busy || !formAccountInfoChanged" type="submit" class="btn btn-info">Save <i class="fa fa-edit"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- change password --}}
            <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="profile-tab">
                <div class="alert alert-warning mt-3">
                    <strong>Remember</strong> to change your password regularly for better security.
                </div>
                <div class="mt-3">
                    <form v-on:submit.prevent="changePassword" v-on:keydown="formChangePassword.onKeydown($event)">
                        <div class="form-group">
                          <label><i class="fa fa-lock"></i> Current Password</label>
                          <input v-model="formChangePassword.current_password" type="password" name="current_password"
                            placeholder="Enter Current Password" 
                            class="form-control" :class="{ 'is-invalid': formChangePassword.errors.has('current_password') }">
                          <has-error :form="formChangePassword" field="current_password"></has-error>
                        </div>

                        <div class="form-group">
                          <label><i class="fa fa-lock"></i> Password</label>
                          <input v-model="formChangePassword.password" type="password" name="password" placeholder="Enter password" 
                            class="form-control" :class="{ 'is-invalid': formChangePassword.errors.has('password') }">
                          <has-error :form="formChangePassword" field="password"></has-error>
                        </div>

                        <div class="form-group">
                          <label><i class="fa fa-lock"></i> Confirm Password</label>
                          <input v-model="formChangePassword.confirm_password" type="password" name="confirm_password"
                            placeholder="Retype password" 
                            class="form-control" :class="{ 'is-invalid': formChangePassword.errors.has('confirm_password') }">
                          <has-error :form="formChangePassword" field="confirm_password"></has-error>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button :disabled="formChangePassword.busy" type="submit" class="btn btn-info">Change Password <i class="fa fa-edit"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- basic info --}}
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <form v-on:submit.prevent="changeBasicInformation" v-on:keydown="formBasicInfo.onKeydown($event)">
                <h5 class="text-info mb-2">Address</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>House No/Subd/Village</label>
                          <input v-model="formBasicInfo.house_no" type="text" name="house_no"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('house_no') }">
                          <has-error :form="formBasicInfo" field="house_no"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Barangay</label>
                          <input v-model="formBasicInfo.barangay" type="text" name="barangay"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('barangay') }">
                          <has-error :form="formBasicInfo" field="barangay"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Town/City</label>
                          <input v-model="formBasicInfo.town_city" type="text" name="town_city"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('town_city') }">
                          <has-error :form="formBasicInfo" field="town_city"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Province</label>
                          <input v-model="formBasicInfo.province" type="text" name="province"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('province') }">
                          <has-error :form="formBasicInfo" field="province"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Country</label>
                          <input v-model="formBasicInfo.country" type="text" name="country"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('country') }">
                          <has-error :form="formBasicInfo" field="country"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>ZIP Code</label>
                          <input v-model="formBasicInfo.zip_code" type="text" name="zip_code"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('zip_code') }">
                          <has-error :form="formBasicInfo" field="zip_code"></has-error>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Place of Birth</label>
                          <input v-model="formBasicInfo.place_of_birth" type="text" name="place_of_birth"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('place_of_birth') }">
                          <has-error :form="formBasicInfo" field="place_of_birth"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Civil Status</label>
                          <input v-model="formBasicInfo.civil_status" type="text" name="civil_status"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('civil_status') }">
                          <has-error :form="formBasicInfo" field="civil_status"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Nationality</label>
                          <input v-model="formBasicInfo.nationality" type="text" name="nationality"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('nationality') }">
                          <has-error :form="formBasicInfo" field="nationality"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Religion</label>
                          <input v-model="formBasicInfo.religion" type="text" name="religion"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('religion') }">
                          <has-error :form="formBasicInfo" field="religion"></has-error>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Contact No</label>
                          <input v-model="formBasicInfo.contact_no" type="text" name="contact_no"
                            class="form-control" :class="{ 'is-invalid': formBasicInfo.errors.has('contact_no') }">
                          <has-error :form="formBasicInfo" field="contact_no"></has-error>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button :disabled="formBasicInfo.busy" type="submit" class="btn btn-info">Save <i class="fa fa-edit"></i></button>
                </div>

            </div>
            
        </div>
{{--         <div class="row">
            <div class="col-md-4">
                
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body py-4">
                        <h5>Primary Information</h5>


                    </div>
                </div>
            </div>
        </div> --}}
        
    </div>
@endsection

@push ('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                user: @json($user),
                user_profile: @json($user_profile),
                faculty: @json($faculty),
                formAccountInfo: new Form({
                    username: '',
                    email: ''
                }),
                formChangePassword: new Form({
                    current_password: '',
                    password: '',
                    confirm_password: ''
                }),
                formBasicInfo: new Form({
                    house_no: '',
                    barangay: '',
                    town_city: '',
                    province: '',
                    country: 'Philippines',
                    zip_code: '',
                    place_of_birth: '',
                    civil_status: '',
                    nationality: '',
                    religion: '',
                    contact_no: ''
                }),
                formAccountInfoChanged: false
            },
            methods: {
                parseDate(date) {
                    return moment().format('MMMM D, YYYY');
                },
                formAccountInfoHasChanged() {
                    this.formAccountInfoChanged = true;
                },
                changeAccountInformation() {
                    this.formAccountInfo.post(myRootURL + '/user_profiles/'+ this.user.id +'/update_account_information')
                    .then(response => {
                        this.formAccountInfoChanged = false;
                        toast.fire({
                            title: 'Account Information Successfully Updated',
                            type: 'success'
                        });
                    });
                },
                changePassword() {
                    this.formChangePassword.post(myRootURL + '/user_profiles/'+ this.user.id +'/update_password')
                    .then(response => {
                        toast.fire({
                            title: 'Password Successfully Updated',
                            type: 'success'
                        });
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    })
                },
                changeBasicInformation() {
                    this.formBasicInfo.post(myRootURL + '/user_profiles/'+ this.user.id +'/update_basic_info')
                    .then(response => {
                        toast.fire({
                            title: 'Basic Information Successfully Updated',
                            type: 'success'
                        });
                    })
                }
            },
            created() {
                this.formAccountInfo.username = this.user.username;
                this.formAccountInfo.email = this.user.email;


                this.formBasicInfo.house_no = this.user_profile.house_no;
                this.formBasicInfo.barangay = this.user_profile.barangay;
                this.formBasicInfo.town_city = this.user_profile.town_city;
                this.formBasicInfo.province = this.user_profile.province;
                this.formBasicInfo.country = this.user_profile.country;
                this.formBasicInfo.zip_code = this.user_profile.zip_code;
                this.formBasicInfo.place_of_birth = this.user_profile.place_of_birth;
                this.formBasicInfo.civil_status = this.user_profile.civil_status;
                this.formBasicInfo.nationality = this.user_profile.nationality;
                this.formBasicInfo.religion = this.user_profile.religion;
                this.formBasicInfo.contact_no = this.user_profile.contact_no;
            }
        });
    </script>
@endpush