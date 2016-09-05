<?php
if( isset($fields) && !empty($fields) ){
    ?>
    <div class="metaboxContent">
    <?php
    foreach( $fields as $field ){
        ?>
        <div class="customField">
            <?php if( isset($field->properties['label']) ) {
                ?>
                <label for="<?php echo $field->properties->ID; ?>">
                    <?php echo $field->properties['label']; ?>
                </label>
                <?php
            } ?>

            <?php echo $field->html; ?>
        </div>
        <?php
    }
    ?>
    </div>
    <?php
} else {
    ?>
    <p>Bummer! Something went terribly wrong with your custom fields :'(</p>
    <?php
}