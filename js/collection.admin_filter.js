/**
 * @file
 * Collection item admin filter behaviors.
 */

(function($, Drupal, debounce) {
  /**
   * Filters the collection item list table by a text input search string.
   *
   *
   * Text search input: input.table-filter-text
   * Target table:      input.table-filter-text[data-table]
   * Source text:       .table-filter-text-source
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.tableFilterByText = {
    attach(context, settings) {
      const $input = $('input.table-filter-text').once('table-filter-text');
      const $table = $($input.attr('data-table'));
      let $rows;
      let searching = false;

      function filterRows(e) {
        const query = $(e.target).val();
        // Case insensitive expression to find query at the beginning of a word.
        const re = new RegExp(`\\b${query}`, 'i');

        function showRow(index, row) {
          const $row = $(row);
          const $sources = $row.find(
            '.table-filter-text-source, .collection-item-entity-label',
          );
          const textMatch = $sources.text().search(re) !== -1;
          $row.closest('tr').toggle(textMatch);
        }

        // Filter if the length of the query is at least 2 characters.
        if (query.length >= 2) {
          searching = true;
          $rows.each(showRow);

          Drupal.announce(
            Drupal.t('!items items are available in the modified list.', {
              '!items': $rows.find('tbody tr:visible').length,
            }),
          );
        } else if (searching) {
          searching = false;
          $rows.show();
        }
      }

      function preventEnterKey(event) {
        if (event.which === 13) {
          event.preventDefault();
          event.stopPropagation();
        }
      }

      if ($table.length) {
        $rows = $table.find('tbody tr');

        $input.on({
          keyup: debounce(filterRows, 200),
          keydown: preventEnterKey,
        });
      }
    },
  };
})(jQuery, Drupal, Drupal.debounce);
