{{-- relationships with pivot table (n-n) --}}
<span>
    <?php
        $results = $entry->{$column['entity']};

        if ($results && $results->count()) {
            foreach ($results as $value) {
            	echo ' ' . $value->name . ' ' . $value->page_name . ',';
            }
        } else {
            echo '-';
        }
    ?>
</span>
