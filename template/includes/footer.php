    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <ul class="list-inline text-center">

                        <?php foreach($siteinfo["social"] as $social) { ?>
                             <li class="list-inline-item">
                                <a href="<?php echo $social['url']; ?> ">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-<?php echo $social['type']; ?> fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                          <?php } ?> 
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; <?php echo $siteinfo['title']; ?> <?php echo date("Y"); ?>  <?php $sw->_em("powered by"); ?> <a target="_black" href="https://glose.media/swalize/">Swalize</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="<?php echo TEMPLATE_URL; ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo TEMPLATE_URL; ?>vendor/tether/tether.min.js"></script>
    <script src="<?php echo TEMPLATE_URL; ?>vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="<?php echo TEMPLATE_URL; ?>js/jqBootstrapValidation.js"></script>
    <script src="<?php echo TEMPLATE_URL; ?>js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="<?php echo TEMPLATE_URL; ?>js/clean-blog.min.js"></script>

</body>

</html>