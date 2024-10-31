 <div>
 <div class="store-header" id="cart-header" >
        <div class="store-header-container" style="padding-top:0 !important;">
        </div>
    </div>
     <nav aria-label="breadcrumb" class="breadcrumb-1 p-3">
        <div class="container">
            <ol class="breadcrumb mb-0 breadcrumb-1">
            <li class="breadcrumb-item"><a href="#">{{ trans('frontend.Home') }}</a></li>
            <li class="breadcrumb-item"><a href="/store">{{ trans('frontend.store.title') }}</a></li>
            <li class="breadcrumb-item breadcrumb-item-active"><a href="/shopping/cart">{{ trans('frontend.store.cart') }}</a></li>
            </ol>
        </div>
    </nav>
    
    <nav aria-label="breadcrumb" class="breadcrumb-2">
        <div class="container">
            <ol class="breadcrumb breadcrumb-2">
                <li class="order-process breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active"><a href="#" style="color:#000000 !important;">{{ trans('frontend.store.order_detail') }}</a></li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="payment" href="#" style="color:#000000 !important;">{{ trans('frontend.store.select_payment') }}</a>
                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="payment" href="#" style="color:#000000 !important;">{{ trans('frontend.store.payment_detail') }}</a>
                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="order-complete" href="#" style="color:#000000 !important;">{{ trans('frontend.store.order_complete') }}</a>
                </li>
            </ol>
        </div>
    </nav>
    <div class="container">
		<form id="confirmPaymentForm" name="confirmPayment" action="{{URL::to('contact')}}" method="get">
		        <div class="" id="select_payment_page">
		            
		                <section class="mt-5">
		                    <h3 class="cart-title border-below">{{ trans('frontend.store.payment_detail') }}</h3>
		                    <div class="row section-line">
		                           <div id="payment-failed">
		                           	<div class="payment-failed-container">
		                           		<img src="{{URL::to('img/error.png')}}">
		                           		<p>{{ trans('frontend.store.fail_content') }}</p>
		                           	</div>
		                           </div>
		                        
		                    </div>
		                </section>
		                
		                <section class="mt-5 mb-5">
		                    <div class="row">
		                        <div class="col-md-3"></div>
		                        <div class="col-md-6">
		                            <div class="row">
		                                <div class="col">
		                                    <a href="/shopping/cart" id="back-discount" class="mybtn btn btn-secondary">{{ trans('frontend.store.back') }}</a>
		                                </div>
		                                <div class="col">
		                                    <button type="submit" class="mybtn btn btn-warning">{{ trans('frontend.contact.title') }}</button>
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

        