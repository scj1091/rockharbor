<style type="text/css">
#myrh-upcoming-events-options div {
    margin-bottom: 10px;
}
#myrh-upcoming-events-options span.preformatted {
    font-family: monospace;
    background-color: #eee;
}
</style>
<div id="myrh-upcoming-events-options">
    <?php echo $theme->Html->input('ccbpress_start_date', array(
        'type' => 'date',
        'label' => 'Event list start date<br/>'
    ));
    echo $theme->Html->input('ccbpress_search_length', array(
        'type' => 'select',
        'label' => 'Event list duration<br/>',
        'options' => array(
            '4 weeks' => '4 Weeks',
            '1 week' => '1 Week',
            '2 weeks' => '2 Weeks',
            '3 months' => '3 Months',
            '6 months' => '6 Months'
        )
    ));
    echo $theme->Html->input('ccbpress_how_many', array(
        'type' => 'text',
        'label' => 'Max displayed events<br/>'
    ));
    echo $theme->Html->input('ccbpress_theme', array(
        'type' => 'select',
        'label' => 'Theme<br/>',
        'options' => array(
            'graphical' => 'Graphical',
            'text' => 'Text'
        )
    ));
    echo $theme->Html->input('ccbpress_filter_by', array(
        'type' => 'select',
        'label' => 'Filter by<br/>',
        'options' => array(
            'group' => 'Group ID',
            'group_type' => 'Group Type',
            'department' => 'Department Name',
            'campus' => 'Campus ID'
        )
    ));
    echo $theme->Html->input('ccbpress_filter_value', array(
        'type' => 'text',
        'label' => 'Filter value<br/>',
        'after' => '<br/>Separate group types and department names by <span class="preformatted">||</span> and group IDs by <span class="preformatted">,</span>'
    ));
    echo $theme->Html->input('ccbpress_exclude', array(
        'type' => 'text',
        'label' => 'Excluded event IDs',
        'attrs' => array(
            'placeholder' => 'e.g. 123,631,984'
        )
    )); ?>
</div>
