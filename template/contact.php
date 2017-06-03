<?php 

$pagename = 'contact';

$page = $sw->block("contact");

$meta_author = $siteinfo['author'];
$title = $page['title'].' - '.$siteinfo['title'];
$description = $page['baseline'];
$meta_url = SITE_URL.'about';
$meta_preview = SITE_URL . 'files/show/1200/'.$page['cover']; 



include 'template/includes/header.php';


?>


    <!-- Page Header -->
    <header class="masthead" style="background-image: url('<?php echo $meta_preview; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="page-heading">
                        <h1><?php echo $page['title']; ?></h1>
                        <span class="subheading"><?php echo $page['baseline']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

   
       <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                       
                       <p><?php echo $page['text']; ?></p>

                <form name="sentMessage" id="contactForm" novalidate>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group controls">
                            <label><?php $sw->_em("Name"); ?></label>
                            <input type="text" class="form-control" placeholder="Name" id="name"  required data-validation-required-message="<?php $sw->_em("Please enter your name."); ?>">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group controls">
                            <label><?php $sw->_em("Email Adress"); ?></label>
                            <input type="email" class="form-control" placeholder="Email Address" id="email" data-validation-validemail-message="<?php $sw->_em("Not a valid email address"); ?>" required data-validation-required-message="<?php $sw->_em("Please enter your email address."); ?>">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label><?php $sw->_em("Phone Number"); ?></label>
                            <input type="tel" class="form-control" placeholder="Phone Number" id="phone" required data-validation-required-message="<?php $sw->_em("Please enter your phone number."); ?>">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group controls">
                            <label><?php $sw->_em("Message"); ?></label>
                            <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="<?php $sw->_em("Please enter a message."); ?>"></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div id="success"></div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary"><?php $sw->_em("send"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <hr>
<script>
var messageForm = {
    valid : "<?php $sw->_em("Your message has been sent."); ?>",
    invalid : "<?php $sw->_em("Sorry, it seems that my mail server is not responding. Please try again later!"); ?>"
};
</script>
<?php include 'template/includes/footer.php'; ?>