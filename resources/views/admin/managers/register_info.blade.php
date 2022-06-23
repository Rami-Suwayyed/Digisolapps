    <!-- Modal -->
    <div class="modal fade" id="Register" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-10">
                        <h5 class="modal-title" id="exampleModalLabel">{{__("Manager Information")}}</h5>
                    </div>
                    <div class="col-2 text-right">
                        <textarea id="textCopied" cols="30" rows="10" style="display: none"></textarea>
                        <span class="copy-button" id="CopyManagerInfo">
                            <i class="far fa-copy"></i>
                        </span>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-lg-4 font-weight-bold text-size-17">{{__("Full Name")}}</div>:
                        <div class="col-lg-6 text-size-17" id="FullNameInfo">{{$manager["full_name"]}}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 font-weight-bold text-size-17">{{__("Username")}}</div>:
                        <div class="col-lg-6 text-size-17" id="UsernameInfo">{{$manager["username"]}}</div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-lg-4 font-weight-bold text-size-17">{{__("Email")}}</div> :
                        <div class="col-lg-6 text-size-17" id="EmailInfo">{{$manager["email"]}}</div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-lg-4 font-weight-bold text-size-17">{{__("Password")}}</div>:
                        <div class="col-lg-6 text-size-17"  id="PasswordInfo">{{$manager["password"]}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
