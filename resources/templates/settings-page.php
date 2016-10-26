<div class="wrapper">
    <h1><?php echo $pageTitle; ?></h1>
    <p><?php echo  $description; ?></p>

    <form method="post">
        <?php foreach( $fields as $field ) { ?>
            <p>
                <?php if(isset($field->properties['label'])){ ?>
                    <label for="<?php echo $field->properties; ?>"><?php echo $field->properties['label']; ?></label>
                <?php } ?>
                <?php echo $field->html; ?>
            </p>

        <?php } ?>
        <p>
            <input type="submit" value="Save" class="button button-primary button-large" />
        </p>
    </form>

</div>