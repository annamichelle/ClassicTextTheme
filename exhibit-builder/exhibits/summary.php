<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
    $(function() {
        $( "#accordion" ).accordion({
            collapsible: true, active: false, heightStyle: "content"
        });
    });
</script>

<?php echo head(array('title' => metadata('exhibit', 'title'), 'bodyclass'=>'exhibits summary')); ?>

<div id="primary">
<h1><?php echo metadata('exhibit', 'title'); ?></h1>
<?php if (metadata('exhibit', 'subtitle')): ?>
    <h2><?php echo metadata('exhibit', 'subtitle'); ?></h2>
<?php endif; ?>

<?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
<div class="exhibit-description">
    <?php echo $exhibitDescription; ?>
</div>
<?php endif; ?>

<?php set_exhibit_pages_for_loop_by_exhibit(); ?>
<?php if (has_loop_records('exhibit_page')): ?>
<div class="exhibit-contents">
	<h3><?php echo __('Contents'); ?></h3>
    <div id="accordion">
        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
        <?php echo emiglio_exhibit_builder_summary_accordion($exhibitPage); ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
</div>

<div id="secondary">
<h3><?php echo cls_exhibit_link_to_first_page(); ?></h3>

<?php if (($exhibitCredits = metadata('exhibit', 'credits'))): ?>
<div class="exhibit-credits featured">
    <h2><?php echo __('Credits'); ?></h2>
    <p><?php echo $exhibitCredits; ?></p>
</div>
<?php endif; ?>
    
</div>

<?php echo foot(); ?>
