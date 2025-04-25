<!-- Newsletter -->
<div class="section-newsletter">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h2>Enter your e-mail and <span class="text-resalt">subscribe</span> to our newsletter.</h2>
                    <p>Duis non lorem porta,  eros sit amet, tempor sem. Donec nunc arcu, semper a tempus et, consequat.</p>
                </div>
                <form id="newsletterForm" action="php/mailchip/newsletter-subscribe.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                <input class="form-control" placeholder="Your Name" name="name"  type="text" required="required">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                <input class="form-control" placeholder="Your  Email" name="email"  type="email" required="required">
                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="submit" name="subscribe" >SIGN UP</button>
                                                 </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="result-newsletter"></div>
            </div>
        </div>
    </div>
</div>
<!-- End Newsletter -->
