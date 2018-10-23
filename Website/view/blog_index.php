<div class="container">

		<?php foreach($blogs as $blog){ ?>
        <div class="row">
            <div class="box">
            	<?php echo $blog->id;?>
            	<?php echo $blog->id_benutzer;?>
            	<?php echo $blog->test;?>
            	<?php echo $blog->timestamp;?>
            
      		</div>
        </div>
        <?php }?>

    </div>
    <!-- /.container -->
