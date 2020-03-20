   <?php
   require_once('./includes/head.php');
   require_once('./includes/classes/VideoDetailsFormProvider.php');
   ?>
   

        <div class="form-warpper">
        <div class="column">
            <?php
//                create upload form
                $formProvider = new VideoDetailsFormProvider($con);
                echo $formProvider->createUploadForm();
            ?>
        </div>
            <script class="javascript">
                $("form").submit(function(){
                   $("#loadingModal").modal("show");
                });
            </script>
        <!-- backdrop:   When backdrop is set to static, the modal will not close when clicking outside it.
            keyboard: Closes the modal when escape key is pressed-->
            <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop ='static' data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            Video uploading...
                            <img src="assets/imgs/processing.gif" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
</body>
</html>