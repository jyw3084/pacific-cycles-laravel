 <div>
 <div class="store-header" id="cart-header" >
        <div class="store-header-container" style="padding-top:0 !important;">
        </div>
    </div>
     <nav aria-label="breadcrumb" class="breadcrumb-1 p-3">
        <div class="container">
            <ol class="breadcrumb mb-0 breadcrumb-1">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="/shopping/cart">Store</a></li>
            <li class="breadcrumb-item breadcrumb-item-active"><a href="/shopping/cart">Cart</a></li>
            </ol>
        </div>
    </nav>
    
    <nav aria-label="breadcrumb" class="breadcrumb-2">
        <div class="container">
            <ol class="breadcrumb breadcrumb-2">
                <li class="order-process breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active"><a href="#" style="color:#000000 !important;">Order Details</a></li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="payment" href="#" style="color:#000000 !important;">Select Payment</a>
                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="payment" href="#" style="color:#000000 !important;">Payment Details</a>
                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="order-complete" href="#" >Order complete</a>
                </li>
            </ol>
        </div>
    </nav>
    <div class="container">
		<form id="confirmPaymentForm" name="confirmPayment" action="{{URL::to('shopping/order-complete')}}">
		        <div class="" id="select_payment_page">
		            
		                <section class="mt-5">
		                    <h3 class="cart-title border-below">Payment details</h3>
		                    <form action="">
		                    <div class="row section-line">
		                            <div class="col-md-3"></div>
		                            <div class="col-md-6 mt-5">
		                                <div class="row">
		                                    <div class="col-md-9 form-group">
		                                        <label>Card Number</label>
		                                        <input type="text" name="card-number" class="form-control" id="card-number" wire:model="card_number"> 
		                                        @error('card_number') <span class="text-danger error">{{ $message }}</span> @enderror
		                                    </div>
		                                    <div class="col-md-3 form-group">
		                                        <label>CVC</label>
		                                        <input type="text" name="cvc" class="form-control" id="cvc" wire:model="cvc"> 
		                                        @error('cvc') <span class="text-danger error">{{ $message }}</span> @enderror
		                                    </div>
		                                </div>
		                                <div class="row ">
		                                    <div class="col form-group">
		                                        <label>Holder Name</label>
		                                        <input type="text" name="holder-name" class="form-control" id="holder-name" wire:model="holder_name"> 
		                                        @error('holder_name') <span class="text-danger error">{{ $message }}</span> @enderror
		                                    </div>
		                                </div>

		                                <label>Expiration date</label>
		                                <div class="row ">
		                                    
		                                    <div class="col-md-3 form-group">
		                                        
		                                        <select class="form-control">
		                                            <option disabled selected>Day</option>
		                                            <?php 
		                                            for($y = 1; $y <= 31; $y++){
		                                                ?>
		                                                <option value="{{$y}}">{{$y}}</option>

		                                            <?php 
		                                            }
		                                            ?>

		                                        </select>
		                                    </div>
		                                    <div class="col-md-5 form-group">
		                                        <select class="form-control">
		                                            <option disabled selected>Month</option>
		                                            <option value="January">January</option>
		                                            <option value="February">February</option>
		                                            <option value="March">March</option>
		                                            <option value="April">April</option>
		                                            <option value="May">May</option>
		                                            <option value="June">June</option>
		                                            <option value="July">July</option>
		                                            <option value="August">August</option>
		                                            <option value="September">September</option>
		                                            <option value="October">October</option>
		                                            <option value="November">November</option>
		                                            <option value="December">December</option>
		                                            
		                                        </select> 
		                                    </div>
		                                    <div class="col-md-4 form-group">
		                                        <select class="form-control">
		                                            <option disabled selected>Year</option>
		                                            <?php 
		                                            for($x = 2021; $x <= 2050; $x++){
		                                                ?>
		                                                <option value="{{$x}}">{{$x}}</option>

		                                            <?php 
		                                            }
		                                            ?>

		                                        </select>
		                                    </div>
		                                </div>
		                                <div class="text-right">
		                                    Total: $9,000 USD
		                                </div>
		                            
		                            </div>
		                            <div class="col-md-3"></div>
		                        
		                    </div>
		                </form>
		                </section>
		                
		                <section class="mt-5 mb-5">
		                    <div class="row">
		                        <div class="col-md-3"></div>
		                        <div class="col-md-6">
		                            <div class="row">
		                                <div class="col">
		                                    <a wire:click="back" onclick="payment_page()" id="back-discount" class="mybtn btn btn-secondary">Back</a>
		                                </div>
		                                <div class="col">
		                                    <button type="submit" class="mybtn btn btn-warning">Confirm payment</button>
		                                </div>
		                            </div>
		                            
		                        </div>
		                        <div class="col-md-3"></div>
		                    </div>
		                </section>
		            </div>
		        </form>
        </div>
        </div>

        