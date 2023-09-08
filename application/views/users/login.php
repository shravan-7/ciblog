<?php echo form_open('users/login'); ?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <h1 class="text-center"><?php echo $title; ?></h1>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Enter Username" name="username" required autofocus>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Enter Password" name="password" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </div>
</div>
<?php echo form_close(); ?>
