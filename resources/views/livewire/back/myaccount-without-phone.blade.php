<div>
    <div class="body_container">
        <!-- body nav start -->
        <div class="body_nav">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Dashboard</a></li>
                <li>My Account</li>
            </ul>
        </div>
        <!-- body nav start -->

        <div class="heading_title">
            <h2>My Account</h2>
        </div>
        <div class="without_email_phone">
            請填寫以下資訊：Phone number
        </div>
        <div class="product_card_area">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <label>
                        Name
                    </label>
                    <div class="details_text">
                        Alice Wang
                    </div>
                    <label> 
                        Email
                    </label>
                    <div class="details_text">
                        abcde@gmail.com
                    </div>
                    <label>
                        Phone<span class="text-style-email_phone">*</span>
                    </label>
                    <div class="details_text">
                        &nbsp;
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <label>
                        Address
                    </label>
                    <div class="details_text">
                        no.123, example st., city, country
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">

                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn edit_button" href="{{route('myaccount.edit')}}">Edit</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="product_card_area">
            <div class="row">
                <div class="col-xl-12 col-lg-4 col-md-12 col-sm-12">
                    <label>
                        Password
                    </label>
                    <div class="details_text">
                        *********
                    </div>
                </div>
                <div class="col-xl-12 col-lg-4 col-md-12 col-sm-12">
                    <div class="edit_button1"></div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn edit_button" href="{{route('myaccount.edit')}}">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
