<?php if (isset($form) && !empty($form)): ?>
    <div class="form-wrapper">
        <form <?php print html_attr($form['attr'] ?? ['method' => 'POST']); ?>>

            <!-- Field Generation Start -->
            <?php foreach ($form['fields'] as $field_id => $field): ?>
                <div class="field-wrapper">

                    <!--If the label is set - print fields inside label-->                    
                    <?php if (isset($field['label'])): ?>
                        <label>
                            <span class="label">
                                <?php print $field['label']; ?>
                            </span>
                        <?php endif; ?>

                        <?php if (in_array($field['type'], ['text', 'number', 'email', 'hidden'])): ?>
                            <?php require 'form/input.tpl.php'; ?>
                        <?php elseif ($field['type'] === 'select'): ?>
                            <?php require 'form/select.tpl.php'; ?>
                        <?php endif; ?>

                        <?php if (isset($field['label'])): ?>
                        </label>
                    <?php endif; ?>
                    
                    <?php if (isset($field['error'])): ?>
                        <div class="field-error">
                            <span><?php print $field['error']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <!-- Field Generation End -->

            <?php if (isset($form['buttons']) && !empty($form['buttons'])): ?>
                <div class="button-wrapper">

                    <!-- Button Generation Start -->
                    <?php foreach ($form['buttons'] as $button_id => $button): ?>
                        <?php require 'form/button.tpl.php'; ?>
                    <?php endforeach; ?>
                    <!-- Button Generation End --> 

                </div>
            <?php endif; ?>
            
            <?php if (isset($form['message'])): ?>
                <div class="message">
                    <span><?php print $form['message']; ?></span>
                </div>
            <?php endif; ?>
        </form>
    </div>
<?php endif; ?>